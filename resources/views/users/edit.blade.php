@extends('layouts.app')

@section('title', 'Editar Usuário')

@section('content')

<h2>Editar Usuário</h2>

<form action="{{ route('users.update', $user) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Nome</label>
        <input type="text" name="name" class="form-control" value="{{ $user->name }}">
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="{{ $user->email }}">
    </div>

    <button class="btn btn-success">Atualizar</button>
</form>

@endsection
