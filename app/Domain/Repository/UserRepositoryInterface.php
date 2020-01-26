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
    public function findWithPhotos(int $userId): User;

    /**
     * @param int $providerId
     * @return User
     */
    public function findByProvider(int $providerId): User;

    /**
     * @param array $user
     * @return User
     */
    public function create(array $user): User;
}
