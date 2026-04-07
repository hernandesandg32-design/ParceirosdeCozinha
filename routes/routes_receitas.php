<?php

// ─── RECEITAS ────────────────────────────────────────────────────────────────

use App\Http\Controllers\ReceitaController;
use App\Http\Controllers\IngredienteController;
use App\Http\Controllers\PassoController;

// Listagem pública
Route::get('/receitas', [ReceitaController::class, 'index'])->name('receitas.index');

Route::middleware(['auth', 'verified'])->group(function () {

    // Etapa 1 – Informações básicas
    Route::get('/receitas/criar',         [ReceitaController::class, 'create'])->name('receitas.create');
    Route::post('/receitas',              [ReceitaController::class, 'store'])->name('receitas.store');

    // Etapa 2 – Ingredientes e Passos
    Route::get('/receitas/{receita}/ingredientes', [ReceitaController::class, 'editIngredientes'])
        ->name('receitas.ingredientes.edit');

    // Ingredientes (CRUD parcial)
    Route::post('/receitas/{receita}/ingredientes',                        [IngredienteController::class, 'store'])->name('ingredientes.store');
    Route::delete('/receitas/{receita}/ingredientes/{ingrediente}',        [IngredienteController::class, 'destroy'])->name('ingredientes.destroy');

    // Passos (CRUD parcial)
    Route::post('/receitas/{receita}/passos',              [PassoController::class, 'store'])->name('passos.store');
    Route::delete('/receitas/{receita}/passos/{passo}',    [PassoController::class, 'destroy'])->name('passos.destroy');

    // Publicar
    Route::patch('/receitas/{receita}/publicar', [ReceitaController::class, 'publicar'])->name('receitas.publicar');

    // Editar informações básicas
    Route::get('/receitas/{receita}/editar',  [ReceitaController::class, 'edit'])->name('receitas.edit');
    Route::put('/receitas/{receita}',         [ReceitaController::class, 'update'])->name('receitas.update');

    // Deletar
    Route::delete('/receitas/{receita}', [ReceitaController::class, 'destroy'])->name('receitas.destroy');
});
