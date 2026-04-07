@extends('layouts.app')
@section('title', 'Ingredientes e Passos')

@section('content')

{{-- STEPPER --}}
<div class="stepper mb-4">
    <div class="step done">
        <div class="step-circle">✓</div>
        <span>Informações</span>
    </div>
    <div class="step-line active"></div>
    <div class="step active">
        <div class="step-circle">2</div>
        <span>Ingredientes e Passos</span>
    </div>
    <div class="step-line"></div>
    <div class="step">
        <div class="step-circle">3</div>
        <span>Publicar</span>
    </div>
</div>

{{-- TÍTULO --}}
<div class="d-flex align-items-center gap-3 mb-4">
    <div>
        <h2 class="mb-0">{{ $receita->titulo }}</h2>
        <small class="text-muted">Adicione os ingredientes e o modo de preparo</small>
    </div>
    <a href="{{ route('receitas.edit', $receita) }}" class="btn btn-sm btn-outline-secondary ms-auto">
        ✏️ Editar informações
    </a>
</div>

<div class="row g-4">

    {{-- COLUNA ESQUERDA: INGREDIENTES --}}
    <div class="col-lg-6">
        <div class="form-card h-100">
            <div class="form-card-header">
                <h5>🥕 Ingredientes</h5>
            </div>

            @if (session('success_ingrediente'))
                <div class="alert alert-success py-2">{{ session('success_ingrediente') }}</div>
            @endif

            {{-- Lista de ingredientes existentes --}}
            @if ($receita->ingredientes->count())
                <ul class="ingredient-list mb-3">
                    @foreach ($receita->ingredientes as $ingrediente)
                        <li class="ingredient-item">
                            <div>
                                <strong>{{ $ingrediente->nome }}</strong>
                                @if ($ingrediente->quantidade)
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

            {{-- Formulário para adicionar ingrediente --}}
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
                        <button type="submit" class="btn btn-sm btn-primary-orange w-100">
                            + Adicionar ingrediente
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- COLUNA DIREITA: PASSOS --}}
    <div class="col-lg-6">
        <div class="form-card h-100">
            <div class="form-card-header">
                <h5>👨‍🍳 Modo de Preparo</h5>
            </div>

            @if (session('success_passo'))
                <div class="alert alert-success py-2">{{ session('success_passo') }}</div>
            @endif

            {{-- Lista de passos existentes --}}
            @if ($receita->passos->count())
                <ol class="passo-list mb-3">
                    @foreach ($receita->passos as $passo)
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

            {{-- Formulário para adicionar passo --}}
            <form action="{{ route('receitas.passos.store', $receita) }}" method="POST">
                @csrf
                <div>
                    <textarea
                        name="descricao"
                        rows="3"
                        class="form-control form-control-sm @error('descricao') is-invalid @enderror"
                        placeholder="Descreva este passo do preparo... *"
                    >{{ old('descricao') }}</textarea>
                    @error('descricao')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <button type="submit" class="btn btn-sm btn-primary-orange w-100 mt-2">
                        + Adicionar passo
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

{{-- RODAPÉ DE AÇÕES --}}
<div class="form-card mt-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

        <div>
            @if ($receita->estaCompleta())
                <span class="badge-completa">✅ Receita completa e pronta para publicar!</span>
            @else
                <span class="badge-pendente">⚠️ Adicione ao menos 1 ingrediente e 1 passo para publicar.</span>
            @endif
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('receitas.index') }}" class="btn btn-outline-secondary">
                Salvar e sair
            </a>

            @if ($receita->estaCompleta())
                <form action="{{ route('receitas.publicar', $receita) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success-green">
                        🚀 Publicar receita
                    </button>
                </form>
            @endif
        </div>

    </div>
</div>

@endsection
