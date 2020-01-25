<?php


namespace App\Providers;

use App\Foundation\OAuth\Client\Facebook;
use App\Foundation\Payment\Stripe;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

/**
 * @author Gayan Sanjeewa <iamgayan@gmail.com>
 */
class OAppServiceProvider extends ServiceProvider
{
    /**
     * @inheritDoc
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(Facebook::class, function ($app) {
            return new Facebook($app['config']->get('services.facebook'));
        });
    }
}
