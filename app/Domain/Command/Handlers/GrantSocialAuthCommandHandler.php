<?php


namespace Domain\Command\Handlers;

use App\User;
use Domain\Command\GrantSocialAuthCommand;
use Laravel\Socialite\Two\User as SocialiteUser;

/**
 * @author Gayan Sanjeewa <iamgayan@gmail.com>
 */
class GrantSocialAuthCommandHandler
{
    /**
     * @param GrantSocialAuthCommand $command
     */
    public function __invoke($command)
    {
        $socialiteUser = $command->getCredentials();

        /** @var User $user */
        $user = User::where('provider_id', $socialiteUser['provider_id'])->first();

        if (!$user) {
            $user = User::create([
                'name' => $socialiteUser['name'],
                'email' => $socialiteUser['email'],
                'provider' => $command->getProvider(),
                'provider_id' => $socialiteUser['provider_id']
            ]);
        }

        auth()->login($user);
    }
}
