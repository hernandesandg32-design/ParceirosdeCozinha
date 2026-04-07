{{-- resources/views/receitas/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Nova Receita')

@section('content')

{{-- Indicador de etapas --}}
<div class="stepper">
    <div class="stepper__item stepper__item--active">
        <div class="stepper__circle">1</div>
        <span class="stepper__label">Informações Básicas</span>
    </div>
    <div class="stepper__line"></div>
    <div class="stepper__item">
        <div class="stepper__circle stepper__circle--inactive">2</div>
        <span class="stepper__label stepper__label--inactive">Ingredientes & Passos</span>
    </div>
    <div class="stepper__line stepper__line--inactive"></div>
    <div class="stepper__item">
        <div class="stepper__circle stepper__circle--inactive">3</div>
        <span class="stepper__label stepper__label--inactive">Publicar</span>
    </div>
</div>

<div class="form-card">
    <div class="form-card__header">
        <h2 class="form-card__title">Nova Receita</h2>
        <p class="form-card__subtitle">Preencha as informações básicas da sua receita</p>
    </div>

    @include('partials._errors')

    <form action="{{ route('receitas.store') }}" method="POST" class="recipe-form">
        @csrf

        <div class="form-section">
            <h3 class="form-section__title">Dados Principais</h3>

            <div class="form-group">
                <label class="form-label form-label--required" for="titulo">Título</label>
                <input
                    type="text"
                    id="titulo"
                    name="titulo"
                    class="form-control @error('titulo') is-invalid @enderror"
                    value="{{ old('titulo') }}"
                    placeholder="Ex: Bolo de Chocolate da Vovó"
                    autofocus
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
                    placeholder="Descreva brevemente a receita, sua origem, ocasião ideal..."
                >{{ old('descricao') }}</textarea>
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
                        value="{{ old('tempo_preparo') }}"
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
                        <option value="fácil"   {{ old('dificuldade') == 'fácil'   ? 'selected' : '' }}>🟢 Fácil</option>
                        <option value="médio"   {{ old('dificuldade') == 'médio'   ? 'selected' : '' }}>🟡 Médio</option>
                        <option value="difícil" {{ old('dificuldade') == 'difícil' ? 'selected' : '' }}>🔴 Difícil</option>
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
                        value="{{ old('custo_medio') }}"
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
                    value="{{ old('endereco_video') }}"
                    placeholder="https://youtube.com/..."
                >
                @error('endereco_video')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('receitas.index') }}" class="btn btn-outline">Cancelar</a>
            <button type="submit" class="btn btn-primary">
                Continuar para Ingredientes
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
    </form>
</div>

@endsection
