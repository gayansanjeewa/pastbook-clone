<?php


namespace App\Foundation\OAuth\Client;

use Facebook\Exceptions\FacebookSDKException;

/**
 * @author Gayan Sanjeewa <iamgayan@gmail.com>
 */
class Facebook
{

    /**
     * @var \Facebook\Facebook
     */
    private $client;

    /**
     * @param array $credentials
     */
    public function __construct(array $credentials)
    {
        try {
            $this->client = new \Facebook\Facebook([
                'app_id' => $credentials['client_id'],
                'app_secret' => $credentials['client_secret'],
                'graph_api_version' => $credentials['graph_api_version'],
            ]);
        } catch (FacebookSDKException $e) {
        }
    }
}
