<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin.auth'           => \App\Http\Middleware\AdminAuthenticated::class,
            'check.installed'      => \App\Http\Middleware\CheckInstalled::class,
            'check.installed.done' => \App\Http\Middleware\CheckInstalledDone::class,
            'track.pageview'       => \App\Http\Middleware\TrackPageView::class,
            'maintenance.mode'     => \App\Http\Middleware\MaintenanceMode::class,
            'admin.locale'         => \App\Http\Middleware\SetAdminLocale::class,
            'handle.redirects'     => \App\Http\Middleware\HandleRedirects::class,
        ]);

        // Run redirect checks on all web requests (before maintenance/pageview)
        $middleware->web(append: [
            \App\Http\Middleware\HandleRedirects::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
