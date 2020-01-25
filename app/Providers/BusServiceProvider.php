<?php

namespace App\Providers;

use App\Foundation\Bus\CommandBus;
use App\Foundation\Bus\QueryExecutor;
use Illuminate\Support\ServiceProvider;

/**
 * @author Gayan Sanjeewa <iamgayan@gmail.com>
 */
class BusServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * @inheritDoc
     */
    public function register()
    {
         $commandNamespace = "Domain\Command";
         $commandHandlerNamespace = "Domain\Command\Handlers";

        $this->app->singleton(CommandBus::class, function ($app) use ($commandNamespace, $commandHandlerNamespace){
            return new CommandBus($app, $commandNamespace, $commandHandlerNamespace);
        });

         $queryNamespace = "Domain\Query";
         $queryHandlerNamespace = "Domain\Query\Handlers";

        $this->app->singleton(QueryExecutor::class, function ($app) use ($queryNamespace, $queryHandlerNamespace){
            return new QueryExecutor($app, $queryNamespace, $queryHandlerNamespace);
        });
    }
}
