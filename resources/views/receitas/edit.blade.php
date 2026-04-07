{{-- resources/views/receitas/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Editar — ' . $receita->titulo)

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Editar Receita</h1>
        <p class="page-subtitle">{{ $receita->titulo }}</p>
    </div>
    <a href="{{ route('receitas.ingredientes.edit', $receita) }}" class="btn btn-outline">
        Ingredientes & Passos
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
@endif

<div class="form-card">
    <div class="form-card__header">
        <h2 class="form-card__title">Informações Básicas</h2>
        <span class="badge badge--status badge--{{ $receita->status }}">{{ ucfirst($receita->status) }}</span>
    </div>

    @include('partials._errors')

    <form action="{{ route('receitas.update', $receita) }}" method="POST" class="recipe-form">
        @csrf
        @method('PUT')

        <div class="form-section">
            <h3 class="form-section__title">Dados Principais</h3>

            <div class="form-group">
                <label class="form-label form-label--required" for="titulo">Título</label>
                <input
                    type="text"
                    id="titulo"
                    name="titulo"
                    class="form-control @error('titulo') is-invalid @enderror"
                    value="{{ old('titulo', $receita->titulo) }}"
                    placeholder="Título da receita"
                >
                @error('titulo')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label form-label--required" for="descricao">Descrição</label>
                <textarea
                    id="descricao"
                    name="descricao"
                    class="form-control @error('descricao') is-invalid @enderror"
                    rows="4"
                    placeholder="Descrição da receita"
                >{{ old('descricao', $receita->descricao) }}</textarea>
                @error('descricao')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-section">
            <h3 class="form-section__title">Detalhes</h3>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="tempo_preparo">Tempo de Preparo</label>
                    <input
                        type="text"
                        id="tempo_preparo"
                        name="tempo_preparo"
                        class="form-control @error('tempo_preparo') is-invalid @enderror"
                        value="{{ old('tempo_preparo', $receita->tempo_preparo) }}"
                        placeholder="Ex: 45 minutos"
                    >
                    @error('tempo_preparo')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="dificuldade">Dificuldade</label>
                    <select id="dificuldade" name="dificuldade" class="form-control form-select @error('dificuldade') is-invalid @enderror">
                        <option value="">Selecione...</option>
                        <option value="fácil"   {{ old('dificuldade', $receita->dificuldade) == 'fácil'   ? 'selected' : '' }}>🟢 Fácil</option>
                        <option value="médio"   {{ old('dificuldade', $receita->dificuldade) == 'médio'   ? 'selected' : '' }}>🟡 Médio</option>
                        <option value="difícil" {{ old('dificuldade', $receita->dificuldade) == 'difícil' ? 'selected' : '' }}>🔴 Difícil</option>
                    </select>
                    @error('dificuldade')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="custo_medio">Custo Médio (R$)</label>
                    <input
                        type="number"
                        id="custo_medio"
                        name="custo_medio"
                        step="0.01"
                        min="0"
                        class="form-control @error('custo_medio') is-invalid @enderror"
                        value="{{ old('custo_medio', $receita->custo_medio) }}"
                        placeholder="0,00"
                    >
                    @error('custo_medio')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="endereco_video">Link do Vídeo (opcional)</label>
                <input
                    type="url"
                    id="endereco_video"
                    name="endereco_video"
                    class="form-control @error('endereco_video') is-invalid @enderror"
                    value="{{ old('endereco_video', $receita->endereco_video) }}"
                    placeholder="https://youtube.com/..."
                >
                @error('endereco_video')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('receitas.index') }}" class="btn btn-outline">Cancelar</a>
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </div>
    </form>
</div>
@endsection
