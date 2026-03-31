@extends('layouts.auth')

@section('title', 'Nova Senha')

@section('content')

<h2>Redefinir senha 🔐</h2>
<p class="subtitle">Escolha uma nova senha segura para sua conta.</p>

<form action="{{ route('password.update') }}" method="POST">
    @csrf

    {{-- token oculto enviado pelo e-mail --}}
    <input type="hidden" name="token" value="{{ $token }}">

    <div class="mb-3">
        <label class="form-label">E-mail</label>
        <input
            type="email"
            name="email"
            value="{{ old('email', request('email')) }}"
            class="form-control @error('email') is-invalid @enderror"
            placeholder="seu@email.com"
        >
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Nova senha</label>
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
        <label class="form-label">Confirmar nova senha</label>
        <input
            type="password"
            name="password_confirmation"
            class="form-control"
            placeholder="Repita a nova senha"
        >
    </div>

    <button type="submit" class="btn btn-auth">Salvar nova senha</button>
</form>

@endsection
