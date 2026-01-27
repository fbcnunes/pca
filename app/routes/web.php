<?php

use App\Http\Controllers\Admin\CicloController;
use App\Http\Controllers\Admin\PerfilPermissaoController;
use App\Http\Controllers\Admin\UnidadeController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\DemandasController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ValidacaoController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::prefix('admin')->middleware('auditoria')->group(function () {
        Route::middleware('permissao:unidades.gerenciar')->group(function () {
            Route::get('/unidades', [UnidadeController::class, 'index'])->name('admin.unidades.index');
            Route::post('/unidades', [UnidadeController::class, 'store'])->name('admin.unidades.store');
            Route::get('/unidades/{unidade}/edit', [UnidadeController::class, 'edit'])->name('admin.unidades.edit');
            Route::put('/unidades/{unidade}', [UnidadeController::class, 'update'])->name('admin.unidades.update');
            Route::patch('/unidades/{unidade}/toggle', [UnidadeController::class, 'toggle'])->name('admin.unidades.toggle');
            Route::delete('/unidades/{unidade}', [UnidadeController::class, 'destroy'])->name('admin.unidades.destroy');
        });

        Route::middleware('permissao:ciclos.gerenciar')->group(function () {
            Route::get('/ciclos', [CicloController::class, 'index'])->name('admin.ciclos.index');
            Route::post('/ciclos', [CicloController::class, 'store'])->name('admin.ciclos.store');
            Route::get('/ciclos/{ciclo}/edit', [CicloController::class, 'edit'])->name('admin.ciclos.edit');
            Route::put('/ciclos/{ciclo}', [CicloController::class, 'update'])->name('admin.ciclos.update');
            Route::patch('/ciclos/{ciclo}/ativar', [CicloController::class, 'ativar'])->name('admin.ciclos.ativar');
        });

        Route::middleware('permissao:usuarios.gerenciar')->group(function () {
            Route::get('/perfis-permissoes', [PerfilPermissaoController::class, 'index'])->name('admin.perfis.index');
            Route::post('/perfis-permissoes/{perfil}/permissoes', [PerfilPermissaoController::class, 'sync'])->name('admin.perfis.sync');
            Route::get('/usuarios', [UsuarioController::class, 'index'])->name('admin.usuarios.index');
            Route::get('/usuarios/criar', [UsuarioController::class, 'create'])->name('admin.usuarios.create');
            Route::post('/usuarios', [UsuarioController::class, 'store'])->name('admin.usuarios.store');
            Route::get('/usuarios/{user}/editar', [UsuarioController::class, 'edit'])->name('admin.usuarios.edit');
            Route::put('/usuarios/{user}', [UsuarioController::class, 'update'])->name('admin.usuarios.update');
            Route::delete('/usuarios/{user}', [UsuarioController::class, 'destroy'])->name('admin.usuarios.destroy');
        });

        Route::middleware('permissao:catalogos.gerenciar')->group(function () {
            Route::get('/catalogos', [\App\Http\Controllers\Admin\CatalogoController::class, 'index'])->name('admin.catalogos.index');
            Route::post('/catalogos', [\App\Http\Controllers\Admin\CatalogoController::class, 'store'])->name('admin.catalogos.store');
            Route::patch('/catalogos/{tipo}/{id}/toggle', [\App\Http\Controllers\Admin\CatalogoController::class, 'toggle'])->name('admin.catalogos.toggle');
        });
    });

    Route::prefix('demandas')->middleware(['auditoria'])->group(function () {
        Route::get('/', [DemandasController::class, 'index'])->name('demandas.index');
        Route::get('/criar', [DemandasController::class, 'create'])->name('demandas.create');
        Route::post('/', [DemandasController::class, 'store'])->name('demandas.store');
        Route::get('/{demanda}', [DemandasController::class, 'show'])->name('demandas.show');
        Route::get('/{demanda}/editar', [DemandasController::class, 'edit'])->name('demandas.edit');
        Route::put('/{demanda}', [DemandasController::class, 'update'])->name('demandas.update');
        Route::patch('/{demanda}/enviar', [DemandasController::class, 'enviar'])->name('demandas.enviar');
    });

    Route::prefix('validacao')->middleware(['auditoria'])->group(function () {
        Route::get('/', [ValidacaoController::class, 'index'])->name('validacao.index');
        Route::post('/{demanda}/decidir', [ValidacaoController::class, 'decidir'])->name('validacao.decidir');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
