<?php

namespace App\Http\Controllers;

use App\Models\ConfigAlerta;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Pega alertas ativos com as informações da doença
        $configuracoesAtivas = ConfigAlerta::with('doenca')
            ->where('alerta_ativo', true)
            ->get();

        return view('welcome', compact('configuracoesAtivas'));
    }
}