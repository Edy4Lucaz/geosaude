<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Caso;
use App\Models\Doenca;
use App\Models\LogSistema;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class CasoController extends Controller
{
    // REMOVA o public function __construct() daqui

    public function create()
    {
        $doencas = Doenca::all();
        return view('casos.create', compact('doencas'));
    }

    public function store(Request $request)
    {
        $userIdLogado = Auth::id();
        // ... resto do seu código igual ao anterior

        $localidade = "{$request->bairro}, {$request->municipio}, {$request->provincia}, Angola";

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'GeoSaudeAngola/1.0'
            ])->get("https://nominatim.openstreetmap.org/search", [
                        'q' => $localidade,
                        'format' => 'json',
                        'limit' => 1
                    ]);

            $dadosMapa = $response->json();

            if (empty($dadosMapa)) {
                return redirect()->back()->withErrors(['msg' => 'Localização não encontrada. Tente usar nomes de bairros ou municípios mais conhecidos.']);
            }

            $lat = (float) $dadosMapa[0]['lat'];
            $lng = (float) $dadosMapa[0]['lon'];

        } catch (\Exception $e) {
            LogSistema::create([
                'user_id' => $userIdLogado,
                'acao' => 'Erro API Mapas',
                'detalhes' => $e->getMessage(),
                'tipo' => 'ERRO'
            ]);
            return redirect()->back()->withErrors(['msg' => 'Erro na conexão com o serviço de mapas.']);
        }

        $dadosQr = $request->paciente_bi . $request->paciente_nome . date('YmdHis');
        $qrCodeHash = hash('sha256', $dadosQr);

        // 2. Criar o registo com os dados dinâmicos
        $caso = Caso::create([
            'paciente_nome' => $request->paciente_nome,
            'paciente_bi' => $request->paciente_bi,
            'paciente_nascimento' => $request->paciente_nascimento,
            'provincia' => $request->provincia,
            'municipio' => $request->municipio,
            'bairro' => $request->bairro,
            'latitude' => $lat,
            'longitude' => $lng,
            'qr_code' => $qrCodeHash,
            'doenca_id' => $request->doenca_id,
            'user_id' => $userIdLogado, // DINÂMICO
        ]);

        // 3. Log de Auditoria Real
        LogSistema::create([
            'user_id' => $userIdLogado,
            'acao' => 'Registo de Caso',
            'detalhes' => "Caso de " . $caso->doenca->nome_doenca . " em {$request->bairro} registado por " . Auth::user()->name,
            'tipo' => 'INFO'
        ]);

        return redirect()->back()->with('success', "Caso registado com sucesso em {$request->bairro}!");
    }
}