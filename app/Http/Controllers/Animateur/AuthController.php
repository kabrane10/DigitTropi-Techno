<?php

namespace App\Http\Controllers\Animateur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin() { return view('animateur.auth.login'); }
    public function login(Request $request)
    {
        $credentials = $request->validate(['email' => 'required|email', 'password' => 'required']);
        if (Auth::guard('animateur')->attempt($credentials)) {
            $request->session()->regenerate();
            Auth::guard('animateur')->user()->update(['last_login' => now()]);
            return redirect()->intended(route('animateur.dashboard'));
        }
        return back()->withErrors(['email' => 'Identifiants incorrects.']);
    }
    public function logout(Request $request)
    {
        Auth::guard('animateur')->logout();
        $request->session()->invalidate();
        return redirect()->route('animateur.login');
    }
}