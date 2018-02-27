<?php

namespace Arkade\Demandware;

use GuzzleHttp;
use Arkade\Demandware;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class LaravelServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {

        // Setup the auth.
        $this->app->singleton(Demandware\Authentication::class, function(){
            return (new Demandware\Authentication)
                ->setApiVersion(config('services.demandware.version'))
                ->setAuthUrl(config('services.demandware.auth_url'))
                ->setSiteName(config('services.demandware.site_name'))
                ->setClientId(config('services.demandware.client_id'))
                ->setClientSecret(config('services.demandware.client_secret'));
        });

        // Setup the client.
        $this->app->singleton(Demandware\Client::class, function () {
            $client = (new Demandware\Client(resolve(Demandware\Authentication::class)))
                ->setEndpoint(config('services.demandware.endpoint'))
                ->setLogging(config('services.commerceconnect.logging'))
                ->setVerifyPeer(config('app.env') === 'production')
                ->setLogger(Log::getMonolog());

            $this->setupRecorder($client);

            return $client;
        });
    }

    /**
     * Setup recorder middleware if the HttpRecorder package is bound.
     *
     * @param  Client $client
     * @return Client
     */
    protected function setupRecorder(Client $client)
    {
        if (! $this->app->bound('Omneo\Plugins\HttpRecorder\Recorder')) {
            return $client->setupClient();
        }

        $stack = GuzzleHttp\HandlerStack::create();

        $stack->push(
            $this->app
                ->make('Omneo\Plugins\HttpRecorder\GuzzleIntegration')
                ->getMiddleware(['demandware', 'outgoing'])
        );

        return $client->setupClient($stack);
    }
}
