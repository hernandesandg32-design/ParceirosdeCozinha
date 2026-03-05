@extends('layouts.app')

@section('title', 'Editar Receita')

@section('content')
<h2>Editar Receita</h2>

<form action="{{ route('receitas.update', $receita) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Título</label>
        <input type="text" name="titulo" class="form-control" value="{{ $receita->titulo }}">
    </div>

    <div class="mb-3">
        <label>Descrição</label>
        <textarea name="descricao" class="form-control">{{ $receita->descricao }}</textarea>
    </div>

    <div class="mb-3">
        <label>Data de Publicação</label>
        <input type="date" name="data_publicacao" class="form-control" value="{{ $receita->data_publicacao }}">
    </div>

    <div class="mb-3">
        <label>Endereço do Vídeo</label>
        <input type="text" name="endereco_video" class="form-control" value="{{ $receita->endereco_video }}">
    </div>

    <div class="mb-3">
        <label>Tempo de Preparo</label>
        <input type="text" name="tempo_preparo" class="form-control" value="{{ $receita->tempo_preparo }}">
    </div>

    <div class="mb-3">
        <label>Dificuldade</label>
        <select name="dificuldade" class="form-control">
            <option value="fácil" {{ $receita->dificuldade == 'fácil' ? 'selected' : '' }}>Fácil</option>
            <option value="médio" {{ $receita->dificuldade == 'médio' ? 'selected' : '' }}>Médio</option>
            <option value="difícil" {{ $receita->dificuldade == 'difícil' ? 'selected' : '' }}>Difícil</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Custo Médio</label>
        <input type="number" step="0.01" name="custo_medio" class="form-control" value="{{ $receita->custo_medio }}">
    </div>

    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control">
            <option value="publicada" {{ $receita->status == 'publicada' ? 'selected' : '' }}>Publicada</option>
            <option value="pendente" {{ $receita->status == 'pendente' ? 'selected' : '' }}>Pendente</option>
            <option value="oculta" {{ $receita->status == 'oculta' ? 'selected' : '' }}>Oculta</option>
        </select>
    </div>

    <button class="btn btn-success">Atualizar</button>
</form>
@endsection
