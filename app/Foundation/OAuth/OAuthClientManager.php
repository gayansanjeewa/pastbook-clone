<?php


namespace App\Foundation\OAuth;

use App\Foundation\OAuth\Clients\AbstractOAuthClient;
use App\Foundation\OAuth\Clients\Facebook;
use App\Foundation\OAuth\Clients\Manager;
use App\Foundation\OAuth\Contracts\Factory as OAuthContract;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * @author Gayan Sanjeewa <iamgayan@gmail.com>
 */
class OAuthClientManager extends Manager implements OAuthContract
{
    /**
     * @var array
     */
    private $providers = [];

    /**
     * @var array
     */
    private $config;

    /**
     * @inheritDoc
     */
    public function provider(string $name = null, string $token = null)
    {
        $this->setConfig($name, $token);
        return $this->providers[$name] = $this->get($name);
    }

    /**
     * @param $name
     * @return AbstractOAuthClient
     */
    private function get($name)
    {
        return $this->providers[$name] ?? $this->resolve($name);
    }

    /**
     * @param $name
     * @return AbstractOAuthClient
     * @throws InvalidArgumentException
     */
    private function resolve($name)
    {
        $config = $this->getConfig();

        if (is_null($config)) {
            throw new InvalidArgumentException("OAuth Provider [{$name}] is not defined.");
        }

        $method = 'create' . Str::studly($name) . 'Client';

        if (method_exists($this, $method)) {
            return $this->{$method}($config);
        } else {
            throw new InvalidArgumentException("OAuth Provider [{$name}] is not supported.");
        }
    }

    /**
     * @param string $name
     * @return array
     */
    private function getAppConfig(string $name): array
    {
        return $this->container['config']["services.{$name}"];
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     *
     * @param array $config
     *
     * @return AbstractOAuthClient
     */
    protected function createFacebookClient(array $config): AbstractOAuthClient
    {
        return $this->buildProvider(
            Facebook::class, $config
        );
    }

    /**
     * @param string|null $name
     * @param string|null $token
     */
    private function setConfig(?string $name, ?string $token)
    {
        $config = $this->getAppConfig($name);
        $config['default_access_token'] = $token;
        $this->config = $config;
    }

}
