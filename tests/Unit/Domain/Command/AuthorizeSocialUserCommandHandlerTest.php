<?php


namespace Tests\Unit\Domain\Command;

use App\User;
use Domain\Command\AuthorizeSocialUserCommand;
use Domain\Command\Handlers\AuthorizeSocialUserCommandHandler;
use Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

/**
 * @author Gayan Sanjeewa <iamgayan@gmail.com>
 */
class AuthorizeSocialUserCommandHandlerTest extends TestCase
{
    /**
     * @var UserRepositoryInterface|MockObject
     */
    private $userRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
    }

    /**
     * @return AuthorizeSocialUserCommandHandler
     */
    public function createCommand(): AuthorizeSocialUserCommandHandler
    {
        return new AuthorizeSocialUserCommandHandler($this->userRepository);
    }

    /**
     * @test
     */
    public function invoke_withValidCommandAndExistingUser_successfullyGrantAccess()
    {
        $credentials = [
            'name' => 'foo',
            'email' => 'foo@acme.io',
            'provider_id' => 1548042448670314
        ];

        $user = new User();
        $user->id = 42;
        $user->name = 'foo';
        $user->email = 'foo@acme.io';

        $this->userRepository
            ->expects($this->once())
            ->method('getByProvider')
            ->with($this->callback(function ($providerId) {
                return 1548042448670314 === $providerId;
            }))
            ->willReturn($user);

        $this->userRepository
            ->expects($this->never())
            ->method('create');

        $this->createCommand()->__invoke(new AuthorizeSocialUserCommand('facebook', $credentials));

        $this->assertAuthenticated();
        $this->be($user);
        $this->assertAuthenticatedAs($user);
    }

    /**
     * @test
     */
    public function invoke_withValidCommandAndNewUser_SuccessfullyGrantAccess()
    {
        $credentials = [
            'name' => 'foo',
            'email' => 'foo@acme.io',
            'provider_id' => 1548042448670314
        ];

        $user = new User();
        $user->id = 42;
        $user->name = 'foo';
        $user->email = 'foo@acme.io';

        $command = new AuthorizeSocialUserCommand('facebook', $credentials);

        $this->userRepository
            ->expects($this->once())
            ->method('getByProvider')
            ->with($this->callback(function ($providerId) {
                return 1548042448670314 === $providerId;
            }))
            ->willReturn(null);

        $this->userRepository
            ->expects($this->once())
            ->method('create')
            ->with($this->callback(function ($credentials) {
                return 1548042448670314 === $credentials['provider_id']
                    && 'foo' === $credentials['name']
                    && 'foo@acme.io' === $credentials['email']
                    && 'facebook' === $credentials['provider'];
            }));

        $this->createCommand()->__invoke($command);

        $this->assertAuthenticated();
        $this->be($user);
        $this->assertAuthenticatedAs($user);
    }
}
