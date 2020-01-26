<?php


namespace Domain\Command;

/**
 * @author Gayan Sanjeewa <iamgayan@gmail.com>
 */
final class AuthorizeSocialUserCommand
{
    /**
     * @var string
     */
    private $provider;

    /**
     * @var array
     */
    private $credentials;

    /**
     * @param string $provider
     * @param array $credentials
     */
    public function __construct(string $provider, array $credentials)
    {
        $this->provider = $provider;
        $this->credentials = $credentials;
    }

    /**
     * @return string
     */
    public function getProvider(): string
    {
        return $this->provider;
    }

    /**
     * @return array
     */
    public function getCredentials(): array
    {
        return $this->credentials;
    }
}
