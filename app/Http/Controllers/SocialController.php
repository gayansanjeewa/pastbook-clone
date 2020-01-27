<?php

namespace App\Http\Controllers;

use App\Events\UserAlbumPhotosFoundEvent;
use DateTime;
use Domain\Command\AuthorizeSocialUserCommand;
use Domain\Command\StoreUserPhotosCommand;
use Domain\Exceptions\AlbumPhotosNotFoundException;
use Domain\Query\GetBestPhotosForRangeQuery;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Event;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @author Gayan Sanjeewa <iamgayan@gmail.com>
 */
class SocialController extends Controller
{
    /**
     * @param string $provider
     * @return RedirectResponse
     * @throws \InvalidArgumentException
     */
    public function redirect(string $provider): RedirectResponse
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * @param string $provider
     * @return RedirectResponse
     * @throws AuthenticationException
     * @throws BindingResolutionException
     */
    public function callback(string $provider): RedirectResponse
    {
        /** @var SocialiteUser $socialiteUser */
        $socialiteUser = Socialite::driver($provider)->user();

        $this->command->dispatch(new AuthorizeSocialUserCommand($provider, [
            'name' => $socialiteUser->name,
            'email' => $socialiteUser->email,
            'provider_id' => $socialiteUser->id
        ]));

        if (!auth()->check()) {
            throw new AuthenticationException();
        }

        $since = DateTime::createFromFormat('d-m-Y', config('duration.since'))->getTimestamp();
        $until = DateTime::createFromFormat('d-m-Y', config('duration.until'))->getTimestamp();

        try {
            $photos = $this->query->execute(new GetBestPhotosForRangeQuery($since, $until, $socialiteUser->token));
        } catch (AlbumPhotosNotFoundException $exception) {
            request()->session()->flash('error', 'Sorry. No photos found!');
            return redirect()->to('/home');
        }

        $this->command->dispatch(new StoreUserPhotosCommand(auth()->id(), $photos));

        Event::dispatch(new UserAlbumPhotosFoundEvent(auth()->id()));

        request()->session()->flash('success', 'Thanks, you will receive your best 9 photos of 2019 by email!!');
        return redirect()->to('/home');
    }
}
