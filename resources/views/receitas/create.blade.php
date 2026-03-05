@extends('layouts.app')

@section('title', 'Receitas')

@section('content')
<h2>Receitas</h2>

<form action="{{ route('receitas.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label>Título</label>
        <input type="text" name="titulo" class="form-control">
    </div>

    <div class="mb-3">
        <label>Descrição</label>
        <textarea name="descricao" class="form-control"></textarea>
    </div>

    <div class="mb-3">
        <label>Data de Publicação</label>
        <input type="date" name="data_publicacao" class="form-control">
    </div>

    <div class="mb-3">
        <label>Endereço do Vídeo</label>
        <input type="text" name="endereco_video" class="form-control">
    </div>

    <div class="mb-3">
        <label>Tempo de Preparo</label>
        <input type="text" name="tempo_preparo" class="form-control">
    </div>

    <div class="mb-3">
        <label>Dificuldade</label>
        <select name="dificuldade" class="form-control">
            <option value="fácil">Fácil</option>
            <option value="médio">Médio</option>
            <option value="difícil">Difícil</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Custo Médio</label>
        <input type="number" step="0.01" name="custo_medio" class="form-control">
    </div>

    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control">
            <option value="publicada">Publicada</option>
            <option value="pendente">Pendente</option>
            <option value="oculta">Oculta</option>
        </select>
    </div>

    <button class="btn btn-success">Salvar</button>
</form>
@endsection
