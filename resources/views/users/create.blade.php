@extends('layouts.app')

@section('title', 'Criar Usuário')

@section('content')

<h2>Novo Usuário</h2>

<form action="{{ route('users.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label>Nome</label>
        <input type="text" name="name" class="form-control">
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control">
    </div>

    <div class="mb-3">
        <label>Senha</label>
        <input type="password" name="password" class="form-control">
    </div>

    <button class="btn btn-success">Salvar</button>
</form>

@endsection
