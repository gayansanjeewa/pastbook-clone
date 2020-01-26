<?php


namespace App\Repository;

use Domain\Model\Photo;
use Domain\Repository\PhotoRepositoryInterface;

/**
 * @author Gayan Sanjeewa <iamgayan@gmail.com>
 */
class PhotoRepository implements PhotoRepositoryInterface
{

    /**
     * {@inheritDoc}
     */
    public function insert(array $photos)
    {
        Photo::query()->insert($photos);
    }
}
