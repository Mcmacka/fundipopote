<?php

use App\Http\Middleware\IsAdminMiddleware;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\SubscriptionMiddleware;
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
        // Apply locale to every web request
        $middleware->web(append: [
            SetLocale::class,
        ]);

        // Custom aliases
        $middleware->alias([
            'subscription.active' => SubscriptionMiddleware::class,
            'is.admin'            => IsAdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
