<?php

namespace Arkade\Demandware;

use Psr\Http;
use GuzzleHttp;
use GuzzleHttp\Middleware;
use GuzzleHttp\MessageFormatter;
use Psr\Log\LoggerInterface;
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
     * Enable logging of guzzle requests / responses
     *
     * @var bool
     */
    protected $logging = false;

    /**
     * PSR-3 logger
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Verify peer SSL
     *
     * @var bool
     */
    protected $verifyPeer = true;

    /**
     * Set connection timeout
     *
     * @var int
     */
    protected $timeout = 900;

    /**
     * @var
     */
    protected $siteName;

    /**
     * @var
     */
    protected $apiVersion;

    /**
     * @var
     */
    protected $clientId;

    /**
     * @var
     */
    protected $customerCreationMethod;

    /**
     * Client constructor.
     *
     * @param Authentication $auth
     * @param GuzzleHttp\HandlerStack|null $handler
     */
    public function __construct(Demandware\Authentication $auth, GuzzleHttp\HandlerStack $handler = null)
    {
        $this->auth = $auth;

        $this->setupClient($handler);
    }

    /**
     * @return GuzzleHttp\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param GuzzleHttp\Client $client
     * @return Client
     */
    public function setClient($client)
    {
        $this->client = $client;
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
     * Get the sitename.
     *
     * @return string
     */
    public function getSitename()
    {
        return $this->siteName;
    }

    /**
     * Set the sitename.
     *
     * @param string $sitename
     * @return $this
     */
    public function setSitename($siteName)
    {
        $this->siteName = $siteName;

        return $this;
    }

    /**
     * Get the sitename.
     *
     * @return string
     */
    public function getApiVersion()
    {
        return $this->apiVersion;
    }

    /**
     * Set the apiVersion.
     *
     * @param string $apiVersion
     * @return $this
     */
    public function setApiVersion($apiVersion)
    {
        $this->apiVersion = $apiVersion;

        return $this;
    }

    /**
     * Get the sitename.
     *
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Set the apiVersion.
     *
     * @param string $apiVersion
     * @return $this
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * Set customer creation method, customers or customer_list
     * @param $method
     * @return $this
     */
    public function setCustomerCreationMethod($method) {
        $this->customerCreationMethod = $method;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomerCreationMethod() {
        return $this->customerCreationMethod;
    }

    /**
     * @return bool
     */
    public function getLogging()
    {
        return $this->logging;
    }

    /**
     * @param bool $logging
     * @return Client
     */
    public function setLogging($logging)
    {
        $this->logging = $logging;
        return $this;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     * @return Client
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * @return bool
     */
    public function getVerifyPeer()
    {
        return $this->verifyPeer;
    }

    /**
     * @param bool $verifyPeer
     * @return RestClient
     */
    public function setVerifyPeer($verifyPeer)
    {
        $this->verifyPeer = $verifyPeer;
        return $this;
    }

    /**
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @param int $timeout
     * @return RestClient
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * Setup Guzzle client with optional provided handler stack.
     *
     * @param  GuzzleHttp\HandlerStack|null $stack
     * @param  array                        $options
     * @return Client
     */
    public function setupClient(GuzzleHttp\HandlerStack $stack = null, $options = [])
    {
        $stack = $stack ?: GuzzleHttp\HandlerStack::create();

        if($this->logging) $this->bindLoggingMiddleware($stack);

        $this->client = new GuzzleHttp\Client(array_merge([
            'handler'  => $stack,
            'verify' => $this->getVerifyPeer(),
            'timeout'  => $this->getTimeout(),
        ], $options));

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

    /**
     * Authentication module.
     *
     * @return Modules\CustomerAuthentication
     */
    public function customerAuthentication()
    {
        return new Modules\CustomerAuthentication($this);
    }

    /**
     * Bind logging middleware.
     *
     * @param  GuzzleHttp\HandlerStack $stack
     * @return void
     */
    protected function bindLoggingMiddleware(GuzzleHttp\HandlerStack $stack)
    {
        $stack->push(Middleware::log(
            $this->logger,
            new MessageFormatter('{request} - {response}')
        ));
    }

}
