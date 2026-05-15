<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackPageView
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only track GET requests to the frontend (not admin/install)
        if ($request->isMethod('GET') && !$request->is('admin*') && !$request->is('install*') && !$request->is('_*')) {
            try {
                PageView::create([
                    'page'       => $request->path() === '' ? '/' : '/' . $request->path(),
                    'ip_hash'    => hash('sha256', $request->ip() . config('app.key')),
                    'user_agent' => substr($request->userAgent() ?? '', 0, 500),
                    'referrer'   => substr($request->headers->get('referer', '') ?? '', 0, 500),
                    'session_id' => hash('sha256', $request->session()->getId()),
                    'created_at' => now(),
                ]);
            } catch (\Exception $e) {
                // Silently fail — tracking should never break the site
            }
        }

        return $response;
    }
}
