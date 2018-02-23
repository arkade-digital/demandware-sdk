<?php

use Carbon\Carbon;
use Arkade\Demandware\Authentication;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use PHPUnit\Framework\TestCase;

class AuthenticationTest extends TestCase
{

    public function testApiVersionSetterGetter()
    {
        $auth = new Authentication;
        $auth->setApiVersion('17_8');
        $this->assertEquals('17_8', $auth->getApiVersion());
    }

    public function testSiteNameSetterGetter()
    {
        $auth = new Authentication;
        $auth->setSiteName('SiteGenesis');
        $this->assertEquals('SiteGenesis', $auth->getSiteName());
    }

    public function testAuthUrlSetterGetter()
    {
        $auth = new Authentication;
        $auth->setAuthUrl('https://account.demandware.com/dw/oauth2/access_token');
        $this->assertEquals('https://account.demandware.com/dw/oauth2/access_token', $auth->getAuthUrl());
    }

    public function testClientIdGetterSetter()
    {
        $auth = new Authentication;
        $auth->setClientId('arkade');
        $this->assertEquals('arkade', $auth->getClientId());
    }

    public function testClientSecretGetterSetter()
    {
        $auth = new Authentication;
        $auth->setClientSecret('secret');
        $this->assertEquals('secret', $auth->getClientSecret());
    }

    public function testTokenExpiryGetterSetter()
    {
        $auth = new Authentication;
        $auth->setTokenExpiry(Carbon::createFromTimestamp('3600'));
        $this->assertEquals(Carbon::createFromTimestamp('3600'),
            $auth->getTokenExpiry());
    }

    public function testTokenIsExpired()
    {
        $auth = new Authentication;

        $auth->setTokenExpiry(Carbon::now()->addHour());
        $this->assertEquals(false, $auth->isTokenExpired());

        $auth->setTokenExpiry(Carbon::now()->subHour());
        $this->assertEquals(true, $auth->isTokenExpired());
    }


    public function testTokenSetter()
    {
        $auth = new Authentication;
        $auth->setToken('mytoken');
        $auth->setTokenExpiry(Carbon::now()->addHour());
        $this->assertEquals('mytoken', $auth->getToken());
    }

    public function testTokenGetter()
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
        $this->assertEquals('aRandomToken', $auth->getToken());
        $this->assertEquals(Carbon::now()->addSeconds(3600)->toDateTimeString(), $auth->getTokenExpiry()->toDateTimeString());
    }


    public function testCreateToken(){
        // Set up our request(s) history
        $container = [];
        $history = Middleware::history($container);

        // Set up our mock handler for the auth response(s)
        $mock = new MockHandler(
            [
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
            ]
        );

        // Set up our Guzzle Handler Stack History (for requests)
        $stack = HandlerStack::create($mock);
        $stack->push($history);

        // Let's make the client with our mock responses and history container
        $auth = new Authentication($stack);
        $auth->setAuthUrl('https://account.demandware.com/dw/oauth2/access_token');
        $auth->setClientId('arkade');
        $auth->setClientSecret('secret');

        // Let's call create token
        $auth->getToken();

        // Check that only one call was made to get q new token
        $this->assertCount(1, $container);

        // Check that it was a POST request
        $this->assertEquals('POST', $container[0]['request']->getMethod());

        // Ensure that it went to the Auth URL specified
        $this->assertEquals('/dw/oauth2/access_token',
            $container[0]['request']->getRequestTarget());

        // Lastly, make sure the body has the correct form params
        $this->assertEquals(
            "grant_type=client_credentials",
            (string)$container[0]['request']->getBody()
        );

        $this->assertEquals('aRandomToken', $auth->getToken());
    }

    public function testRefreshToken(){
        // Set up our request(s) history
        $container = [];
        $history = Middleware::history($container);

        // Set up our mock handler for the auth response(s)
        $mock = new MockHandler(
            [
                new Response(
                    200, [], json_encode(
                        [
                            'access_token' => 'aRandomToken',
                            'scope' => 'mail',
                            'token_type' => 'Bearer',
                            'expires_in'  => 3600,
                        ]
                    )
                )
            ]
        );

        // Set up our Guzzle Handler Stack History (for requests)
        $stack = HandlerStack::create($mock);
        $stack->push($history);

        // Let's make the client with our mock responses and history container
        $auth = new Authentication($stack);
        $auth->setAuthUrl('https://account.demandware.com/dw/oauth2/access_token');
        $auth->setClientId('arkade');
        $auth->setClientSecret('secret');

        // Let's call create token
        $auth->refreshPasswordCredentialsToken();

        // Check that only one call was made to get a new token
        $this->assertCount(1, $container);

        // Check that it was a POST request
        $this->assertEquals('POST', $container[0]['request']->getMethod());

        // Ensure that it went to the Auth URL specified in the setClientConfig
        $this->assertEquals('/dw/oauth2/access_token',
            $container[0]['request']->getRequestTarget());

        // Lastly, make sure the body has the correct form params
        $this->assertEquals(
            "auth_type=token",
            (string)$container[0]['request']->getBody()
        );

        $this->assertEquals('aRandomToken', $auth->getToken());

    }

}