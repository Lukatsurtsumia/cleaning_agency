<?php

use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Behind Coolify's Traefik proxy: TLS is terminated at the proxy and
        // plain HTTP is forwarded to the container. Trust the proxy so Laravel
        // reads X-Forwarded-Proto and generates https:// URLs (assets, links,
        // redirects) instead of http:// — otherwise CSS/JS is blocked as
        // mixed content on the https site.
        $middleware->trustProxies(at: '*');

        // Browser hardening headers (HSTS, nosniff, anti-clickjacking) on
        // every response, including /up.
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);

        $middleware->web(append: [
            SetLocale::class,
        ]);

        // Signed-in pages are for the agency's own accounts only.
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
