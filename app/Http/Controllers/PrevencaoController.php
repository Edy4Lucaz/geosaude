<?php


namespace App\Http\Controllers;

use App\Models\Doenca; // Certifique-se que o Model Doenca existe
use Illuminate\Http\Request;

class PrevencaoController extends Controller
{
    public function index()
    {
        // Puxa as doenças e as informações de prevenção cadastradas
        $doencas = Doenca::all();
        return view('prevencao.index', compact('doencas'));
    }
}