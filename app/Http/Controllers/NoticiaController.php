<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; 

class NoticiaController extends Controller
{
    public function index()
    {
        try {
            $apiKey = env('NEWS_API_KEY');

            // 1. Busca abrangente (Angola, África e Saúde)
            $response = Http::get("https://newsapi.org/v2/everything", [
                'q' => '(Angola saúde) OR (África surto) OR (OMS saúde)',
                'language' => 'pt',
                'sortBy' => 'relevancy',
                'apiKey' => $apiKey
            ]);

            $todas = $response->json()['articles'] ?? [];

            // 2. Filtros de "Pureza"
            $termosObrigatorios = ['saúde', 'minsa', 'hospital', 'médico', 'doença', 'vírus', 'vacina', 'surto', 'oms', 'paciente', 'medicina', 'epidemia', 'malária'];
            $termosLixo = ['futebol', 'disney', 'música', 'concerto', 'eleições', 'partido', 'novela', 'cinema', 'sport', 'vencer', 'golos'];

            $noticiasFiltradas = array_filter($todas, function ($n) use ($termosObrigatorios, $termosLixo) {
                $texto = strtolower(($n['title'] ?? '') . ' ' . ($n['description'] ?? ''));

                // Regra 1: Se tiver lixo, elimina na hora
                foreach ($termosLixo as $lixo) {
                    if (str_contains($texto, $lixo))
                        return false;
                }

                // Regra 2: PRECISA ter pelo menos um termo de saúde real
                foreach ($termosObrigatorios as $saude) {
                    if (str_contains($texto, $saude))
                        return true;
                }

                return false;
            });

            // 3. Organizar por Localidade (Angola primeiro, depois África, depois o resto)
            usort($noticiasFiltradas, function ($a, $b) {
                $tA = strtolower($a['title'] . $a['description']);
                $tB = strtolower($b['title'] . $b['description']);

                $pesoA = str_contains($tA, 'angola') ? 2 : (str_contains($tA, 'áfrica') ? 1 : 0);
                $pesoB = str_contains($tB, 'angola') ? 2 : (str_contains($tB, 'áfrica') ? 1 : 0);

                return $pesoB <=> $pesoA;
            });

            // 4. Garante no máximo 8
            $noticias = array_slice($noticiasFiltradas, 0, 8);

        } catch (\Exception $e) {
            $noticias = [];
        }

        return view('noticias.index', compact('noticias'));
    }
}