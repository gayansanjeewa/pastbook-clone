<?php


namespace App\Repository;

use App\User;
use Domain\Repository\UserRepositoryInterface;

/**
 * @author Gayan Sanjeewa <iamgayan@gmail.com>
 */
class UserRepository implements UserRepositoryInterface
{

    /**
     * {@inheritDoc}
     */
    public function find(int $userId) : User
    {
        return User::query()->find($userId)->first();
    }
}
