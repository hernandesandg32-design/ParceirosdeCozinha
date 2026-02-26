@extends('layouts.app')

@section('title', 'Detalhes do Usuário')

@section('content')

<h2>Detalhes</h2>

<ul class="list-group">
    <li class="list-group-item"><strong>ID:</strong> {{ $user->id }}</li>
    <li class="list-group-item"><strong>Nome:</strong> {{ $user->name }}</li>
    <li class="list-group-item"><strong>Email:</strong> {{ $user->email }}</li>
</ul>

<a href="{{ route('users.index') }}" class="btn btn-secondary mt-3">
    Voltar
</a>

@endsection
