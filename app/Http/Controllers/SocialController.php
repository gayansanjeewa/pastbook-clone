<?php

namespace App\Http\Controllers;

use App\User;
use DateTime;
use Domain\Command\GrantSocialAuthCommand;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Facebook\GraphNodes\GraphNode;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @author Gayan Sanjeewa <iamgayan@gmail.com>
 */
class SocialController extends Controller
{
    /**
     * @param string $provider
     * @return RedirectResponse
     * @throws \InvalidArgumentException
     */
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * @param string $provider
     * @return RedirectResponse
     * @throws BindingResolutionException
     * @throws FacebookSDKException
     */
    public function callback($provider)
    {
        /** @var SocialiteUser $socialiteUser */
        $socialiteUser = Socialite::driver($provider)->user();

        $this->command->dispatch(new GrantSocialAuthCommand($provider, [
            'name' => $socialiteUser->name,
            'email' => $socialiteUser->email,
            'provider_id' => $socialiteUser->id
        ]));

        $since = DateTime::createFromFormat('d-m-Y', '25-01-2020')->getTimestamp();
        $until = DateTime::createFromFormat('d-m-Y', '26-01-2020')->getTimestamp();


        $credentials = app()['config']['services.facebook'];
        $fb = new Facebook([
            'app_id' => $credentials['client_id'],
            'app_secret' => $credentials['client_secret'],
            'graph_api_version' => $credentials['graph_api_version'],
            'default_access_token' => $socialiteUser->token,
        ]);

        $query = "me/albums?fields=photos{picture},description,count,updated_time&since={$since}&until={$until}";
        try {
            $response = $fb->get($query);
        } catch (FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        $graphEdge = $response->getGraphEdge();

        $pics = $graphEdge->map(function (GraphNode $nodes) {
            $photoNodes = [];
            if (!empty($nodes['photos'])) {
                $photoNodes = $nodes['photos']->map(function ($photos) {
                    return $photos;
                });
            }

            return $photoNodes;
        });


        $filteredArray = array_filter($pics->asArray());

        $flat = [];
        foreach ($filteredArray as $item) {
            $flat = $item;
        }

        dump($flat);
        dump(count($flat));
        die();
        return redirect()->to('/home');
    }
}
