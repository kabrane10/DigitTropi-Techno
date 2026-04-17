<?php

namespace App\Http\Controllers\Controleur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('controleur.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::guard('controleur')->attempt($credentials)) {
            $request->session()->regenerate();
            
            $controleur = Auth::guard('controleur')->user();
            $controleur->update(['last_login' => now()]);
            
            return redirect()->intended(route('controleur.dashboard'));
        }

        return back()->withErrors(['email' => 'Identifiants incorrects.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('controleur')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('controleur.login');
    }
}