<?php

namespace App\Listeners;

use App\Events\UserAlbumPhotosFoundEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EmailUserAlbumPhotosListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserAlbumPhotosFoundEvent  $event
     * @return void
     */
    public function handle(UserAlbumPhotosFoundEvent $event)
    {
        //
    }
}
