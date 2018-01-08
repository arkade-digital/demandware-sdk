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
     * @param Demandware\Source $source
     */
    public function __construct(Demandware\Client $client)
    {
        $this->client = $client;
    }


    public function getSiteName()
    {
        return env('DW_SITE_NAME');
    }


}
