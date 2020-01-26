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
     * @return User|null
     */
    public function getByProvider(int $providerId);

    /**
     * @param array $user
     * @return User
     */
    public function create(array $user): User;

    /**
     * @param array $photos
     * @param int $userId
     */
    public function removeAndInsert(array $photos, int $userId): void;
}
