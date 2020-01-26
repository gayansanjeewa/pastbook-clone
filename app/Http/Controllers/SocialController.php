<?php

namespace App\Http\Controllers;

use App\User;
use DateTime;
use Domain\Command\GrantSocialAuthCommand;
use Domain\Query\GetAlbumPhotosForRangeQuery;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Facebook\GraphNodes\GraphNode;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
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

        if (auth()->check()) {
            $since = DateTime::createFromFormat('d-m-Y', '25-01-2020')->getTimestamp();
            $until = DateTime::createFromFormat('d-m-Y', '26-01-2020')->getTimestamp();

            $photos = $this->query->execute(new GetAlbumPhotosForRangeQuery($since, $until, $socialiteUser->token));
        }


        return redirect()->to('/home');
    }
}
