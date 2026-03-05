@extends('layouts.app')

@section('title', 'Receitas')

@section('content')
<h2>Receitas</h2>

<a href="{{ route('receitas.create') }}" class="btn btn-primary mb-3">
    Nova Receita
</a>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<table class="table">
    <thead>
        <tr>
            <th>Título</th>
            <th>Descrição</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($receitas as $receita)
            <tr>
                <td>{{ $receita->titulo }}</td>
                <td>{{ $receita->descricao }}</td>
                <td>
                    <a href="{{ route('receitas.edit', $receita) }}" class="btn btn-warning btn-sm">Editar</a>

                    <form action="{{ route('receitas.destroy', $receita) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Deletar</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection
