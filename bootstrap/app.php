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
        $middleware->redirectTo(function ($request) {
            if ($request->is('admin/*') || $request->is('admin')) {
                return route('admin.login');
            }
            if ($request->is('animateur/*') || $request->is('animateur')) {
                return route('animateur.login');
            }
            if ($request->is('agent/*') || $request->is('agent')) {
                return route('agent.login');
            }
            if ($request->is('controleur/*') || $request->is('controleur')) {
                return route('controleur.login');
            }
            return route('role.selection');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();