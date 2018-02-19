<?php

namespace Arkade\Demandware;

use GuzzleHttp;
use Arkade\Demandware;
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
                ->setAuthUrl(config('services.demandware.authUrl'))
                ->setSiteName(config('services.demandware.siteName'))
                ->setClientId(config('services.demandware.clientId'))
                ->setClientSecret(config('services.demandware.clientSecret'));
        });

        // Setup the client.
        $this->app->singleton(Demandware\Client::class, function () {
            $client = (new Demandware\Client(resolve(Demandware\Authentication::class)))
                ->setEndpoint(config('services.demandware.endpoint'));

            $this->setupRecorder($client);

            return $client->setupClient();
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
            return $client;
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
