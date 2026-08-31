<?php

namespace App\Http\Controllers;

use App\Mail\ContactAutoReply;
use App\Mail\ContactReceived;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        // --- Spam defences (all silent: a bot gets the same "success" a human
        // does, so it never learns it was blocked). ---

        // 1. Honeypot: a hidden field real users never see. Bots fill it.
        if (filled($request->input('website'))) {
            return $this->pretendSuccess();
        }

        // 2. Time-trap: the form carries an encrypted render timestamp. Humans
        // take several seconds to fill it; bots fire almost instantly. Reject
        // anything submitted in under 3 seconds, or with a missing/tampered token.
        try {
            $renderedAt = (int) decrypt($request->input('ts'));
        } catch (\Throwable $e) {
            $renderedAt = 0;
        }
        if ($renderedAt === 0 || (now()->timestamp - $renderedAt) < 3) {
            return $this->pretendSuccess();
        }

        // 3. Link filter: genuine cleaning enquiries don't contain URLs; spam
        // almost always does.
        if (preg_match('~https?://|www\.~i', (string) $request->input('message'))) {
            return $this->pretendSuccess();
        }

        // 4. Rate limit: at most 3 submissions per 10 minutes from one IP.
        $key = 'contact:'.$request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            return $this->pretendSuccess();
        }
        RateLimiter::hit($key, 600);

        // 5. Cloudflare Turnstile: an invisible CAPTCHA. Skipped automatically
        // when no keys are configured, so nothing breaks before they're set.
        if (! $this->passesTurnstile($request)) {
            return $this->pretendSuccess();
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
        ]);

        $contactMessage = ContactMessage::create($data);

        try {
            Mail::to(config('mail.agency_address'))->send(new ContactReceived($contactMessage));
            Mail::to($contactMessage->email)->send(new ContactAutoReply($contactMessage));
        } catch (\Throwable $e) {
            Log::error('Contact mail failed: '.$e->getMessage());
        }

        return $this->pretendSuccess();
    }

    /**
     * Verify the Cloudflare Turnstile token. Returns true when the secret key
     * isn't configured (feature off) or when Cloudflare confirms the token.
     */
    private function passesTurnstile(Request $request): bool
    {
        $secret = config('services.turnstile.secret_key');

        if (blank($secret)) {
            return true; // Turnstile not set up yet — don't block anyone.
        }

        try {
            $response = Http::asForm()
                ->timeout(5)
                ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                    'secret' => $secret,
                    'response' => $request->input('cf-turnstile-response'),
                    'remoteip' => $request->ip(),
                ]);

            return $response->successful() && $response->json('success') === true;
        } catch (\Throwable $e) {
            // If Cloudflare is unreachable, don't lose a genuine enquiry.
            Log::warning('Turnstile verification failed: '.$e->getMessage());

            return true;
        }
    }

    /**
     * Return the normal success response — used both for genuine sends and for
     * silently-dropped spam, so bots can't tell the difference.
     */
    private function pretendSuccess()
    {
        return back()
            ->with('contact_status', __('site.contact.success'))
            ->withFragment('contact');
    }
}
