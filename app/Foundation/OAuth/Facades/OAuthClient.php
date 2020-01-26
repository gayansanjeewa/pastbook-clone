<?php


namespace App\Foundation\OAuth\Facades;

use App\Foundation\OAuth\Contracts\Factory;
use Illuminate\Support\Facades\Facade;

/**
 * @author Gayan Sanjeewa <iamgayan@gmail.com>
 * @method static \App\Foundation\OAuth\Contracts\Provider provider(string $name = null, string $token = null)
 * @see OAuthClientManager
 */
class OAuthClient extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Factory::class;
    }
}
