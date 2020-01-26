<?php

namespace App\Http\Controllers;

use App\Events\UserAlbumPhotosFoundEvent;
use DateTime;
use Domain\Command\GrantSocialAuthCommand;
use Domain\Command\StoreUserPhotosCommand;
use Domain\Exceptions\AlbumPhotosNotFoundException;
use Domain\Query\GetAlbumPhotosForRangeQuery;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Container\BindingResolutionException;
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
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * @param string $provider
     * @return RedirectResponse
     * @throws BindingResolutionException
     * @throws FacebookSDKException
     * @throws FacebookResponseException
     * @throws FacebookSDKException
     * @throws AuthenticationException
     */
    public function callback($provider)
    {
        /** @var SocialiteUser $socialiteUser */
        $socialiteUser = Socialite::driver($provider)->user();

        $this->command->dispatch(new GrantSocialAuthCommand($provider, [
            'name' => $socialiteUser->name,
            'email' => $socialiteUser->email,
            'provider_id' => $socialiteUser->id
        ]));

        if (!auth()->check()) {
           throw new AuthenticationException();
        }

        $since = DateTime::createFromFormat('d-m-Y', config('duration.since'))->getTimestamp(); // TODO@Gayan: 01-01-2019
        $until = DateTime::createFromFormat('d-m-Y', config('duration.until'))->getTimestamp(); // TODO@Gayan: 31-12-2019

        // TODO@Gayan: GetAlbumPhotosForRangeQuery => GetBestPhotosForRangeQuery
        $photos = $this->query->execute(new GetAlbumPhotosForRangeQuery($since, $until, $socialiteUser->token));

        if (empty($photos)) {
            throw new AlbumPhotosNotFoundException();
        }

        $this->command->dispatch(new StoreUserPhotosCommand(auth()->id(), $photos));

        event(new UserAlbumPhotosFoundEvent(auth()->id()));

        // TODO@Gayan: Email images

        return redirect()->to('/home');
    }
}
