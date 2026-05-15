<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
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

        ActivityLog::record(
            "Contact form: {$request->input('name')} <{$request->input('email')}> — " .
            substr($request->input('message'), 0, 100),
            'contact',
            [
                'name'    => $request->input('name'),
                'email'   => $request->input('email'),
                'message' => $request->input('message'),
                'ip'      => $request->ip(),
            ]
        );

        if ($request->expectsJson()) {
            return response()->json(['ok' => true, 'message' => __('Message sent successfully.')]);
        }

        $lang = $request->cookie('site_lang', Setting::get('default_locale', 'ar'));

        return redirect()->route('home', ['lang' => $lang])
            ->with('contact_success', true)
            ->withFragment('contact');
    }
}
