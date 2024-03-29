<?php

namespace Arkade\Demandware;

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
                ->setUsername(config('services.demandware.clientId'))
                ->setPassword(config('services.demandware.clientSecret'))
                ->setEndpoint(config('services.demandware.endpoint'));
        });

        // Setup the client.
        $this->app->singleton(Demandware\Client::class, function () {
            return (new Demandware\Client(resolve(Demandware\Authentication::class)));
        });
    }
}
