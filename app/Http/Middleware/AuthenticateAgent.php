<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateAgent
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('agent')->check()) {
            return redirect()->route('agent.login');
        }
        
        return $next($request);
    }
}