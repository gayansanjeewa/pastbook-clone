<?php


namespace Domain\Command\Handlers;

use Domain\Command\StoreUserPhotosCommand;
use Domain\Model\Photo;
use Domain\Repository\UserRepositoryInterface;

/**
 * @author Gayan Sanjeewa <iamgayan@gmail.com>
 */
final class StoreUserPhotosCommandHandler
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
     * @param StoreUserPhotosCommand $command
     */
    public function __invoke($command)
    {
        $photos = [];

        foreach ($command->getPhotos() as $photoDetails) {
            $photo = new Photo();
            $photo->user_id = $command->getUserId();
            $photo->picture_id = $photoDetails['id'];
            $photo->image_source = current($photoDetails['images'])['source'];

            $photos[] = $photo;
        }

        $this->userRepository->removeAndInsert($photos, $command->getUserId());
    }
}
