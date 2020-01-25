<?php

namespace App\Http\Controllers;

use App\User;
use Domain\Command\GrantSocialAuthCommand;
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

        return redirect()->to('/home');
    }
}
