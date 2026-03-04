<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('home');
});

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
