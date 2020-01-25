<?php

namespace App\Http\Controllers;

use App\User;
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
     */
    public function redirect($provider)
    {
        try {
            return Socialite::driver($provider)->redirect();
        } catch (\Exception $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }
    }

    /**
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback($provider)
    {
        /** @var SocialiteUser $socialiteUser */
        $socialiteUser = Socialite::driver($provider)->user();
        $user = $this->createUser($socialiteUser, $provider);
        auth()->login($user);
        return redirect()->to('/home');
    }

    /**
     * @param SocialiteUser $socialiteUser
     * @param string $provider
     * @return User
     */
    private function createUser(SocialiteUser $socialiteUser, $provider)
    {
        /** @var User $user */
        $user = User::where('provider_id', $socialiteUser->id)->first();
        if (!$user) {
            $user = User::create([
                'name' => $socialiteUser->name,
                'email' => $socialiteUser->email,
                'provider' => $provider,
                'provider_id' => $socialiteUser->id
            ]);
        }
        return $user;
    }
}
