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
     * @inheritDoc
     */
    public function findWithPhotos(int $userId): User
    {
        return User::query()->with('photos')->find($userId)->first();
    }

    /**
     * @inheritDoc
     */
    public function getByProvider(int $providerId)
    {
        return User::query()->where('provider_id', $providerId)->get()->first();
    }

    /**
     * @inheritDoc
     */
    public function create(array $user): User
    {
        return User::create($user);
    }

    /**
     * @inheritDoc
     */
    public function removeAndInsert(array $photos, int $userId): void
    {
        $user = User::query()->find($userId);
        $user->photos()->delete();
        $user->photos()->saveMany($photos);
    }
}
