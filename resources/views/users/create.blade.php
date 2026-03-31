@extends('layouts.auth')

@section('title', 'Criar Conta')

@section('content')

<h2>Crie sua conta 🍳</h2>
<p class="subtitle">Junte-se à comunidade de cozinheiros!</p>

<form action="{{ route('users.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label class="form-label">Nome completo</label>
        <input
            type="text"
            name="name"
            value="{{ old('name') }}"
            class="form-control @error('name') is-invalid @enderror"
            placeholder="Seu nome"
            autofocus
        >
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">E-mail</label>
        <input
            type="email"
            name="email"
            value="{{ old('email') }}"
            class="form-control @error('email') is-invalid @enderror"
            placeholder="seu@email.com"
        >
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Senha</label>
        <input
            type="password"
            name="password"
            class="form-control @error('password') is-invalid @enderror"
            placeholder="Mínimo 8 caracteres"
        >
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4">
        <label class="form-label">Confirmar senha</label>
        <input
            type="password"
            name="password_confirmation"
            class="form-control"
            placeholder="Repita a senha"
        >
    </div>

    <button type="submit" class="btn btn-auth">Criar conta</button>
</form>

<hr class="divider">

<div class="text-center" style="font-size:0.875rem; color:#6c757d">
    Já tem conta?
    <a href="{{ route('login') }}" class="auth-link">Entrar</a>
</div>

@endsection
