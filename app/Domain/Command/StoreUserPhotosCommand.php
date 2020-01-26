<?php


namespace Domain\Command;

/**
 * @author Gayan Sanjeewa <iamgayan@gmail.com>
 */
final class StoreUserPhotosCommand
{
    /**
     * @var int
     */
    private $userId;

    /**
     * @var array
     */
    private $photos;

    /**
     * @param int $userId
     * @param array $photos
     */
    public function __construct(int $userId, array $photos)
    {
        $this->userId = $userId;
        $this->photos = $photos;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return array
     */
    public function getPhotos(): array
    {
        return $this->photos;
    }
}
