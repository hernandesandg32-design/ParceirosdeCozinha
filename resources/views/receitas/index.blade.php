{{-- resources/views/receitas/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Receitas')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Receitas</h1>
        <p class="page-subtitle">Gerencie todas as receitas do sistema</p>
    </div>
    @auth
        <a href="{{ route('receitas.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nova Receita
        </a>
    @endauth
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
    <div class="alert alert-danger">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        {{ session('error') }}
    </div>
@endif

@if($receitas->isEmpty())
    <div class="empty-state">
        <div class="empty-state__icon">🍽️</div>
        <h3>Nenhuma receita cadastrada</h3>
        <p>Comece criando sua primeira receita!</p>
        @auth
            <a href="{{ route('receitas.create') }}" class="btn btn-primary mt-3">Criar receita</a>
        @endauth
    </div>
@else
    <div class="receitas-grid">
        @foreach($receitas as $receita)
            <div class="receita-card">
                <div class="receita-card__header">
                    <div class="receita-card__badges">
                        <span class="badge badge--status badge--{{ $receita->status }}">
                            {{ ucfirst($receita->status) }}
                        </span>
                        @if($receita->dificuldade)
                            <span class="badge badge--dificuldade">{{ ucfirst($receita->dificuldade) }}</span>
                        @endif
                    </div>
                </div>

                <div class="receita-card__body">
                    <h3 class="receita-card__titulo">{{ $receita->titulo }}</h3>
                    <p class="receita-card__descricao">{{ Str::limit($receita->descricao, 100) }}</p>

                    <div class="receita-card__meta">
                        @if($receita->tempo_preparo)
                            <span class="meta-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $receita->tempo_preparo }}
                            </span>
                        @endif
                        <span class="meta-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            {{ $receita->ingredientes_count ?? $receita->ingredientes->count() }} ingredientes
                        </span>
                        <span class="meta-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                            </svg>
                            {{ $receita->passos->count() }} passos
                        </span>
                    </div>
                </div>

                @auth
                    @if(auth()->id() === $receita->user_id)
                        <div class="receita-card__actions">
                            <a href="{{ route('receitas.ingredientes.edit', $receita) }}" class="btn btn-sm btn-outline">
                                Ingredientes & Passos
                            </a>
                            <a href="{{ route('receitas.edit', $receita) }}" class="btn btn-sm btn-warning">
                                Editar
                            </a>

                            @if($receita->status !== 'publicada' && $receita->estaCompleta())
                                <form action="{{ route('receitas.publicar', $receita) }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success"
                                        onclick="return confirm('Publicar esta receita?')">
                                        Publicar
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('receitas.destroy', $receita) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Tem certeza que deseja deletar esta receita?')">
                                    Deletar
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>
        @endforeach
    </div>
@endif
@endsection
