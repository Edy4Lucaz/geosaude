<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController, CasoController, AdminController, NoticiaController, HomeController, PrevencaoController, FichaController};
/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/noticias', [NoticiaController::class, 'index'])->name('noticias.index');
Route::get('/radar-surtos', function () {
    return view('mapa.publico'); })->name('mapa.publico');

//Prevenção

Route::get('/prevencao', [PrevencaoController::class, 'index'])->name('prevencao.index');
Route::get('/prevencao/{id}', [PrevencaoController::class, 'show'])->name('prevencao.show');

// API para o Mapa
Route::get('/api/casos-geosaude', function () {
    return App\Models\Caso::with('doenca')->get();
})->name('api.casos.dados');

// Autenticação
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Rotas Protegidas (Exigem Autenticação)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // --- ROTAS PARA MÉDICOS (CASOS) ---
    // Esta é a parte que estava a causar o erro!
    Route::get('/casos/novo', [CasoController::class, 'create'])->name('casos.create');
    Route::post('/casos/salvar', [CasoController::class, 'store'])->name('casos.store');

    // --- ROTAS DE ADMINISTRAÇÃO ---
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
        Route::post('/usuarios/store', [AdminController::class, 'storeUsuario'])->name('admin.usuarios.store');
        Route::delete('/usuarios/{id}', [AdminController::class, 'eliminarUsuario'])->name('admin.usuarios.destroy');
        Route::get('/mapa', [AdminController::class, 'mapa'])->name('admin.mapa');
        Route::get('/logs', [AdminController::class, 'logs'])->name('admin.logs');
        Route::get('/doencas', [AdminController::class, 'doencasIndex'])->name('admin.doencas');
        Route::post('/doencas/store', [AdminController::class, 'storeDoenca'])->name('admin.doencas.store');
        Route::put('/doencas/{id}', [AdminController::class, 'updateDoenca'])->name('admin.doencas.update');
        Route::delete('/doencas/{id}', [AdminController::class, 'destroyDoenca'])->name('admin.doencas.destroy');
        Route::get('/alertas', [AdminController::class, 'alertasIndex'])->name('admin.alertas');
        Route::put('/alertas/{id}', [AdminController::class, 'updateAlerta'])->name('admin.alertas.update');
        Route::get('/relatorios', [AdminController::class, 'gerarRelatorio'])->name('admin.relatorios');
    });

    Route::get('/medico', function () {
        return view('medico.index');
    })->name('medico.index')->middleware('auth');

    //----- 
    Route::middleware(['auth'])->group(function () {
        Route::get('/ficha-tecnica', [FichaController::class, 'index'])->name('ficha.index');


    });

});