<?php


namespace Domain\Command\Handlers;

use Domain\Command\StoreUserPhotosCommand;
use Domain\Repository\PhotoRepositoryInterface;

/**
 * @author Gayan Sanjeewa <iamgayan@gmail.com>
 */
class StoreUserPhotosCommandHandler
{
    /**
     * @var PhotoRepositoryInterface
     */
    private $photoRepository;

    /**
     * @param PhotoRepositoryInterface $photoRepository
     */
    public function __construct(PhotoRepositoryInterface $photoRepository)
    {
        $this->photoRepository = $photoRepository;
    }

    /**
     * @param StoreUserPhotosCommand $command
     */
    public function __invoke($command)
    {
        $photos = [];
        foreach ($command->getPhotos() as $photoDetails) {
            $photos[] = [
                'user_id' => $command->getUserId(),
                'picture_id' => $photoDetails['id'],
                'image_source' => current($photoDetails['images'])['source'],
            ];
        }

//        $this->photoRepository->insert($photos);
    }
}
