<?php


namespace Domain\Command\Handlers;

use App\User;
use Domain\Command\GrantSocialAuthCommand;
use Domain\Repository\UserRepositoryInterface;
use Laravel\Socialite\Two\User as SocialiteUser;

/**
 * @author Gayan Sanjeewa <iamgayan@gmail.com>
 */
class GrantSocialAuthCommandHandler
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param GrantSocialAuthCommand $command
     */
    public function __invoke($command)
    {
        $socialiteUser = $command->getCredentials();

        /** @var User $user */
        $user = $this->userRepository->findByProvider($socialiteUser['provider_id']);

        if (!$user) {
            $user = $this->userRepository->create([
                'name' => $socialiteUser['name'],
                'email' => $socialiteUser['email'],
                'provider' => $command->getProvider(),
                'provider_id' => $socialiteUser['provider_id']
            ]);
        }

        auth()->login($user);
    }
}
