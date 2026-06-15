<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\SubscriptionMiddleware;
use App\Http\Middleware\IsAdminMiddleware;
use App\Http\Middleware\EnsureUserVerification;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            SetLocale::class,
        ]);

        $middleware->alias([
            'subscription.active' => SubscriptionMiddleware::class,
            'is.admin'            => IsAdminMiddleware::class,
            'verified.user'       => EnsureUserVerification::class,
        ]);
    })
    

    ->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\CheckRole::class,
    ]);
})

    ->withExceptions(function (Exceptions $exceptions) {
        
        //
    })->create();