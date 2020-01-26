<?php


namespace Domain\Exceptions;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Gayan Sanjeewa <iamgayan@gmail.com>
 */
class AlbumPhotosNotFoundException extends NotFoundHttpException
{
    public function __construct()
    {
        parent::__construct('Photos not found');
    }
}
