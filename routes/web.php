<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReceitaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', [HomeController::class, 'index'])->name('home');

//
// USUARIOS
//

Route::get('/usuarios', [UserController::class, 'index'])
    ->name('users.index');

Route::get('/cadastre-se', [UserController::class, 'create'])
    ->name('users.create');

Route::get('/perfil/editar/{user}', [UserController::class, 'edit'])
    ->name('users.edit');

Route::get('/perfil', [UserController::class, 'show'])
    ->name('users.show');

Route::post('/cadastre-se', [UserController::class, 'store'])
    ->name('users.store');

Route::post('/perfil/editar/{user}', [UserController::class, 'update'])
    ->name('users.update');

Route::delete('/usuarios/{user}', [UserController::class, 'destroy'])
    ->name('users.destroy');

    //
    // RECEITAS
    //

    Route::get('/receitas', [ReceitaController::class, 'index'])
    ->name('receitas.index');

Route::get('/receitas/create', [ReceitaController::class, 'create'])
    ->name('receitas.create');

Route::post('/receitas', [ReceitaController::class, 'store'])
    ->name('receitas.store');

Route::get('/receitas/{receita}/edit', [ReceitaController::class, 'edit'])
    ->name('receitas.edit');

Route::put('/receitas/{receita}', [ReceitaController::class, 'update'])
    ->name('receitas.update');

Route::delete('/receitas/{receita}', [ReceitaController::class, 'destroy'])
    ->name('receitas.destroy');
