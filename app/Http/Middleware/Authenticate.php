<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            // Pour l'administration
            if ($request->is('admin/*') || $request->is('admin')) {
                return route('admin.login');
            }
            // Pour l'espace animateur
            if ($request->is('animateur/*') || $request->is('animateur')) {
                return route('animateur.login');
            }
            // Pour l'espace agent
            if ($request->is('agent/*') || $request->is('agent')) {
                return route('agent.login');
            }
            // Pour l'espace contrôleur
            if ($request->is('controleur/*') || $request->is('controleur')) {
                return route('controleur.login');
            }
            // Redirection par défaut vers admin login (au lieu de 'login')
            return route('admin.login');
        }
        return null;
    }
}