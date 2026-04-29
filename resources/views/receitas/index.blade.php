@extends('layouts.app')
@section('title', 'Receitas')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Receitas</h1>
            <p class="page-subtitle">Gerencie todas as receitas do sistema</p>
        </div>
    </div>

    {{-- ALERTAS --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- ═══ FILTROS ═══ --}}
    <form method="GET" action="{{ route('receitas.index') }}" class="filtros-wrapper">

        {{-- CATEGORIAS --}}
        <div class="filtros-categorias">
            <a href="{{ route('receitas.index', request()->except('categoria', 'page')) }}"
                class="categoria-pill {{ !request('categoria') ? 'categoria-pill--ativo' : '' }}">
                🍽️ Todas
            </a>
            @foreach ($categories as $cat)
                <a href="{{ route('receitas.index', array_merge(request()->except('page'), ['categoria' => $cat->slug])) }}"
                    class="categoria-pill {{ request('categoria') === $cat->slug ? 'categoria-pill--ativo' : '' }}">
                    {{ $cat->emoji }} {{ $cat->nome }}
                </a>
            @endforeach
        </div>

        {{-- FILTROS SECUNDÁRIOS --}}
        <div class="filtros-secundarios">
            <select name="dificuldade" onchange="this.form.submit()" class="filtro-select">
                <option value="">Dificuldade</option>
                <option value="fácil" {{ request('dificuldade') === 'fácil' ? 'selected' : '' }}>🟢 Fácil</option>
                <option value="médio" {{ request('dificuldade') === 'médio' ? 'selected' : '' }}>🟡 Médio</option>
                <option value="difícil" {{ request('dificuldade') === 'difícil' ? 'selected' : '' }}>🔴 Difícil</option>
            </select>

            <select name="ordenar" onchange="this.form.submit()" class="filtro-select">
                <option value="recentes" {{ request('ordenar', 'recentes') === 'recentes' ? 'selected' : '' }}>🕐 Mais
                    recentes</option>
                <option value="curtidas" {{ request('ordenar') === 'curtidas' ? 'selected' : '' }}>❤️ Mais curtidas
                </option>
            </select>

            @if (request()->hasAny(['categoria', 'dificuldade', 'ordenar']))
                <a href="{{ route('receitas.index') }}" class="filtro-limpar">✕ Limpar filtros</a>
            @endif
        </div>
    </form>
    {{-- GRID DE RECEITAS --}}
    @if ($receitas->isEmpty())
        <div class="empty-state">
            <div class="empty-state__icon">🍽️</div>
            <h3>Nenhuma receita encontrada</h3>
            <p>Tente ajustar os filtros ou <a href="{{ route('receitas.index') }}">ver todas</a>.</p>
        </div>
    @else
        <div class="receitas-destaque-grid">
            @foreach ($receitas as $receita)
                <article class="destaque-card">

                    {{-- IMAGEM --}}
                    <a href="{{ route('receitas.show', $receita) }}" class="destaque-card__img-link">
                        @if ($receita->imagem)
                            <img src="{{ asset('storage/' . $receita->imagem) }}" alt="{{ $receita->titulo }}"
                                class="destaque-card__img">
                        @else
                            <div class="destaque-card__img-placeholder">
                                {{ $receita->category->emoji ?? '🍳' }}
                            </div>
                        @endif
                        @if ($receita->dificuldade)
                            <span
                                class="destaque-card__dificuldade destaque-card__dificuldade--{{ $receita->dificuldade }}">
                                {{ ucfirst($receita->dificuldade) }}
                            </span>
                        @endif
                        @if ($receita->category)
                            <span class="destaque-card__categoria">
                                {{ $receita->category->emoji }} {{ $receita->category->nome }}
                            </span>
                        @endif
                    </a>

                    {{-- CORPO --}}
                    <div class="destaque-card__body">
                        <div class="destaque-card__autor">
                            <div class="autor-avatar">{{ mb_substr($receita->user->name, 0, 1) }}</div>
                            <span>{{ $receita->user->name }}</span>
                        </div>
                        <h3 class="destaque-card__titulo">
                            <a href="{{ route('receitas.show', $receita) }}">{{ $receita->titulo }}</a>
                        </h3>
                        <p class="destaque-card__descricao">{{ Str::limit($receita->descricao, 80) }}</p>
                        <div class="destaque-card__meta">
                            @if ($receita->tempo_preparo)
                                <span class="meta-pill">⏱ {{ $receita->tempo_preparo }}</span>
                            @endif
                            <span class="meta-pill">🥕 {{ $receita->ingredientes->count() }} ingredientes</span>
                        </div>
                    </div>

                    {{-- RODAPÉ --}}
                    <div class="destaque-card__footer">
                        <div style="display:flex;gap:8px;align-items:center;">
                            <a href="{{ route('receitas.show', $receita) }}" class="btn-ver-mais">Ver receita →</a>
                            @auth
                                @if (auth()->id() === $receita->user_id)
                                    <a href="{{ route('receitas.edit', $receita) }}" class="btn btn-sm btn-warning">Editar</a>
                                    <form action="{{ route('receitas.destroy', $receita) }}" method="POST"
                                        style="display:inline" onsubmit="return confirm('Deletar esta receita?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Deletar</button>
                                    </form>
                                @endif
                            @endauth
                        </div>

                        {{-- CURTIDA --}}
                        @auth
                            <form action="{{ route('receitas.curtir', $receita) }}" method="POST" class="curtida-form">
                                @csrf
                                <button type="submit"
                                    class="btn-curtida {{ $receita->curtidaPorMim() ? 'btn-curtida--ativo' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="{{ $receita->curtidaPorMim() ? 'currentColor' : 'none' }}" stroke="currentColor"
                                        stroke-width="2">
                                        <path
                                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                                    </svg>
                                    <span>{{ $receita->curtidas_count }}</span>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn-curtida">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2">
                                    <path
                                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                                </svg>
                                <span>{{ $receita->curtidas_count }}</span>
                            </a>
                        @endauth
                    </div>

                </article>
            @endforeach
        </div>
    @endif

    {{-- PAGINAÇÃO --}}
    <div class="mt-5">
        {{ $receitas->links() }}
    </div>

