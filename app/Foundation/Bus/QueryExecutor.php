<?php

namespace App\Foundation\Bus;

use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * @author Gayan Sanjeewa <iamgayan@gmail.com>
 */
class QueryExecutor
{
    /**
     * @var array
     */
    protected $handlers = [];

    /**
     * @var Container
     */
    private $container;

    /**
     * @var string
     */
    private $queryNamespace;

    /**
     * @var string
     */
    private $handlerNamespace;

    /**
     * @param Container $container
     * @param string $queryNamespace
     * @param string $handlerNamespace
     */
    public function __construct(Container $container, $queryNamespace, $handlerNamespace)
    {
        $this->container = $container;
        $this->queryNamespace = $queryNamespace;
        $this->handlerNamespace = $handlerNamespace;
    }

    /**
     * {@inheritdoc}
     * @throws BindingResolutionException
     */
    public function execute($query)
    {
        $queryHandler = $this->getCommandHandler($query);
        return $this->container->call([$queryHandler, "__invoke"], [$query]);
    }

    /**
     * @param mixed $query
     * @return bool|mixed
     * @throws BindingResolutionException
     */
    private function getCommandHandler($query)
    {
        if (!$this->hasCommandHandler($query)) {
            $this->registerCommandHandler($query);
        }

        return $this->container->make($this->handlers[get_class($query)]);
    }


    /**
     * Determine if the given command has a handler.
     *
     * @param mixed $query
     * @return bool
     */
    public function hasCommandHandler($query)
    {
        return array_key_exists(get_class($query), $this->handlers);
    }

    /**
     * Register a command handler
     * @param $query
     */
    private function registerCommandHandler($query)
    {
        $handler = $this->handlerNamespace
            . "\\" . class_basename(get_class($query)) . "Handler";

        if (!class_exists($handler)) {
            throw  new \InvalidArgumentException($handler . "Not found!");
        }

        $this->handlers[get_class($query)] = $handler;
    }
}
