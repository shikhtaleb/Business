<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetAdminLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $lang = session('admin_lang', 'en');

        if (in_array($lang, ['ar', 'en', 'nl', 'de'])) {
            app()->setLocale($lang);
        }

        return $next($request);
    }
}
