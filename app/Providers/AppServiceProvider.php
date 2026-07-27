<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // In production the app always sits behind Coolify's Traefik proxy,
        // which terminates TLS and forwards plain HTTP to the container. Force
        // every generated URL (assets, links, redirects, form actions) to https
        // so nothing is emitted as http:// and blocked as mixed content.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
