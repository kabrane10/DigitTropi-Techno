<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if ($guard === 'admin' && Auth::guard($guard)->check()) {
                return redirect()->route('admin.dashboard');
            }
            if ($guard === 'animateur' && Auth::guard($guard)->check()) {
                return redirect()->route('animateur.dashboard');
            }
            if ($guard === 'agent' && Auth::guard($guard)->check()) {
                return redirect()->route('agent.dashboard');
            }
            if ($guard === 'controleur' && Auth::guard($guard)->check()) {
                return redirect()->route('controleur.dashboard');
            }
            if (Auth::guard($guard)->check()) {
                return redirect('/admin/dashboard');
            }
        }

        return $next($request);
    }
}