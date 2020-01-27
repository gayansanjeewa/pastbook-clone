<?php


namespace App\Foundation\OAuth\Facades;

use App\Foundation\OAuth\Contracts\Factory;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Foundation\OAuth\Contracts\Provider provider(string $name = null, string $token = null)
 *
 * @see OAuthClientManager
 *
 * @author Gayan Sanjeewa <iamgayan@gmail.com>
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
