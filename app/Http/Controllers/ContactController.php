<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Setting;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:150',
            'email'   => 'required|email|max:255',
            'message' => 'required|string|max:3000',
        ]);

        $lang = $request->cookie('site_lang', Setting::get('default_locale', 'ar'));

        Message::create([
            'name'    => $request->input('name'),
            'email'   => $request->input('email'),
            'subject' => null,
            'body'    => $request->input('message'),
            'status'  => 'unread',
            'ip'      => $request->ip(),
            'lang'    => $lang,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['ok' => true, 'message' => __('Message sent successfully.')]);
        }

        return redirect()->route('home', ['lang' => $lang])
            ->with('contact_success', true)
            ->withFragment('contact');
    }
}
