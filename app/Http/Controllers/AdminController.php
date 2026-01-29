<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Caso;
use App\Models\Doenca;
use App\Models\LogSistema;
use App\Models\ConfigAlerta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf; // Importação necessária para o PDF

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_medicos' => User::where('role', 2)->count(),
            'total_casos' => Caso::count(),
            'total_doencas' => Doenca::count(),
            'alertas_ativos' => ConfigAlerta::where('alerta_ativo', true)->count(),
            'ultimos_logs' => LogSistema::with('user')->latest()->take(5)->get()
        ];

        return view('admin.dashboard', compact('stats'));
    }

    // --- GESTÃO DE UTILIZADORES ---
    public function usuarios()
    {
        $usuarios = User::withTrashed()->get();
        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function storeUsuario(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|integer',
            'provincia' => 'required|string'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'provincia' => $request->provincia,
        ]);

        return redirect()->route('admin.usuarios')->with('success', 'Profissional de saúde registado!');
    }

    public function eliminarUsuario($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === Auth::id()) return back()->withErrors(['msg' => 'Não pode eliminar a sua conta.']);
        
        $user->delete();
        return back()->with('success', 'Utilizador removido.');
    }

    // --- GESTÃO DE DOENÇAS ---
    public function doencasIndex()
    {
        $doencas = Doenca::all();
        return view('admin.doencas.index', compact('doencas'));
    }

    public function storeDoenca(Request $request)
    {
        $request->validate(['nome_doenca' => 'required|unique:doencas']);
        $doenca = Doenca::create($request->all());
        ConfigAlerta::create(['doenca_id' => $doenca->id]);
        return back()->with('success', 'Patologia configurada!');
    }

    public function updateDoenca(Request $request, $id)
    {
        $doenca = Doenca::findOrFail($id);
        $doenca->update($request->all());
        return back()->with('success', 'Patologia atualizada!');
    }

    public function destroyDoenca($id)
    {
        Doenca::findOrFail($id)->delete();
        return back()->with('success', 'Doença removida.');
    }

    // --- ALERTAS ---
    public function alertasIndex()
    {
        $doencas = Doenca::all();
        foreach ($doencas as $doenca) {
            ConfigAlerta::firstOrCreate(['doenca_id' => $doenca->id]);
        }
        $configuracoes = ConfigAlerta::with('doenca')->get();
        return view('admin.alertas.index', compact('configuracoes'));
    }

    public function updateAlerta(Request $request, $id)
    {
        $config = ConfigAlerta::findOrFail($id);
        $config->update([
            'alerta_ativo' => $request->has('alerta_ativo'),
            'modo' => $request->modo,
            'threshold_surto' => $request->threshold_surto ?? 5,
        ]);
        return back()->with('success', 'Alerta atualizado!');
    }

    // --- RELATÓRIOS (CORRIGIDO) ---
    public function gerarRelatorio(Request $request)
    {
        $formato = $request->query('format');
        $casos = Caso::with('doenca')->get();

        // Dados formatados e anonimizados
        $dados = $casos->map(function($caso) {
            return [
                'paciente' => Str::mask($caso->paciente_nome, '*', 2, -2),
                'bi' => Str::mask($caso->paciente_bi, '*', 4, -2),
                'doenca' => $caso->doenca->nome_doenca ?? 'N/A',
                'provincia' => $caso->provincia,
                'municipio' => $caso->municipio ?? 'N/A',
                'data' => $caso->created_at->format('d/m/Y'),
            ];
        });

        // Estatísticas para os gráficos
        $stats = [
            'por_doenca' => $casos->groupBy(fn($item) => $item->doenca->nome_doenca ?? 'N/A')->map->count(),
            'por_provincia' => $casos->groupBy('provincia')->map->count(),
            'total_geral' => $casos->count()
        ];

        // --- LÓGICA CSV ---
        if ($formato === 'csv') {
            $fileName = 'relatorio_geosaude_' . date('Ymd_His') . '.csv';
            $headers = [
                "Content-type" => "text/csv; charset=UTF-8",
                "Content-Disposition" => "attachment; filename=$fileName",
            ];

            $callback = function() use($dados) {
                $file = fopen('php://output', 'w');
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM para Excel
                fputcsv($file, ['Paciente', 'BI', 'Doença', 'Província', 'Município', 'Data'], ';');
                foreach ($dados as $row) {
                    fputcsv($file, [$row['paciente'], $row['bi'], $row['doenca'], $row['provincia'], $row['municipio'], $row['data']], ';');
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }

        // --- LÓGICA PDF (BAIXAR AUTOMATICAMENTE) ---
        // Se quiser baixar direto, usamos a Facade PDF
        if ($formato === 'pdf') {
            $pdf = Pdf::loadView('admin.relatorios.pdf', compact('dados', 'stats'))
                      ->setPaper('a4', 'portrait');
            
            return $pdf->download('relatorio_epidemiologico_' . date('d-m-Y') . '.pdf');
        }

        // Caso aceda via URL sem formato definido, apenas mostra a view
        return view('admin.relatorios.pdf', compact('dados', 'stats'));
    }

    public function logs()
    {
        $logs = LogSistema::with('user')->latest()->paginate(15);
        return view('admin.logs.index', compact('logs'));
    }
}