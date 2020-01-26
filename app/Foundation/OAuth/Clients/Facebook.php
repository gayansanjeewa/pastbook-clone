<?php


namespace App\Foundation\OAuth\Clients;

use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook as FacebookSDK;
use Facebook\FacebookResponse;

/**
 * @author Gayan Sanjeewa <iamgayan@gmail.com>
 */
class Facebook extends AbstractOAuthClient
{
    /**
     * @var FacebookSDK
     */
    private $client;

    /**
     * @param array $configs
     * @throws FacebookSDKException
     */
    public function __construct(array $configs)
    {
        $this->client = new FacebookSDK([
            'app_id' => $configs['client_id'],
            'app_secret' => $configs['client_secret'],
            'graph_api_version' => $configs['graph_api_version'],
            'default_access_token' => $configs['default_access_token'],
        ]);
    }

    /**
     * @inheritDoc
     */
    public function get($endpoint, $accessToken = null, $eTag = null, $graphVersion = null): FacebookResponse
    {
        return $this->client->get($endpoint, $accessToken = null, $eTag = null, $graphVersion = null);
    }
}
