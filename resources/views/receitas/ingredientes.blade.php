{{-- resources/views/receitas/ingredientes.blade.php --}}
@extends('layouts.app')

@section('title', 'Ingredientes & Passos — ' . $receita->titulo)

@section('content')

{{-- Indicador de etapas --}}
<div class="stepper">
    <div class="stepper__item stepper__item--done">
        <div class="stepper__circle stepper__circle--done">✓</div>
        <span class="stepper__label">Informações Básicas</span>
    </div>
    <div class="stepper__line stepper__line--done"></div>
    <div class="stepper__item stepper__item--active">
        <div class="stepper__circle">2</div>
        <span class="stepper__label">Ingredientes & Passos</span>
    </div>
    <div class="stepper__line {{ $receita->estaCompleta() ? 'stepper__line--done' : 'stepper__line--inactive' }}"></div>
    <div class="stepper__item {{ $receita->estaCompleta() ? '' : '' }}">
        <div class="stepper__circle {{ $receita->estaCompleta() ? '' : 'stepper__circle--inactive' }}">3</div>
        <span class="stepper__label {{ $receita->estaCompleta() ? '' : 'stepper__label--inactive' }}">Publicar</span>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="ingredientes-layout">

    {{-- ─── INGREDIENTES ────────────────────────────────────────────────────── --}}
    <div class="form-card">
        <div class="form-card__header">
            <h2 class="form-card__title">
                <span class="form-card__icon">🥕</span>
                Ingredientes
            </h2>
            <span class="badge badge--count">{{ $receita->ingredientes->count() }}</span>
        </div>

        {{-- Lista atual --}}
        @if($receita->ingredientes->isNotEmpty())
            <ul class="items-list">
                @foreach($receita->ingredientes as $ingrediente)
                    <li class="items-list__item">
                        <div class="items-list__content">
                            <span class="items-list__qty">
                                {{ $ingrediente->quantidade }}{{ $ingrediente->unidade ? ' ' . $ingrediente->unidade : '' }}
                            </span>
                            <span class="items-list__name">{{ $ingrediente->nome }}</span>
                        </div>
                        <form action="{{ route('ingredientes.destroy', [$receita, $ingrediente]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon btn-icon--danger" title="Remover ingrediente"
                                onclick="return confirm('Remover este ingrediente?')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="empty-hint">Nenhum ingrediente adicionado ainda.</p>
        @endif

        {{-- Formulário para adicionar --}}
        @include('partials._errors')
        <form action="{{ route('ingredientes.store', $receita) }}" method="POST" class="add-form">
            @csrf
            <div class="add-form__row">
                <input
                    type="text"
                    name="quantidade"
                    class="form-control add-form__qty"
                    placeholder="Qtd"
                    value="{{ old('quantidade') }}"
                >
                <input
                    type="text"
                    name="unidade"
                    class="form-control add-form__unit"
                    placeholder="Unid."
                    value="{{ old('unidade') }}"
                >
                <input
                    type="text"
                    name="nome"
                    class="form-control add-form__name @error('nome') is-invalid @enderror"
                    placeholder="Nome do ingrediente *"
                    value="{{ old('nome') }}"
                    required
                >
                <button type="submit" class="btn btn-primary btn-add">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Adicionar
                </button>
            </div>
            @error('nome')
                <span class="invalid-feedback d-block mt-1">{{ $message }}</span>
            @enderror
        </form>
    </div>

    {{-- ─── PASSOS ──────────────────────────────────────────────────────────── --}}
    <div class="form-card">
        <div class="form-card__header">
            <h2 class="form-card__title">
                <span class="form-card__icon">📋</span>
                Modo de Preparo
            </h2>
            <span class="badge badge--count">{{ $receita->passos->count() }}</span>
        </div>

        {{-- Lista atual --}}
        @if($receita->passos->isNotEmpty())
            <ol class="passos-list">
                @foreach($receita->passos as $passo)
                    <li class="passos-list__item">
                        <div class="passos-list__number">{{ $passo->numero }}</div>
                        <div class="passos-list__content">
                            <p class="passos-list__descricao">{{ $passo->descricao }}</p>
                            @if($passo->dica)
                                <p class="passos-list__dica">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                    Dica: {{ $passo->dica }}
                                </p>
                            @endif
                        </div>
                        <form action="{{ route('passos.destroy', [$receita, $passo]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon btn-icon--danger" title="Remover passo"
                                onclick="return confirm('Remover este passo?')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </form>
                    </li>
                @endforeach
            </ol>
        @else
            <p class="empty-hint">Nenhum passo adicionado ainda.</p>
        @endif

        {{-- Formulário para adicionar --}}
        <form action="{{ route('passos.store', $receita) }}" method="POST" class="add-form add-form--vertical">
            @csrf
            <div class="form-group">
                <textarea
                    name="descricao"
                    class="form-control @error('descricao') is-invalid @enderror"
                    rows="3"
                    placeholder="Descreva este passo do preparo... *"
                    required
                >{{ old('descricao') }}</textarea>
                @error('descricao')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <input
                    type="text"
                    name="dica"
                    class="form-control"
                    placeholder="Dica opcional para este passo..."
                    value="{{ old('dica') }}"
                >
            </div>
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Adicionar Passo
            </button>
        </form>
    </div>
</div>

{{-- ─── AÇÕES FINAIS ─────────────────────────────────────────────────────────── --}}
<div class="form-card form-card--actions">
    <div class="final-actions">
        <a href="{{ route('receitas.edit', $receita) }}" class="btn btn-outline">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Voltar e Editar Informações
        </a>

        <div class="final-actions__right">
            @if($receita->estaCompleta())
                @if($receita->status !== 'publicada')
                    <div class="publish-box">
                        <div class="publish-box__info">
                            <span class="publish-box__icon">✅</span>
                            <div>
                                <strong>Receita pronta para publicação!</strong>
                                <p>Você adicionou ingredientes e passos. Publique quando quiser.</p>
                            </div>
                        </div>
                        <form action="{{ route('receitas.publicar', $receita) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success btn-publish"
                                onclick="return confirm('Publicar esta receita agora?')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Publicar Receita
                            </button>
                        </form>
                    </div>
                @else
                    <div class="publish-box publish-box--published">
                        <span class="publish-box__icon">🎉</span>
                        <strong>Receita já publicada!</strong>
                    </div>
                @endif
            @else
                <div class="publish-box publish-box--locked">
                    <span class="publish-box__icon">🔒</span>
                    <div>
                        <strong>Para publicar, adicione:</strong>
                        <ul class="publish-requirements">
                            @if($receita->ingredientes->count() === 0)
                                <li>Pelo menos 1 ingrediente</li>
                            @endif
                            @if($receita->passos->count() === 0)
                                <li>Pelo menos 1 passo de preparo</li>
                            @endif
                        </ul>
                    </div>
                </div>
            @endif

            <a href="{{ route('receitas.index') }}" class="btn btn-outline">
                Salvar e sair
            </a>
        </div>
    </div>
</div>

@endsection
