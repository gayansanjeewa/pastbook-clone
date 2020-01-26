<?php


namespace App\Providers;

use App\Repository\PhotoRepository;
use Domain\Repository\PhotoRepositoryInterface;
use Illuminate\Support\ServiceProvider;

/**
 * @author Gayan Sanjeewa <iamgayan@gmail.com>
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->app->bind(PhotoRepositoryInterface::class, PhotoRepository::class);
    }
}
