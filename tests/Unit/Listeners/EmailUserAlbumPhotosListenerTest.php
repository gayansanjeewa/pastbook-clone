<?php


namespace Tests\Unit\Listeners;


use App\Events\UserAlbumPhotosFoundEvent;
use App\Listeners\EmailUserAlbumPhotosListener;
use App\Mail\SendUserAlbumEmail;
use App\User;
use Domain\Model\Photo;
use Domain\Repository\UserRepositoryInterface;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Mail;
use Mockery;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

/**
 * @author Gayan Sanjeewa <iamgayan@gmail.com>
 */
class EmailUserAlbumPhotosListenerTest extends TestCase
{
    use DatabaseMigrations;

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
     * @test
     */
    public function handle_withValidEvent_processSendUserAlbumEmail()
    {

        $event = new UserAlbumPhotosFoundEvent(42);

        $user    = factory(User::class)->create();
        $photo    = factory(Photo::class, 9)->create(['user_id' => $user->id]);
        $user->photos()->saveMany($photo);


        $photos = $user->photos()->get()->map(function (Photo $photo) {
            return $photo->image_source;
        })->toArray();

        Mail::shouldReceive('to')
            ->with($user->email)
            ->once()
            ->andReturnSelf()
            ->shouldReceive('send')
            ->with(Mockery::on(function (SendUserAlbumEmail $mail) use ($photos, $user) {
                return $mail->getUserDetails()['name'] === $user->name
                    && $mail->getPhotos() === $photos;
            }));

        $this->userRepository
            ->expects($this->once())
            ->method('findWithPhotos')
            ->with($event->userId)
            ->willReturn($user);

        $listener = new EmailUserAlbumPhotosListener($this->userRepository);
        $listener->handle($event);
    }
}
