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
            'professional' => \App\Http\Middleware\EnsureProfessional::class,
            // 'client' middleware left in place for future use but not aliased currently
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
