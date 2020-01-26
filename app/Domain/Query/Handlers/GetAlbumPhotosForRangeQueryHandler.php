<?php


namespace Domain\Query\Handlers;

use Domain\Query\GetAlbumPhotosForRangeQuery;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Facebook\GraphNodes\GraphEdge;
use Facebook\GraphNodes\GraphNode;

/**
 * @author Gayan Sanjeewa <iamgayan@gmail.com>
 */
final class GetAlbumPhotosForRangeQueryHandler
{
    /**
     * @param GetAlbumPhotosForRangeQuery $query
     * @return array
     * @throws FacebookSDKException
     * @throws FacebookResponseException
     * @throws FacebookSDKException
     */
    public function __invoke($query)
    {
        $credentials = app()['config']['services.facebook'];
        $client = new Facebook([
            'app_id' => $credentials['client_id'],
            'app_secret' => $credentials['client_secret'],
            'graph_api_version' => $credentials['graph_api_version'],
            'default_access_token' => $query->getAccessToken(),
        ]);

        // Note
        // I'm pretty sure this is not the optimum query
        // I wanted to get something like me/photos... but didn't work even in some places in doc suggest it
        // Therefore due to the time frame I went with this query and manipulate by code... not the perfect solution I know
        $query = "me/albums?fields=photos{picture,images},description,count,updated_time&since={$query->getSince()}&until={$query->getUntil()}";

        $response = $client->get($query);

        $albumGraphEdge = $response->getGraphEdge();

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
