<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\UrlGenerator;
use Laravel\Passport\Passport;
use Laravel\Passport\Client;
use Ramsey\Uuid\Uuid;

Client::creating(function (Client $client) {
    $client->incrementing = false;
    $client->id = \Ramsey\Uuid\Uuid::uuid4()->toString();
});

Client::retrieved(function (Client $client) {
    $client->incrementing = false;
});

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (env('REDIRECT_HTTPS')) {
            $this->app['request']->server->set('HTTPS', true);
        }
        Passport::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        if (env('REDIRECT_HTTPS')) {
            $url->formatScheme('https://');
        }
    }
}
