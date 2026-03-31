@extends('layouts.auth')

@section('title', 'Recuperar Senha')

@section('content')

<h2>Esqueceu a senha? 🔑</h2>
<p class="subtitle">Informe seu e-mail e enviaremos um link de redefinição.</p>

@if (session('status'))
    <div class="alert alert-success mb-3">{{ session('status') }}</div>
@endif

<form action="{{ route('password.email') }}" method="POST">
    @csrf

    <div class="mb-4">
        <label class="form-label">E-mail cadastrado</label>
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

    <button type="submit" class="btn btn-auth">Enviar link de recuperação</button>
</form>

<hr class="divider">

<div class="text-center" style="font-size:0.875rem; color:#6c757d">
    Lembrou a senha?
    <a href="{{ route('login') }}" class="auth-link">Voltar ao login</a>
</div>

@endsection
