<?php

use Carbon\Carbon;
use Arkade\Demandware\Client;
use Arkade\Demandware\Authentication;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    public function testRequest()
    {
        $auth = new Authentication(
            HandlerStack::create(new MockHandler([
                new Response(
                    200, [], json_encode(
                        [
                            'access_token' => 'aRandomToken',
                            'scope' => 'mail',
                            'token_type' => 'Bearer',
                            'expires_in'  => 3600,
                        ]
                    )
                ),
            ]))
        );

        $auth->setAuthUrl('https://account.demandware.com/dw/oauth2/access_token');
        $auth->setClientId('arkade');
        $auth->setClientSecret('secret');

        // Set up our request(s) history
        $container = [];
        $history = Middleware::history($container);

        // Set up our mock handler for the auth response(s)
        $mock = new MockHandler(
            [new Response(200, [], json_encode([]))]
        );

        // Set up our Guzzle Handler Stack History (for requests)
        $stack = HandlerStack::create($mock);
        $stack->push($history);

        // Let's make the client with our mock responses and history container
        $client = new Client($auth, $stack);

        // Let's test!
        $client->request('GET', 'some-endpoint',
            ['query' => ['foo' => 'bar']]);

        // Check the correct outgoing call was made
        $this->assertEquals('GET', $container[0]['request']->getMethod());
        $this->assertEquals('some-endpoint?foo=bar',
            $container[0]['request']->getRequestTarget());

    }
}