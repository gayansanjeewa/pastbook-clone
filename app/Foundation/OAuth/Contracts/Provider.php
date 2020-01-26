<?php


namespace App\Foundation\OAuth\Contracts;

use Facebook\Authentication\AccessToken;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\FacebookResponse;

/**
 * @author Gayan Sanjeewa <iamgayan@gmail.com>
 */
interface Provider
{
    /**
     * Sends a GET request to Graph and returns the result.
     *
     * @param string $endpoint
     * @param AccessToken|string|null $accessToken
     * @param string|null $eTag
     * @param string|null $graphVersion
     *
     * @return FacebookResponse
     *
     * @throws FacebookSDKException
     */
    public function get($endpoint, $accessToken = null, $eTag = null, $graphVersion = null): FacebookResponse;
}
