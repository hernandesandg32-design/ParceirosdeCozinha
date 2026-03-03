@extends('layouts.app')

@section('title', 'Lista de Usuários')

@section('content')

<h2>Lista de Usuários</h2>

<a href="{{ route('users.create') }}" class="btn btn-primary mb-3">
    Novo Usuário
</a>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @forelse($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
                <a href="{{ route('users.show', $user) }}" class="btn btn-info btn-sm">Ver</a>
                <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm">Editar</a>

                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">
                        Excluir
                    </button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4">Nenhum usuário cadastrado.</td>
        </tr>
        @endforelse
    </tbody>
</table>

@endsection
