<?php


namespace App\Foundation\Bus;

use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * @author Gayan Sanjeewa <iamgayan@gmail.com>
 */
class CommandBus
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
    private $commandNamespace;

    /**
     * @var string
     */
    private $handlerNamespace;

    /**
     * @param Container $container
     * @param string $commandNamespace
     * @param string $handlerNamespace
     */
    public function __construct(Container $container, $commandNamespace, $handlerNamespace)
    {
        $this->container = $container;
        $this->commandNamespace = $commandNamespace;
        $this->handlerNamespace = $handlerNamespace;
    }

    /**
     * {@inheritdoc}
     * @throws BindingResolutionException
     */
    public function dispatch($command)
    {
        $commandHandler = $this->getCommandHandler($command);
        $this->container->call([$commandHandler, "__invoke"], [$command]);
    }

    /**
     * @param mixed $command
     * @return bool|mixed
     * @throws BindingResolutionException
     */
    private function getCommandHandler($command)
    {
        if (!$this->hasCommandHandler($command)) {
            $this->registerCommandHandler($command);
        }

        return $this->container->make($this->handlers[get_class($command)]);
    }


    /**
     * Determine if the given command has a handler.
     *
     * @param  mixed  $command
     * @return bool
     */
    public function hasCommandHandler($command)
    {
        return array_key_exists(get_class($command), $this->handlers);
    }

    /**
     * Register a command handler
     * @param $command
     */
    private function registerCommandHandler($command)
    {
        $handler = $this->handlerNamespace
            ."\\".class_basename(get_class($command))."Handler";

        if (!class_exists($handler)) {
            throw  new \InvalidArgumentException($handler. "Not found!");
        }

        $this->handlers[get_class($command)] = $handler;
    }
}
