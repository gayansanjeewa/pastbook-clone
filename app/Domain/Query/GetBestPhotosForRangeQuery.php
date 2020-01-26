<?php


namespace Domain\Query;

/**
 * @author Gayan Sanjeewa <iamgayan@gmail.com>
 */
class GetBestPhotosForRangeQuery
{
    /**
     * @var string
     */
    private $since;

    /**
     * @var string
     */
    private $until;

    /**
     * @var string
     */
    private $accessToken;

    /**
     * @param string $since
     * @param string $until
     * @param string $accessToken
     */
    public function __construct(string $since, string $until, string $accessToken)
    {
        $this->since = $since;
        $this->until = $until;
        $this->accessToken = $accessToken;
    }

    /**
     * @return string
     */
    public function getSince(): string
    {
        return $this->since;
    }

    /**
     * @return string
     */
    public function getUntil(): string
    {
        return $this->until;
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }
}
