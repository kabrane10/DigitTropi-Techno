<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateAnimateur
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('animateur')->check()) {
            return redirect()->route('animateur.login');
        }
        
        return $next($request);
    }
}