@endsection

@push('styles')
<style>
/* ══════════════════════════════════════════════════
   VARIÁVEIS (mesmas da home)
══════════════════════════════════════════════════ */
:root {
    --laranja:      #e85d2f;
    --laranja-dark: #c44d22;
    --ciano:        #8df7e7;
    --ciano-dark:   #5ee8d4;
    --ciano-light:  #e8fdfb;
    --text-dark:    #1a1a2e;
    --text-muted:   #6b7280;
    --white:        #ffffff;
    --radius:       14px;
    --shadow:       0 4px 20px rgba(0,0,0,0.08);
}

/* ══════════════════════════════════════════════════
   FILTROS
══════════════════════════════════════════════════ */
.filtros-wrapper {
    margin-bottom: 2rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.filtros-categorias {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.categoria-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 0.4rem 1rem;
    border-radius: 999px;
    font-size: 0.875rem;
    font-weight: 600;
    text-decoration: none;
    border: 2px solid var(--ciano);
    color: var(--text-dark);
    background: var(--white);
    transition: all 0.2s;
}
.categoria-pill:hover,
.categoria-pill--ativo {
    background: var(--laranja);
    border-color: var(--laranja);
    color: #fff;
}

.filtros-secundarios {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.75rem;
}

.filtro-select {
    padding: 0.4rem 0.75rem;
    border-radius: 8px;
    border: 1.5px solid var(--ciano);
    font-size: 0.875rem;
    background: var(--white);
    color: var(--text-dark);
    cursor: pointer;
    outline: none;
    transition: border-color 0.2s;
}
.filtro-select:focus {
    border-color: var(--laranja);
}

.filtro-limpar {
    font-size: 0.85rem;
    color: var(--text-muted);
    text-decoration: none;
    padding: 0.4rem 0.75rem;
    border: 1.5px solid #ddd;
    border-radius: 8px;
    transition: all 0.2s;
}
.filtro-limpar:hover {
    border-color: var(--laranja);
    color: var(--laranja);
}

/* ══════════════════════════════════════════════════
   GRID DE RECEITAS
══════════════════════════════════════════════════ */
.receitas-destaque-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
    gap: 1.5rem;
}

/* ══════════════════════════════════════════════════
   CARD DE DESTAQUE
══════════════════════════════════════════════════ */
.destaque-card {
    background: var(--white);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    border: 1px solid #f0fdf9;
    transition: transform 0.2s, box-shadow 0.2s;
}
.destaque-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.12);
}

/* Imagem */
.destaque-card__img-link {
    display: block;
    position: relative;
    height: 200px;
    overflow: hidden;
    background: var(--ciano-light);
    text-decoration: none;
}
.destaque-card__img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.35s;
}
.destaque-card:hover .destaque-card__img {
    transform: scale(1.05);
}
.destaque-card__img-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
    background: linear-gradient(135deg, var(--ciano-light), var(--ciano));
}

