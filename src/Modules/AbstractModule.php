<?php

namespace Arkade\Demandware\Modules;

use Arkade\Demandware;

abstract class AbstractModule
{
    /**
     * @var Demandware\Client
     */
    protected $client;

    /**
     * Abstract Module constructor.
     *
     * @param Demandware\Client|null $client
     */
    public function __construct(Demandware\Client $client)
    {
        $this->client = $client;
    }

    public function getSiteName()
    {
        return env('DW_SITE_NAME');
    }

    public function getApiVersion()
    {
        return env('DW_API_VERSION');
    }

    public function getClientId()
    {
        return env('DW_CLIENT_ID');
    }

    /**
     * Returns API URI for data endpoint
     *
     * @param string $endpoint
     * @return string
     */
    public function useData($endpoint)
    {
        return sprintf('/s/-/dw/data/v%s/%s', $this->getApiVersion(), ltrim($endpoint, '/'));
    }

    /**
     * Returns API URI for shop endpoint
     *
     * @param string $endpoint
     * @return string
     */
    public function useShop($endpoint)
    {
        return sprintf('/s/%s/dw/shop/v%s/%s', $this->getSiteName(), $this->getApiVersion(), ltrim($endpoint, '/'));
    }

}
