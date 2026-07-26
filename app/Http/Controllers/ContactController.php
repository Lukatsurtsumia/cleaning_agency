<?php

namespace App\Http\Controllers;

use App\Mail\ContactAutoReply;
use App\Mail\ContactReceived;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        // Honeypot: a hidden field real users never see. Bots fill it, so a
        // filled value means we silently drop the request as spam.
        if (filled($request->input('website'))) {
            return back()->with('contact_status', __('site.contact.success'));
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
        ]);

        $contactMessage = ContactMessage::create($data);

        Mail::to(config('mail.agency_address'))->send(new ContactReceived($contactMessage));
        Mail::to($contactMessage->email)->send(new ContactAutoReply($contactMessage));

        return back()
            ->with('contact_status', __('site.contact.success'))
            ->withFragment('contact');
    }
}
