<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendUserAlbumEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var User
     */
    private $user;

    /**
     * @var array
     */
    private $photos;

    /**
     * @param array $userDetails
     * @param array $photos
     */
    public function __construct(array $userDetails, array $photos)
    {
        $this->user = $userDetails;
        $this->photos = $photos;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.user.album')
            ->with([
                'name' => $this->user['name'],
                'photos' => $this->photos,
            ]);
    }
}
