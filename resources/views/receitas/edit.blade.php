{{-- resources/views/receitas/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Editar — ' . $receita->titulo)

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Editar Receita</h1>
        <p class="page-subtitle">{{ $receita->titulo }}</p>
    </div>
    <span class="badge badge--status badge--{{ $receita->status }}">{{ ucfirst($receita->status) }}</span>
</div>

{{-- ALERTAS --}}
@if(session('success'))
    <div class="alert alert-success">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        {{ session('error') }}
    </div>
@endif

{{-- ═══════════════════════════════════════════════════════════
     BLOCO 1 — INFORMAÇÕES BÁSICAS
════════════════════════════════════════════════════════════ --}}
<div class="form-card">
    <div class="form-card__header">
        <h2 class="form-card__title">📝 Informações Básicas</h2>
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

{{-- ═══════════════════════════════════════════════════════════
     BLOCO 2 — INGREDIENTES E PASSOS
════════════════════════════════════════════════════════════ --}}
<div class="section-divider">
    <span>Ingredientes & Modo de Preparo</span>
</div>

@if(session('success_ingrediente') || session('success_passo'))
    <div class="alert alert-success">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success_ingrediente') ?? session('success_passo') }}
    </div>
@endif

<div class="row g-4 mb-4">

    {{-- INGREDIENTES --}}
    <div class="col-lg-6">
        <div class="form-card h-100">
            <div class="form-card__header">
                <h2 class="form-card__title">🥕 Ingredientes</h2>
            </div>

            @if($receita->ingredientes->count())
                <ul class="ingredient-list mb-3">
                    @foreach($receita->ingredientes as $ingrediente)
                        <li class="ingredient-item">
                            <div>
                                <strong>{{ $ingrediente->nome }}</strong>
                                @if($ingrediente->quantidade)
                                    <span class="text-muted"> — {{ $ingrediente->quantidade }}</span>
                                @endif
                            </div>
                            <form action="{{ route('receitas.ingredientes.destroy', [$receita, $ingrediente]) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-remove" title="Remover">✕</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted small mb-3">Nenhum ingrediente adicionado ainda.</p>
            @endif

            <form action="{{ route('receitas.ingredientes.store', $receita) }}" method="POST">
                @csrf
                <div class="row g-2">
                    <div class="col-7">
                        <input
                            type="text"
                            name="nome"
                            class="form-control form-control-sm @error('nome') is-invalid @enderror"
                            placeholder="Nome do ingrediente *"
                            value="{{ old('nome') }}"
                        >
                        @error('nome')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-5">
                        <input
                            type="text"
                            name="quantidade"
                            class="form-control form-control-sm @error('quantidade') is-invalid @enderror"
                            placeholder="Quantidade"
                            value="{{ old('quantidade') }}"
                        >
                        @error('quantidade')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary-orange btn-sm w-100">
                            + Adicionar ingrediente
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- PASSOS --}}
    <div class="col-lg-6">
        <div class="form-card h-100">
            <div class="form-card__header">
                <h2 class="form-card__title">👨‍🍳 Modo de Preparo</h2>
            </div>

            @if($receita->passos->count())
                <ol class="passo-list mb-3">
                    @foreach($receita->passos as $passo)
                        <li class="passo-item">
                            <div class="passo-numero">{{ $passo->ordem }}</div>
                            <div class="passo-texto flex-grow-1">{{ $passo->descricao }}</div>
                            <form action="{{ route('receitas.passos.destroy', [$receita, $passo]) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-remove" title="Remover">✕</button>
                            </form>
                        </li>
                    @endforeach
                </ol>
            @else
                <p class="text-muted small mb-3">Nenhum passo adicionado ainda.</p>
            @endif

            <form action="{{ route('receitas.passos.store', $receita) }}" method="POST">
                @csrf
                <textarea
                    name="descricao"
                    rows="3"
                    class="form-control form-control-sm @error('descricao') is-invalid @enderror"
                    placeholder="Descreva este passo do preparo... *"
                >{{ old('descricao') }}</textarea>
                @error('descricao')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <button type="submit" class="btn btn-primary-orange btn-sm w-100 mt-2">
                    + Adicionar passo
                </button>
            </form>
        </div>
    </div>

</div>

{{-- ═══════════════════════════════════════════════════════════
     BLOCO 3 — PUBLICAR
════════════════════════════════════════════════════════════ --}}
<div class="form-card">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

        <div>
            @if($receita->estaCompleta())
                <span class="badge-completa">✅ Receita completa e pronta para publicar!</span>
            @else
                <span class="badge-pendente">⚠️ Adicione ao menos 1 ingrediente e 1 passo para publicar.</span>
            @endif
        </div>

        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('receitas.index') }}" class="btn btn-outline">
                ← Voltar para receitas
            </a>

            @if($receita->estaCompleta() && $receita->status !== 'publicada')
                <form action="{{ route('receitas.publicar', $receita) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success-green">
                        🚀 Publicar receita
                    </button>
                </form>
            @elseif($receita->status === 'publicada')
                <span class="badge-completa">🌐 Receita já publicada</span>
            @endif
        </div>

    </div>
</div>

@endsection
