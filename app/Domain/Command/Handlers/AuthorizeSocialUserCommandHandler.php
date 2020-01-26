<?php


namespace Domain\Command\Handlers;

use App\User;
use Domain\Command\AuthorizeSocialUserCommand;
use Domain\Repository\UserRepositoryInterface;
use Laravel\Socialite\Two\User as SocialiteUser;

/**
 * @author Gayan Sanjeewa <iamgayan@gmail.com>
 */
final class AuthorizeSocialUserCommandHandler
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
     * @param AuthorizeSocialUserCommand $command
     */
    public function __invoke($command)
    {
        $socialiteUser = $command->getCredentials();

        $user = $this->userRepository->getByProvider($socialiteUser['provider_id']);

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
