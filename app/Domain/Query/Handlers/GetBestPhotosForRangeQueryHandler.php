<?php


namespace Domain\Query\Handlers;

use App\Foundation\OAuth\Facades\OAuthClient;
use App\Foundation\OAuth\ProviderType;
use Domain\Exceptions\AlbumPhotosNotFoundException;
use Domain\Query\GetBestPhotosForRangeQuery;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\GraphNodes\GraphEdge;
use Facebook\GraphNodes\GraphNode;

/**
 * @author Gayan Sanjeewa <iamgayan@gmail.com>
 */
final class GetBestPhotosForRangeQueryHandler
{
    /**
     * @param GetBestPhotosForRangeQuery $query
     * @return array
     * @throws FacebookSDKException
     * @throws AlbumPhotosNotFoundException
     */
    public function __invoke($query)
    {
        $client = OAuthClient::provider(ProviderType::FACEBOOK, $query->getAccessToken());

        // Note
        // I'm pretty sure this is not the optimum query
        // I wanted to get something like me/photos... but didn't work even in some places in doc suggest it
        // Therefore due to the time frame I went with this query and manipulate by code... not the perfect solution I know
        $query = "me/albums?fields=photos{picture,images},description,count,updated_time&since={$query->getSince()}&until={$query->getUntil()}";

        $response = $client->get($query);

        $albumGraphEdge = $response->getGraphEdge();

        if (empty($albumGraphEdge->count())) {
            throw new AlbumPhotosNotFoundException();
        }

        return $this->extractAlbumPhotos($albumGraphEdge);
    }

    /**
     * @param GraphEdge $albumGraphEdge
     * @return array
     */
    private function extractAlbumPhotos(GraphEdge $albumGraphEdge): array
    {
        $photoNodes = $albumGraphEdge->map(function (GraphNode $albumNode) {
            $photoNodes = [];
            if (!empty($albumNode['photos'])) {
                $photoNodes = $albumNode['photos']->map(function ($photos) {
                    return $photos;
                });
            }

            return $photoNodes;
        });

        $photos = [];
        foreach (array_filter($photoNodes->asArray()) as $photoNode) {
            $photos = $photoNode;
        }

        return array_slice($photos, 0, 8);
    }
}