/* Badges na imagem */
.destaque-card__dificuldade {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 0.72rem;
    font-weight: 700;
    backdrop-filter: blur(6px);
}
.destaque-card__dificuldade--fácil   { background: rgba(22,163,74,0.85);  color: #fff; }
.destaque-card__dificuldade--médio   { background: rgba(202,138,4,0.85);  color: #fff; }
.destaque-card__dificuldade--difícil { background: rgba(220,38,38,0.85);  color: #fff; }

.destaque-card__categoria {
    position: absolute;
    bottom: 10px;
    left: 10px;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 0.72rem;
    font-weight: 700;
    background: rgba(255,255,255,0.88);
    color: var(--text-dark);
    backdrop-filter: blur(6px);
}

/* Corpo */
.destaque-card__body {
    padding: 1.1rem 1.1rem 0.75rem;
    flex: 1;
}
.destaque-card__autor {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.6rem;
}
.autor-avatar {
    width: 26px;
    height: 26px;
    background: var(--ciano);
    color: var(--text-dark);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 700;
    flex-shrink: 0;
}
.destaque-card__autor span {
    font-size: 0.8rem;
    color: var(--text-muted);
    font-weight: 500;
}
.destaque-card__titulo {
    font-size: 1rem;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0 0 0.4rem;
    line-height: 1.3;
}
.destaque-card__titulo a {
    color: inherit;
    text-decoration: none;
    transition: color 0.15s;
}
.destaque-card__titulo a:hover { color: var(--laranja); }

.destaque-card__descricao {
    font-size: 0.84rem;
    color: var(--text-muted);
    margin: 0 0 0.75rem;
    line-height: 1.5;
}
.destaque-card__meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.4rem;
}
.meta-pill {
    background: var(--ciano-light);
    color: var(--text-dark);
    border-radius: 20px;
    padding: 2px 10px;
    font-size: 0.75rem;
    font-weight: 500;
    border: 1px solid var(--ciano);
}

/* Rodapé */
.destaque-card__footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1.1rem;
    border-top: 1px solid var(--ciano-light);
    background: #fafffe;
}
.btn-ver-mais {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--laranja);
    text-decoration: none;
    transition: color 0.15s;
}
.btn-ver-mais:hover {
    color: var(--laranja-dark);
    text-decoration: underline;
}

/* Curtida */
.curtida-form { margin: 0; }
.btn-curtida {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: none;
    border: 1.5px solid #e0e0e0;
    border-radius: 20px;
    padding: 4px 10px;
    font-size: 0.82rem;
    color: var(--text-muted);
    cursor: pointer;
    text-decoration: none;
    transition: all 0.2s;
}
.btn-curtida:hover { border-color: var(--laranja); color: var(--laranja); }
.btn-curtida--ativo {
    border-color: var(--laranja);
    color: var(--laranja);
    background: #fff3ee;
}

/* ══════════════════════════════════════════════════
   EMPTY STATE
══════════════════════════════════════════════════ */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    color: var(--text-muted);
    background: var(--ciano-light);
    border-radius: var(--radius);
    border: 1px dashed var(--ciano-dark);
}
.empty-state__icon { font-size: 3rem; display: block; margin-bottom: 0.75rem; }

/* ══════════════════════════════════════════════════
   PAGINAÇÃO
══════════════════════════════════════════════════ */
.paginacao-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.35rem;
    flex-wrap: wrap;
    margin: 2rem 0;
}
.paginacao-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
    border: 1.5px solid var(--ciano);
    color: var(--text-dark);
    background: var(--white);
    transition: all 0.2s;
}
.paginacao-btn:hover       { background: var(--laranja); border-color: var(--laranja); color: #fff; }
.paginacao-btn--ativo      { background: var(--laranja); border-color: var(--laranja); color: #fff; }
.paginacao-btn--disabled   { opacity: 0.4; cursor: default; pointer-events: none; }

/* ══════════════════════════════════════════════════
   RESPONSIVO
══════════════════════════════════════════════════ */
@media (max-width: 768px) {
    .filtros-categorias { gap: 0.4rem; }
    .categoria-pill     { padding: 0.35rem 0.75rem; font-size: 0.8rem; }
    .receitas-destaque-grid { grid-template-columns: 1fr; }
}
</style>
@endpush
