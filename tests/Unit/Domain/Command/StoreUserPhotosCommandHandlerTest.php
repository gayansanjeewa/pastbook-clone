<?php


namespace Tests\Unit\Domain\Command;


use Domain\Command\Handlers\StoreUserPhotosCommandHandler;
use Domain\Command\StoreUserPhotosCommand;
use Domain\Model\Photo;
use Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class StoreUserPhotosCommandHandlerTest extends TestCase
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
     * @return StoreUserPhotosCommandHandler
     */
    public function getHandler(): StoreUserPhotosCommandHandler
    {
        return new StoreUserPhotosCommandHandler($this->userRepository);
    }

    /**
     * @test
     */
    public function invoke_withCommand_successfullyPersists()
    {
        $photosDetail = [
            [
                'id' => 1234567890,
                'images' => [
                    [
                        'source' => 'https://link-to-image'
                    ]
                ]
            ]
        ];

        $command = new StoreUserPhotosCommand(42, $photosDetail);

        $this->userRepository
            ->expects($this->once())
            ->method('removeAndInsert')
            ->with($this->callback(function (array $photos) use ($photosDetail, $command) {

                /** @var Photo $photo */
                $photo = $photos[0];
                return $photo->user_id === $command->getUserId()
                    && $photo->picture_id === $photosDetail[0]['id']
                    && $photo->image_source === current($photosDetail[0]['images'])['source'];
            }));

        $this->getHandler()->__invoke($command);
    }
}
