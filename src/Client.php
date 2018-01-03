<?php

namespace Arkade\Responsys;

use Psr\Http;
use GuzzleHttp;
use Arkade\Responsys;

class Client
{
    /**
     * @var GuzzleHttp\Client
     */
    protected $client;

    /**
     * @var Responsys\Authentication
     */
    protected $auth;

    /**
     * @var Responsys\Source
     */
    protected $source;

    /**
     * Client constructor.
     *
     * @param Authentication $auth
     * @param GuzzleHttp\HandlerStack|null $handler
     */
    public function __construct(Responsys\Authentication $auth, GuzzleHttp\HandlerStack $handler = null)
    {
        $this->auth = $auth;

        $this->client = new GuzzleHttp\Client([
            'handler' => $handler ? $handler : GuzzleHttp\HandlerStack::create(),
        ]);
    }

    /**
     * Set the source.
     *
     * @param Responsys\Source $source
     * @return $this
     */
    public function setSource(Responsys\Source $source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Make a request.
     *
     * @param $method
     * @param $endpoint
     * @param array $params
     * @return Http\Message\ResponseInterface
     * @throws Exceptions\UnexpectedException
     */
    public function request($method, $endpoint, array $params = [])
    {
        $headers = [
            'headers' => [
                'Authorization' => $this->auth->getToken(),
            ],
        ];

        try {
            $response = $this->client->request(
                $method,
                $this->auth->getEndpoint() . $endpoint,
                array_merge($headers, $params)
            );
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            throw new Exceptions\UnexpectedException((string) $e->getResponse()->getBody(), $e->getResponse()->getStatusCode());
        }

        return json_decode((string) $response->getBody());
    }

    /**
     * Perform a POST request.
     *
     * @param $endpoint
     * @param array $params
     * @return Http\Message\ResponseInterface
     */
    public function post($endpoint, array $params = [])
    {
        return $this->request('POST', $endpoint, $params);
    }

    /**
     * Perform a GET request.
     *
     * @param $endpoint
     * @param array $params
     * @return Http\Message\ResponseInterface
     */
    public function get($endpoint, array $params = [])
    {
        return $this->request('GET', $endpoint, $params);
    }

    /**
     * Perform a DELETE request.
     *
     * @param $endpoint
     * @param array $params
     * @return Http\Message\ResponseInterface
     */
    public function delete($endpoint, array $params = [])
    {
        return $this->request('DELETE', $endpoint, $params);
    }

    /**
     * Profile extension module.
     *
     * @return Modules\ProfileExtension
     */
    public function profileExtension()
    {
        return new Modules\ProfileExtension($this, $this->source);
    }

    /**
     * Profile list module.
     *
     * @return Modules\ProfileList
     */
    public function profileList()
    {
        return new Modules\ProfileList($this, $this->source);
    }

    /**
     * Supplemental table module.
     *
     * @return Modules\SupplementalTable
     */
    public function supplementalTable()
    {
        return new Modules\SupplementalTable($this, $this->source);
    }

    /**
     * Trigger module.
     *
     * @return Modules\Trigger
     */
    public function trigger()
    {
        return new Modules\Trigger($this, $this->source);
    }
}
