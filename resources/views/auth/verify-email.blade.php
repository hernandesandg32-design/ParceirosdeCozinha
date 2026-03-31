@extends('layouts.auth')

@section('title', 'Verificar E-mail')

@section('content')

<div class="text-center mb-3" style="font-size: 3rem">📬</div>
<h2 class="text-center">Verifique seu e-mail</h2>
<p class="subtitle text-center">
    Enviamos um link de verificação para o seu endereço de e-mail.
    Clique no link para ativar sua conta.
</p>

@if (session('status') == 'verification-link-sent')
    <div class="alert alert-success mb-3">
        Um novo link de verificação foi enviado para o seu e-mail!
    </div>
@endif

<form action="{{ route('verification.send') }}" method="POST">
    @csrf
    <button type="submit" class="btn btn-auth">Reenviar e-mail de verificação</button>
</form>

<hr class="divider">

<form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit" class="btn btn-link w-100 text-muted" style="font-size:0.875rem">
        Sair da conta
    </button>
</form>

@endsection
