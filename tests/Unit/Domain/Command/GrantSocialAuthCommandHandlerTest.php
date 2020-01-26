<?php


namespace Tests\Unit\Domain\Command;

use Domain\Command\Handlers\GrantSocialAuthCommandHandler;
use Domain\Repository\UserRepositoryInterface;
use Tests\TestCase;

/**
 * @author Gayan Sanjeewa <iamgayan@gmail.com>
 */
class GrantSocialAuthCommandHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function _invoke_withValidCommand_SuccessfullyGrantAccess()
    {
        /** @var UserRepositoryInterface $userRepository */
        $userRepository = $this->createMock(UserRepositoryInterface::class);

        $commandHandler = new GrantSocialAuthCommandHandler($userRepository);

        // FIXME: Illuminate\Database\QueryException : could not find driver (SQL: PRAGMA foreign_keys = ON;) error
    }
}
