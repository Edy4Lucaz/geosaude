<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credenciais = $request->only('email', 'password');

        if (Auth::attempt($credenciais)) {
            // Regenerar a sessão por segurança
            $request->session()->regenerate();

            // O intended('/') tenta mandar de volta para onde ele estava 
            // ou para a home se ele veio direto do botão login
            return redirect()->intended('/');
        }

        return redirect()->back()->withErrors(['msg' => 'Credenciais inválidas.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}