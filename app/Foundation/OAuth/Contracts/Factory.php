<?php


namespace App\Foundation\OAuth\Contracts;

use App\Foundation\OAuth\Clients\AbstractOAuthClient;

/**
 * @author Gayan Sanjeewa <iamgayan@gmail.com>
 */
interface Factory
{
    /**
     * @param string|null $name
     * @param string|null $token
     * @return AbstractOAuthClient
     */
    public function provider(string $name = null, string $token = null);
}
