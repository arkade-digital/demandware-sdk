<?php

namespace Arkade\Demandware\Modules;

use GuzzleHttp;
use Arkade\Demandware\Entities\Customer;
use Arkade\Demandware\Exceptions;
use Arkade\Demandware\Parsers;
use Illuminate\Support\Collection;

Class CustomerAuthentication Extends AbstractModule
{

    /**
     * @param Customer $customer
     * @return Array
     * @throws \Arkade\Demandware\Exceptions\UnexpectedException
     * @throws \Arkade\Demandware\Exceptions\TokenNotFoundException
     * @throws \Arkade\Demandware\Exceptions\CookiesNotFoundException
     */
    public function authenticate(Customer $customer)
    {
        $response = $this->loginCustomer(
            $this->useShop("/customers/auth?client_id={$this->getClientId()}"),
            $customer
        );

        $jwt = $response->getHeader('Authorization');

        if (empty($jwt)) {
            throw new Exceptions\TokenNotFoundException('Customer JWT token could not be found');
        }

        $cookies = $this->getSessionCookies(
            $this->useShop("/sessions?client_id={$this->getClientId()}"),
            $jwt
        );

        if (empty($cookies)) {
            throw new Exceptions\CookiesNotFoundException('Customer JWT token could not be found');
        }

        $customer = (new Parsers\CustomerParser)->parse(json_decode((string)$response->getBody()));

        $customer->attributes = Collect(['cookies'=>$cookies]);

        return $customer;
    }

    /**
     * Make a request to log the customer in using a customers username and password.
     *
     * @internal Needs an endpoint as the site name might be different use the useShop function on the
     * AbstractModule class.
     * @param Customer $customer
     *
     * @return GuzzleHttp\Psr7\Response
     * @throws Exceptions\UnexpectedException
     */
    protected function loginCustomer($endpoint, Customer $customer)
    {
        $basicAuthentication = base64_encode($customer->getCredentials()->getLogin() . ':' . $customer->getCredentials()->getPassword());

        $params['headers'] = [
            'Content-Type'  => 'application/json',
            'Authorization' => 'Basic ' . $basicAuthentication
        ];

        $params['body'] = json_encode([
            'type' => 'credentials'
        ]);

        try {
            $response = $this->client->getClient()->request(
                'POST',
                $this->client->getEndpoint() . $endpoint,
                $params
            );
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            throw new Exceptions\UnexpectedException((string)$e->getResponse()->getBody(),
                $e->getMessage());
        }

        return $response;
    }

    /**
     * Make a request to return customer session cookies.
     *
     * @internal Needs an endpoint as the site name might be different use the useShop function on the
     * AbstractModule class.
     * @param String $jwt JSON Web Token
     *
     * @return string
     * @throws Exceptions\UnexpectedException
     */
    protected function getSessionCookies($endpoint, Array $jwt)
    {
        $params['headers'] = [
            'Content-Type'  => 'application/json',
            'Authorization' => $jwt[0]
        ];

        try {
            $response = $this->client->getClient()->request(
                'POST',
                $this->client->getEndpoint() . $endpoint,
                $params
            );
        } catch (GuzzleHttp\Exception\BadResponseException $e) {
            throw new Exceptions\UnexpectedException((string)$e->getResponse()->getBody(),
                $e->getMessage());
        }
        return $response->getHeader('Set-Cookie') ?: '';
    }

}