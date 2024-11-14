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
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->alias([

            'admin' => \App\Http\Middleware\Admin::class,
            'prevent-back-history' => \App\Http\Middleware\PreventBackHistory::class,
            'redirect-if-logged-out' => \App\Http\Middleware\RedirectIfLoggedOut::class,
            'check-session' => \App\Http\Middleware\CheckSession::class,

        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
