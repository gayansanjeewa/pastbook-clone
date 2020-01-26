<?php


namespace Domain\Repository;

use App\User;

/**
 * @author Gayan Sanjeewa <iamgayan@gmail.com>
 */
interface UserRepositoryInterface
{
    /**
     * @param int $userId
     * @return User
     */
    public function find(int $userId): User;
}
