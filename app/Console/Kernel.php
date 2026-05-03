<?php

namespace App\Console;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's middleware aliases.
     *
     * Aliases may be used to conveniently assign middleware to routes and groups.
     *
     * @var array<string, class-string|string>
     */
    protected $middlewareAliases = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
        'signed' => \App\Http\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        
        // Middleware personnalisés
        'auth.admin' => \App\Http\Middleware\AuthenticateAdmin::class,
        'auth.animateur' => \App\Http\Middleware\AuthenticateAnimateur::class,
        'auth.agent' => \App\Http\Middleware\AuthenticateAgent::class,
        'auth.controleur' => \App\Http\Middleware\AuthenticateControleur::class,
 
    ];

    protected function schedule(Schedule $schedule):void
{
    // Générer les notifications toutes les heures
    $schedule->call(function () {
        App\Http\Controllers\Admin\NotificationController::genererNotifications();
    })->hourly();

    // Sauvegarde quotidienne à 02h00 (désactivée pour debug)
    // $schedule->command('backup:run')->dailyAt('02:00');

    // Alternative: toutes les 5 minutes (pour debug)
    $schedule->command('backup:run')->everyFiveMinutes();
    
    // Nettoyer les vieilles sauvegardes (garder 5 jours)
    $schedule->command('backup:clean')->dailyAt('03:00');
    
    // Sauvegarde manuelle via artisan
    // php artisan backup:run

    // Vérifier les stocks critiques toutes les heures
    $schedule->command('stock:check-alertes')->hourly();
}

protected $routeMiddleware = [
    'auth.animateur' => \App\Http\Middleware\AuthenticateAnimateur::class,
    'auth.agent' => \App\Http\Middleware\AuthenticateAgent::class,
    'auth.controleur' => \App\Http\Middleware\AuthenticateControleur::class,
];

}