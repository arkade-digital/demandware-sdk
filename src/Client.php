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
     * Endpoint.
     *
     * @var string
     */
    protected $endpoint;

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
     * Set the endpoint.
     *
     * @param string $url
     * @return $this
     */
    public function setEndpoint($url)
    {
        $this->endpoint = $url;

        return $this;
    }

    /**
     * Get the endpoint.
     *
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
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

        if (!array_has($params, 'headers.Authorization')) {
            $oauthMiddleware   = Middleware::mapRequest(function ($request) {
                /** @var GuzzleHttp\Psr7\Request $request */
                return $request->withHeader('Authorization', 'Bearer ' . $this->auth->getToken());
            });
            $params['handler'] = $oauthMiddleware($clientHandler);
        }

        try {
            $response = $this->client->request(
                $method,
                $this->getEndpoint() . $endpoint,
                $params
            );
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            throw new Exceptions\UnexpectedException((string)$e->getResponse()->getBody(),
                $e->getResponse()->getStatusCode());
        }

        return json_decode((string)$response->getBody());
    }

    /**
     * Make a request to return customer JWT Authorization bearer.
     *
     * @internal Needs an endpoint as the site name might be different use the useShop function on the
     * AbstractModule class.
     *
     * @return string
     * @throws Exceptions\UnexpectedException
     */
    public function getCustomerAuth($endpoint)
    {
        $params['headers'] = [
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . $this->auth->getToken()
        ];

        $params['body'] = json_encode([
            'type' => 'guest'
        ]);

        try {
            $response = $this->client->request(
                'POST',
                $this->getEndpoint() . $endpoint,
                $params
            );
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            throw new Exceptions\UnexpectedException((string)$e->getResponse()->getBody(),
                $e->getMessage());
        }
        return $response->getHeader('Authorization') ?: '';
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
