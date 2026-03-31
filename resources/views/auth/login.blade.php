@extends('layouts.auth')

@section('title', 'Entrar')

@section('content')

<h2>Bem-vindo de volta! 👋</h2>
<p class="subtitle">Entre com sua conta para continuar.</p>

@if (session('status'))
    <div class="alert alert-success mb-3">{{ session('status') }}</div>
@endif

<form action="{{ route('login') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label class="form-label">E-mail</label>
        <input
            type="email"
            name="email"
            value="{{ old('email') }}"
            class="form-control @error('email') is-invalid @enderror"
            placeholder="seu@email.com"
            autofocus
        >
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <label class="form-label mb-0">Senha</label>
            <a href="{{ route('password.request') }}" class="auth-link" style="font-size:0.8rem">
                Esqueci minha senha
            </a>
        </div>
        <input
            type="password"
            name="password"
            class="form-control @error('password') is-invalid @enderror"
            placeholder="••••••••"
        >
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" name="remember" id="remember">
        <label class="form-check-label" for="remember" style="font-size:0.875rem">
            Lembrar de mim
        </label>
    </div>

    <button type="submit" class="btn btn-auth">Entrar</button>
</form>

<hr class="divider">

<div class="text-center" style="font-size:0.875rem; color:#6c757d">
    Não tem conta?
    <a href="{{ route('users.create') }}" class="auth-link">Cadastre-se grátis</a>
</div>

@endsection
