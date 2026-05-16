<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class HandleRedirects
{
    public function handle(Request $request, Closure $next): Response
    {
        // Only intercept GET requests (not POST, PUT, DELETE, etc.)
        if (!$request->isMethod('GET')) {
            return $next($request);
        }

        $path = '/' . ltrim($request->path(), '/');

        // Never redirect admin or install routes — protects user from accidental lockout
        if (str_starts_with($path, '/admin') || str_starts_with($path, '/install') ||
            in_array($path, ['/sitemap.xml', '/robots.txt'], true)) {
            return $next($request);
        }

        // Load all active redirects from cache (10-minute TTL)
        $redirects = Cache::remember('redirects_all', 600, function () {
            return Redirect::active()
                ->select(['id', 'from_path', 'to_path', 'status_code'])
                ->get()
                ->keyBy('from_path');
        });

        if ($redirects->has($path)) {
            $redirect = $redirects->get($path);

            // Increment hit counter asynchronously (avoid slowing the response)
            Redirect::where('id', $redirect->id)->increment('hits');

            return redirect($redirect->to_path, $redirect->status_code);
        }

        return $next($request);
    }
}
