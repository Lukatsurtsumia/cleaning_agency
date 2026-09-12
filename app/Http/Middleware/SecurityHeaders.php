<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Send the browser-side hardening headers on every response.
 *
 * These live in the app rather than in Cloudflare so they follow the code:
 * every domain, every environment, and still there if the proxy in front
 * ever changes.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Don't touch streamed file downloads - their headers are already
        // set by the responder.
        if ($response instanceof \Symfony\Component\HttpFoundation\BinaryFileResponse) {
            return $response;
        }

        // Never let the browser guess a content type. Stops an uploaded avatar
        // that lies about its type from being run as script.
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // No one embeds this site in an iframe but us. Blocks clickjacking on
        // the forms and booking screens.
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Send the full URL to ourselves, only the origin to anyone else, and
        // nothing at all when leaving HTTPS.
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // We ask for none of these, so don't let an embedded script ask either.
        $response->headers->set(
            'Permissions-Policy',
            'camera=(), microphone=(), geolocation=(), payment=(), usb=(), interest-cohort=()'
        );

        // Pin the browser to HTTPS for a year once it has seen us once.
        // Only sent over a real TLS request so local http://localhost still works.
        if ($request->isSecure()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains'
            );
        }

        // PHP adds this one itself; it just advertises the version to scanners.
        $response->headers->remove('X-Powered-By');

        return $response;
    }
}
