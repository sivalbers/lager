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
        // Alias registrieren
        $middleware->alias([
            'berechtigung' => \App\Http\Middleware\CheckBerechtigung::class,
        ]);
        // Optional: zur Web-Middleware-Gruppe hinzufügen
        $middleware->web(append: [
            \App\Http\Middleware\CheckBerechtigung::class,
        ]);

    })

    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })

    ->
    create();
