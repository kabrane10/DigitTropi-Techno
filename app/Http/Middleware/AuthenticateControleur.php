<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateControleur
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('controleur')->check()) {
            return redirect()->route('controleur.login');
        }
        
        return $next($request);
    }
}