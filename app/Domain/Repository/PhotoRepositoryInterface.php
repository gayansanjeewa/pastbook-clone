<?php


namespace Domain\Repository;

/**
 * @author Gayan Sanjeewa <iamgayan@gmail.com>
 */
interface PhotoRepositoryInterface
{

    /**
     * @param array $photos
     */
    public function insert(array $photos);
}
