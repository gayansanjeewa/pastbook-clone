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
     * @var array
     */
    private $userDetails;

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
        $this->userDetails = $userDetails;
        $this->photos = $photos;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->view('emails.user.album')
            ->subject('PastBook Memories...')
            ->with([
                'name' => $this->getUserDetails()['name'],
                'photos' => $this->getPhotos(),
            ]);
    }

    /**
     * @return array
     */
    public function getUserDetails(): array
    {
        return $this->userDetails;
    }

    /**
     * @return array
     */
    public function getPhotos(): array
    {
        return $this->photos;
    }
}
