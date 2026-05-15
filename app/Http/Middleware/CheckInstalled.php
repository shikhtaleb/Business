<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckInstalled
{
    public function handle(Request $request, Closure $next): Response
    {
        $installed = file_exists(storage_path('installed'));

        // If not installed and not already on the install route, redirect to install
        if (!$installed && !$request->is('install*')) {
            return redirect('/install');
        }

        // If installed and trying to access install route, redirect to home
        if ($installed && $request->is('install*')) {
            return redirect('/');
        }

        return $next($request);
    }
}
