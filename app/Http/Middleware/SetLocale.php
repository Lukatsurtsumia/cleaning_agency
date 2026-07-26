<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Locales the site is actually translated into. French leads because the
     * business and its clients are in Nice.
     */
    public const SUPPORTED = ['fr', 'en'];

    public const DEFAULT = 'fr';

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->get('locale');

        // French is the landing language for everyone. Browser Accept-Language
        // is deliberately ignored: the clientele is local, and an English
        // browser in Nice should still see the French site until asked otherwise.
        if (! in_array($locale, self::SUPPORTED, true)) {
            $locale = self::DEFAULT;
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
