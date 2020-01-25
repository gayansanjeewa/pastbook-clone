<?php

namespace App\Foundation\Bus;

use Illuminate\Support\ServiceProvider;

class BusServiceProvider extends ServiceProvider
{
    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->registerCommandBus();

        $this->registerQueryExecutor();
    }

    private function registerCommandBus(): void
    {
        $commandNamespace = "Domain\Command";
        $commandHandlerNamespace = "Domain\Command\Handlers";

        $this->app->singleton(CommandBus::class, function ($app) use ($commandNamespace, $commandHandlerNamespace) {
            return new CommandBus($app, $commandNamespace, $commandHandlerNamespace);
        });
    }

    private function registerQueryExecutor(): void
    {
        $queryNamespace = "Domain\Query";
        $queryHandlerNamespace = "Domain\Query\Handlers";

        $this->app->singleton(QueryExecutor::class, function ($app) use ($queryNamespace, $queryHandlerNamespace) {
            return new QueryExecutor($app, $queryNamespace, $queryHandlerNamespace);
        });
    }
}
