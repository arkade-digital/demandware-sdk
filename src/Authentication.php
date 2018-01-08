<?php

namespace Arkade\Demandware;

use GuzzleHttp;
use Carbon\Carbon;

class Authentication
{
    /**
     * Guzzle client.
     *
     * @var GuzzleHttp\Client
     */
    protected $guzzle;

    /**
     * API version.
     *
     * @var string
     */
    protected $apiVersion;

    /**
     * Auth URL.
     *
     * @var string
     */
    protected $authUrl;

    /**
     * Username.
     *
     * @var string
     */
    protected $username;

    /**
     * Password.
     *
     * @var string
     */
    protected $password;

    /**
     * Endpoint.
     *
     * @var string
     */
    protected $endpoint;

    /**
     * Token.
     *
     * @var string
     */
    protected $token;

    /**
     * Token expiry.
     *
     * @var string
     */
    protected $tokenExpiry;

    /**
     * Authentication constructor.
     *
     * @param GuzzleHttp\HandlerStack $handler
     */
    public function __construct(GuzzleHttp\HandlerStack $handler = null)
    {
        $this->guzzle = new GuzzleHttp\Client([
            'handler' => $handler ? $handler : GuzzleHttp\HandlerStack::create(),
        ]);
    }

    /**
     * Get the API version.
     *
     * @return string
     */
    public function getApiVersion()
    {
        return $this->apiVersion;
    }

    /**
     * Set the API version.
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
     * Set the Site Name (Client List ID).
     *
     * @param string $apiVersion
     * @return $this
     */
    public function setSiteName($apiVersion)
    {
        $this->siteName = $apiVersion;

        return $this;
    }

    /**
     * Set the auth URL.
     *
     * @param string $authUrl
     * @return $this
     */
    public function setAuthUrl($authUrl)
    {
        $this->authUrl = $authUrl;

        return $this;
    }

    /**
     * Get the auth URL.
     *
     * @return string
     */
    public function getAuthUrl()
    {
        return $this->authUrl;
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

        return sprintf('%s/s/-/dw/data/v%s/', $this->endpoint, $this->getApiVersion());
    }

    /**
     * Get a token.
     *
     * @return string
     */
    public function getToken()
    {
        if ($this->token) return $this->isTokenExpired() ? $this->refreshPasswordCredentialsToken() : $this->token;

        return $this->createPasswordCredentialsToken();

    }

    /**
     * Set the token.
     *
     * @param $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Determine if a token has expired.
     *
     * @return bool
     */
    public function isTokenExpired()
    {
        return Carbon::now()->greaterThanOrEqualTo($this->getTokenExpiry()->subMinutes(5));
    }

    /**
     * Get the token expiry.
     *
     * @return mixed
     */
    public function getTokenExpiry()
    {
        return $this->tokenExpiry;
    }

    /**
     * Set token expiry.
     *
     * @param $tokenExpiry
     * @return $this
     */
    public function setTokenExpiry($tokenExpiry)
    {
        $this->tokenExpiry = $tokenExpiry;

        return $this;
    }

    /**
     * Refresh password credentials token.
     *
     * @return string
     */
    public function refreshPasswordCredentialsToken()
    {
        // Setup our auth parameters
        $params = [
            'headers' => [
                // Use directly to prevent infinite loops
                'Authorization' => $this->token,
            ],
            'form_params' => [
                'auth_type' => 'token',
            ],
        ];

        // Make the Request and process the response
        $response = $this->guzzle->request('POST', $this->getAuthUrl(), $params);
        $result = json_decode((string) $response->getBody());

        $this->setToken($result->access_token)
             ->setTokenExpiry(
                 Carbon::now()
                       ->addMinutes(120)
             );

        return $this->token;
    }

    /**
     * Create password credentials token.
     *
     * @return string
     */
    public function createPasswordCredentialsToken()
    {

        $authorization_token = base64_encode($this->getUsername() . ':' . $this->getPassword());

        $params = [
            'headers' => [ 'Authorization' => 'Basic '. $authorization_token],
            'form_params' => ['grant_type' => env('DW_GRANT_TYPE', 'client_credentials')]
        ];

        // Make the Request and process the response
        $response = $this->guzzle->request('POST', $this->getAuthUrl(), $params);
        $result = json_decode((string) $response->getBody());

        $this->setToken($result->access_token)
             ->setTokenExpiry(
                 Carbon::now()
                       ->addSeconds($result->expires_in)
             );

        return $this->token;
    }

    /**
     * Get the username.
     *
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the username.
     *
     * @param $username
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the password.
     *
     * @param $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }
}
