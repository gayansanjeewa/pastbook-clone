<?php

namespace App\Listeners;

use App\Events\UserAlbumPhotosFoundEvent;
use App\Mail\SendUserAlbumEmail;
use Domain\Repository\UserRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

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
     * @return void
     */
    public function handle(UserAlbumPhotosFoundEvent $event)
    {
        $user = $this->userRepository->find($event->userId);

        // TODO@Gayan: DON'T pass the user
        Mail::to($user->email)->send(new SendUserAlbumEmail($user));
    }
}
