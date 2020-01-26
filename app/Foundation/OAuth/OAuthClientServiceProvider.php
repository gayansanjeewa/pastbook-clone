<?php


namespace App\Foundation\OAuth;

use App\Foundation\OAuth\Contracts\Factory;
use Illuminate\Support\ServiceProvider;

/**
 * @author Gayan Sanjeewa <iamgayan@gmail.com>
 */
class OAuthClientServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Factory::class, function ($app) {
            return new OAuthClientManager($app);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Factory::class];
    }
}
