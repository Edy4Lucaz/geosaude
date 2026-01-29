<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Caso;

class FichaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $paciente = null;
        $historico = collect();

        if ($search) {
            // Pesquisa pelo BI exato ou Nome parcial usando as suas colunas reais
            $paciente = Caso::where('paciente_bi', $search)
                ->orWhere('paciente_nome', 'LIKE', "%{$search}%")
                ->first();

            if ($paciente) {
                // Busca o histórico usando o paciente_bi como chave única
                $historico = Caso::where('paciente_bi', $paciente->paciente_bi)
                    ->with('doenca')
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        }

        return view('ficha.index', compact('paciente', 'historico'));
    }
}