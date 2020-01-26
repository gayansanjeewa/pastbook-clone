<?php


namespace App\Foundation\OAuth\Clients;

use Illuminate\Contracts\Container\Container;

/**
 * @author Gayan Sanjeewa <iamgayan@gmail.com>
 */
class Manager
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * Create a new manager instance.
     *
     * @param  \Illuminate\Contracts\Container\Container  $container
     * @return void
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
    /**
     * @param string $class
     * @param array $config
     *
     * @return AbstractOAuthClient
     */
    protected function buildProvider(string $class, array $config): AbstractOAuthClient
    {
        return new $class($config);
    }
}
