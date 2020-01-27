<?php

namespace App\Listeners;

use App\Events\UserAlbumPhotosFoundEvent;
use App\Mail\SendUserAlbumEmail;
use Domain\Model\Photo;
use Domain\Repository\UserRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

/**
 * @author Gayan Sanjeewa <iamgayan@gmail.com>
 */
class EmailUserAlbumPhotosListener implements ShouldQueue
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * Create the event listener.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Handle the event.
     *
     * @param  UserAlbumPhotosFoundEvent  $event
     */
    public function handle(UserAlbumPhotosFoundEvent $event)
    {
        $user = $this->userRepository->findWithPhotos($event->userId);

        $userDetails = [
            'name' => $user->name
        ];

        $photos = $user->photos()->get()->map(function (Photo $photo) {
            return $photo->image_source;
        })->toArray();


        Mail::to($user->email)->send(new SendUserAlbumEmail($userDetails, $photos));
    }
}
