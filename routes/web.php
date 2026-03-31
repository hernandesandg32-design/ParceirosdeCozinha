<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReceitaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// ─── AUTENTICAÇÃO ────────────────────────────────────────────────────────────

Route::middleware('guest')->group(function () {
    Route::get('/login',  [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    Route::get('/esqueci-minha-senha',   [ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('/esqueci-minha-senha',  [ForgotPasswordController::class, 'store'])->name('password.email');
    Route::get('/redefinir-senha/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('/redefinir-senha',      [ResetPasswordController::class, 'store'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// ─── VERIFICAÇÃO DE EMAIL ────────────────────────────────────────────────────

Route::middleware('auth')->group(function () {
    Route::get('/verificar-email', [VerificationController::class, 'notice'])
        ->name('verification.notice');

    Route::get('/verificar-email/{id}/{hash}', [VerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('/verificar-email/reenviar', [VerificationController::class, 'send'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

// ─── USUÁRIOS ────────────────────────────────────────────────────────────────

Route::get('/cadastre-se',  [UserController::class, 'create'])->name('users.create');
Route::post('/cadastre-se', [UserController::class, 'store'])->name('users.store');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/usuarios',              [UserController::class, 'index'])->name('users.index');
    Route::get('/perfil',                [UserController::class, 'show'])->name('users.show');
    Route::get('/perfil/editar/{user}',  [UserController::class, 'edit'])->name('users.edit');
    Route::post('/perfil/editar/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/usuarios/{user}',    [UserController::class, 'destroy'])->name('users.destroy');
});

// ─── RECEITAS ────────────────────────────────────────────────────────────────

Route::get('/receitas', [ReceitaController::class, 'index'])->name('receitas.index');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/receitas/create',       [ReceitaController::class, 'create'])->name('receitas.create');
    Route::post('/receitas',             [ReceitaController::class, 'store'])->name('receitas.store');
    Route::get('/receitas/{receita}/edit', [ReceitaController::class, 'edit'])->name('receitas.edit');
    Route::put('/receitas/{receita}',    [ReceitaController::class, 'update'])->name('receitas.update');
    Route::delete('/receitas/{receita}', [ReceitaController::class, 'destroy'])->name('receitas.destroy');
});