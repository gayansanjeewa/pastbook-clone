<?php
/*
 * This file is part of the Adlogix package.
 *
 * (c) Allan Segebarth <allan@adlogix.eu>
 * (c) Jean-Jacques Courtens <jjc@adlogix.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\Unit\Domain\Query;

use App\Foundation\OAuth\Contracts\Provider;
use App\Foundation\OAuth\Facades\OAuthClient;
use App\Foundation\OAuth\ProviderType;
use Domain\Exceptions\AlbumPhotosNotFoundException;
use Domain\Query\GetBestPhotosForRangeQuery;
use Domain\Query\Handlers\GetBestPhotosForRangeQueryHandler;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\FacebookResponse;
use Facebook\GraphNodes\GraphEdge;
use Mockery;
use Tests\TestCase;

/**
 * @author Gayan Sanjeewa <gayan@adsdaq.eu>
 */
final class GetBestPhotosForRangeQueryHandlerTest extends TestCase
{

    /**
     * @return GetBestPhotosForRangeQueryHandler
     */
    public function createCommand(): GetBestPhotosForRangeQueryHandler
    {
        return new GetBestPhotosForRangeQueryHandler();
    }

    /**
     * @test
     *
     * @throws FacebookSDKException
     */
    public function invoke_withValidTokenButWithoutAnyPhotos_throwAlbumPhotosNotFoundException()
    {
        $this->expectException(AlbumPhotosNotFoundException::class);

        $query = new GetBestPhotosForRangeQuery(1579859797, 1580032597, $this->getToken());

        $querySting = "me/albums?fields=photos{picture,images},description,count,updated_time&since={$query->getSince()}&until={$query->getUntil()}";

        $graphEdge = Mockery::mock(GraphEdge::class);
        $graphEdge->shouldReceive('count')
            ->once()
            ->andReturn(0);

        $facebookResponse = Mockery::mock(FacebookResponse::class);
        $facebookResponse->shouldReceive('getGraphEdge')
            ->once()
            ->andReturn($graphEdge);

        $provider = Mockery::mock(Provider::class);
        $provider->shouldReceive('get')
            ->with($querySting)
            ->once()
            ->andReturn($facebookResponse);

        OAuthClient::shouldReceive('provider')
            ->with(ProviderType::FACEBOOK, $this->getToken())
            ->once()
            ->andReturn($provider);

        $this->createCommand()->__invoke($query);
    }

    /**
     * @test
     *
     * @throws FacebookSDKException
     */
    public function invoke_withValidToken_executeQuerySuccessfully()
    {
        $query = new GetBestPhotosForRangeQuery(1579859797, 1580032597, $this->getToken());

        $querySting = "me/albums?fields=photos{picture,images},description,count,updated_time&since={$query->getSince()}&until={$query->getUntil()}";

        $photoGraphEdge = Mockery::mock(GraphEdge::class);
        $photoGraphEdge
            ->shouldReceive('asArray')
            ->once()
            ->andReturn([]);

        $albumGraphEdge = Mockery::mock(GraphEdge::class);
        $albumGraphEdge
            ->shouldReceive('count')
            ->once()
            ->andReturn(10);

        $albumGraphEdge
            ->shouldReceive('map')
            ->once()
            ->andReturn($photoGraphEdge);

        $facebookResponse = Mockery::mock(FacebookResponse::class);
        $facebookResponse
            ->shouldReceive('getGraphEdge')
            ->once()
            ->andReturn($albumGraphEdge);

        $provider = Mockery::mock(Provider::class);
        $provider
            ->shouldReceive('get')
            ->with($querySting)
            ->once()
            ->andReturn($facebookResponse);

        OAuthClient::shouldReceive('provider')
            ->with(ProviderType::FACEBOOK, $this->getToken())
            ->once()
            ->andReturn($provider);

        $this->createCommand()->__invoke($query);
    }

    /**
     * @return string
     */
    protected function getToken(): string
    {
        return 'EAAgBFR4cWo4BALEFfxEVgHJx9jJlvgffaXVZBypmqZAiJPBhjk51fImD7yKHEqSAIXIvz0FFWIN06Y3NLlETkrWX8Tq86XiZASfeuJJwsxOmhUoK5ZAVuJJZCEyt7v6Do52B7lVULVGZBdV08OuXjcllwQcbxunOZCvdF76oH99ZCQZDZD';
    }
}
