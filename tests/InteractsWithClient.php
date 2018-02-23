<?php

namespace Arkade\Demandware;

use Arkade\Demandware\Authentication;
use Arkade\Demandware\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

trait InteractsWithClient
{
    /**
     * Create a history stack.
     *
     * @param $container
     * @return HandlerStack
     */
    protected function createHistoryMiddleware(&$container)
    {
        return Middleware::history($container);
    }

    /**
     * Create a Responsys mock client.
     *
     * @param array $responses
     * @param $history
     * @return Client
     */
    private function createClient(array $responses, &$history)
    {
        // Add the auth response.
        $responses = array_prepend($responses, new Response(200, [], json_encode([
            'access_token' => 'aRandomToken',
            'scope' => 'mail',
            'token_type' => 'Bearer',
            'expires_in'  => 3600,
        ])));

        $stack = HandlerStack::create(new MockHandler($responses));

        $stack->push($this->createHistoryMiddleware($history));

        $auth = new Authentication($stack);

        $auth->setAuthUrl('https://account.demandware.com/dw/oauth2/access_token');
        $auth->setClientId('arkade');
        $auth->setClientSecret('secret');

        $client = new Client($auth, $stack);

        return $client;
    }

    /**
     * Get the request body.
     *
     * @param Request $request
     * @return mixed
     */
    protected function getRequestBody(Request $request)
    {
        return json_decode((string) $request->getBody());
    }
}