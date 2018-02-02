<?php

namespace Arkade\Demandware;

use Psr\Http;
use GuzzleHttp;
use GuzzleHttp\Middleware;
use Arkade\Demandware;

class Client
{
    /**
     * @var GuzzleHttp\Client
     */
    protected $client;

    /**
     * @var Demandware\Authentication
     */
    protected $auth;

    /**
     * @var Demandware\Source
     */
    protected $source;

    /**
     * Client constructor.
     *
     * @param Authentication $auth
     * @param GuzzleHttp\HandlerStack|null $handler
     */
    public function __construct(Demandware\Authentication $auth, GuzzleHttp\HandlerStack $handler = null)
    {
        $this->auth = $auth;

        $this->client = new GuzzleHttp\Client([
            'handler' => $handler ? $handler : GuzzleHttp\HandlerStack::create(),
        ]);
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
        $clientHandler = $this->client->getConfig('handler');
        $oauthMiddleware = Middleware::mapRequest(function ($request) {

            return $request->withHeader('Authorization','Bearer ' . $this->auth->getToken());
        });
        $params['handler'] = $oauthMiddleware($clientHandler);


        try {
            $response = $this->client->request(
                $method,
                $this->auth->getEndpoint() . $endpoint,
                $params
            );
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            throw new Exceptions\UnexpectedException((string) $e->getResponse()->getBody(), $e->getResponse()->getStatusCode());
        }

        return json_decode((string) $response->getBody());
    }

    /**
     * Perform a PATCH request.
     *
     * @param $endpoint
     * @param array $params
     * @return Http\Message\ResponseInterface
     * @throws Exceptions\UnexpectedException
     */
    public function patch($endpoint, array $params = [])
    {
        return $this->request('PATCH', $endpoint, $params);
    }

    /**
     * Perform a POST request.
     *
     * @param $endpoint
     * @param array $params
     * @return Http\Message\ResponseInterface
     * @throws Exceptions\UnexpectedException
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
     * @throws Exceptions\UnexpectedException
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
     * @throws Exceptions\UnexpectedException
     */
    public function delete($endpoint, array $params = [])
    {
        return $this->request('DELETE', $endpoint, $params);
    }

    /**
     * Profile extension module.
     *
     * @return Modules\Customers
     */
    public function customers()
    {
        return new Modules\Customers($this);
    }


}
