{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('title', 'Parceiros de Cozinha')

@section('content')

{{-- ═══════════ HERO ═══════════ --}}
<section class="home-hero">
    <div class="home-hero__content">
        <p class="home-hero__eyebrow">Bem-vindo ao</p>
        <h1 class="home-hero__title">Parceiros de<br><span>Cozinha</span></h1>
        <p class="home-hero__subtitle">
            Descubra, compartilhe e se apaixone por receitas feitas com carinho.
            Uma comunidade para quem ama cozinhar.
        </p>
        <div class="home-hero__actions">
            <a href="{{ route('receitas.index') }}" class="btn-hero btn-hero--primary">
                Ver todas as receitas
            </a>
            @guest
                <a href="{{ route('users.create') }}" class="btn-hero btn-hero--outline">
                    Criar conta grátis
                </a>
            @endguest
            @auth
                <a href="{{ route('receitas.create') }}" class="btn-hero btn-hero--outline">
                    + Publicar receita
                </a>
            @endauth
        </div>
    </div>
    <div class="home-hero__illustration" aria-hidden="true">
        <div class="hero-blob">🍳</div>
    </div>
</section>

{{-- ═══════════ CATEGORIAS ═══════════ --}}
<section class="home-section">
    <div class="home-section__header">
        <h2 class="home-section__title">Categorias</h2>
        <p class="home-section__subtitle">Explore por tipo de prato</p>
    </div>

    <div class="categorias-grid">
        @foreach ([
            ['emoji' => '🍝', 'nome' => 'Massas'],
            ['emoji' => '🍰', 'nome' => 'Doces'],
            ['emoji' => '🥩', 'nome' => 'Carnes'],
            ['emoji' => '🥗', 'nome' => 'Vegano'],
            ['emoji' => '🥤', 'nome' => 'Bebidas'],
        ] as $categoria)
            <a href="{{ route('receitas.index') }}" class="categoria-card">
                <span class="categoria-card__emoji">{{ $categoria['emoji'] }}</span>
                <span class="categoria-card__nome">{{ $categoria['nome'] }}</span>
            </a>
        @endforeach
    </div>
</section>

{{-- ═══════════ RECEITAS EM DESTAQUE ═══════════ --}}
<section class="home-section">
    <div class="home-section__header">
        <h2 class="home-section__title">Receitas em Destaque</h2>
        <p class="home-section__subtitle">As últimas criações da nossa comunidade</p>
    </div>

    @if($receitas->isEmpty())
        <div class="home-empty">
            <span>🍽️</span>
            <p>Ainda não há receitas publicadas. Seja o primeiro!</p>
            @auth
                <a href="{{ route('receitas.create') }}" class="btn-hero btn-hero--primary mt-3">
                    Criar receita
                </a>
            @endauth
        </div>
    @else
        <div class="receitas-destaque-grid">
            @foreach($receitas as $receita)
                <article class="destaque-card">

                    {{-- IMAGEM --}}
                    <a href="{{ route('receitas.show', $receita) }}" class="destaque-card__img-link">
                        @if($receita->imagem)
                            <img
                                src="{{ asset('storage/' . $receita->imagem) }}"
                                alt="{{ $receita->titulo }}"
                                class="destaque-card__img"
                            >
                        @else
                            <div class="destaque-card__img-placeholder">🍳</div>
                        @endif

                        @if($receita->dificuldade)
                            <span class="destaque-card__dificuldade destaque-card__dificuldade--{{ $receita->dificuldade }}">
                                {{ ucfirst($receita->dificuldade) }}
                            </span>
                        @endif
                    </a>

                    {{-- CORPO --}}
                    <div class="destaque-card__body">

                        {{-- Autor --}}
                        <div class="destaque-card__autor">
                            <a href="{{ route('users.public', $receita->user) }}" class="autor-link">
                                <div class="autor-avatar">{{ mb_substr($receita->user->name, 0, 1) }}</div>
                                <span>{{ $receita->user->name }}</span>
                            </a>
                        </div>

                        <h3 class="destaque-card__titulo">
                            <a href="{{ route('receitas.show', $receita) }}">{{ $receita->titulo }}</a>
                        </h3>

                        <p class="destaque-card__descricao">
                            {{ Str::limit($receita->descricao, 80) }}
                        </p>

                        {{-- Meta --}}
                        <div class="destaque-card__meta">
                            @if($receita->tempo_preparo)
                                <span class="meta-pill">
                                    ⏱ {{ $receita->tempo_preparo }}
                                </span>
                            @endif
                            <span class="meta-pill">
                                🥕 {{ $receita->ingredientes->count() }} ingredientes
                            </span>
                        </div>

                    </div>

                    {{-- RODAPÉ --}}
                    <div class="destaque-card__footer">
                        <a href="{{ route('receitas.show', $receita) }}" class="btn-ver-mais">
                            Ver receita →
                        </a>

                        {{-- CURTIDA --}}
                        @auth
                            <form action="{{ route('receitas.curtir', $receita) }}" method="POST" class="curtida-form">
                                @csrf
                                <button
                                    type="submit"
                                    class="btn-curtida {{ $receita->curtidaPorMim() ? 'btn-curtida--ativo' : '' }}"
                                    title="Curtir"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="{{ $receita->curtidaPorMim() ? 'currentColor' : 'none' }}"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                    </svg>
                                    <span>{{ $receita->curtidas_count }}</span>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn-curtida" title="Faça login para curtir">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                </svg>
                                <span>{{ $receita->curtidas_count }}</span>
                            </a>
                        @endauth
                    </div>

                </article>
            @endforeach
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('receitas.index') }}" class="btn-hero btn-hero--outline">
                Ver todas as receitas →
            </a>
        </div>
    @endif
</section>

@endsection

@push('styles')
<style>
/* ══════════════════════════════════════════════════
   VARIÁVEIS
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
   HERO
══════════════════════════════════════════════════ */
.home-hero {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 2rem;
    background: linear-gradient(135deg, var(--ciano-light) 0%, #fff 100%);
    border-radius: 20px;
    padding: 3.5rem 3rem;
    margin-bottom: 3rem;
    border: 1px solid var(--ciano);
    position: relative;
    overflow: hidden;
}

.home-hero__eyebrow {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--laranja);
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-bottom: 0.5rem;
}

.home-hero__title {
    font-size: clamp(2rem, 5vw, 3rem);
    font-weight: 800;
    color: var(--text-dark);
    line-height: 1.15;
    margin-bottom: 1rem;
}

.home-hero__title span {
    color: var(--laranja);
}

.home-hero__subtitle {
    font-size: 1rem;
    color: var(--text-muted);
    max-width: 420px;
    line-height: 1.6;
    margin-bottom: 1.75rem;
}

.home-hero__actions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
}

.home-hero__illustration {
    flex-shrink: 0;
}

.hero-blob {
    width: 160px;
    height: 160px;
    background: var(--ciano);
    border-radius: 60% 40% 55% 45% / 50% 60% 40% 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 5rem;
    animation: blob-morph 6s ease-in-out infinite;
}

@keyframes blob-morph {
    0%, 100% { border-radius: 60% 40% 55% 45% / 50% 60% 40% 50%; }
    50%       { border-radius: 40% 60% 45% 55% / 60% 40% 55% 45%; }
}

/* ══════════════════════════════════════════════════
   BOTÕES HERO
══════════════════════════════════════════════════ */
.btn-hero {
    display: inline-flex;
    align-items: center;
    padding: 0.65rem 1.4rem;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.95rem;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-hero--primary {
    background: var(--laranja);
    color: #fff;
    border: 2px solid var(--laranja);
}

.btn-hero--primary:hover {
    background: var(--laranja-dark);
    border-color: var(--laranja-dark);
    color: #fff;
    transform: translateY(-2px);
}

.btn-hero--outline {
    background: transparent;
    color: var(--laranja);
    border: 2px solid var(--laranja);
}

.btn-hero--outline:hover {
    background: var(--laranja);
    color: #fff;
    transform: translateY(-2px);
}

/* ══════════════════════════════════════════════════
   SEÇÕES GERAIS
══════════════════════════════════════════════════ */
.home-section {
    margin-bottom: 3rem;
}

.home-section__header {
    margin-bottom: 1.5rem;
}

.home-section__title {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 0.2rem;
}

.home-section__subtitle {
    font-size: 0.875rem;
    color: var(--text-muted);
    margin: 0;
}

/* ══════════════════════════════════════════════════
   CATEGORIAS
══════════════════════════════════════════════════ */
.categorias-grid {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.categoria-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    background: var(--white);
    border: 2px solid var(--ciano);
    border-radius: var(--radius);
    padding: 1.25rem 1.5rem;
    text-decoration: none;
    transition: all 0.2s;
    min-width: 100px;
    flex: 1;
}

.categoria-card:hover {
    background: var(--laranja);
    border-color: var(--laranja);
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(232,93,47,0.25);
}

.categoria-card:hover .categoria-card__nome {
    color: #fff;
}

.categoria-card__emoji {
    font-size: 2rem;
}

.categoria-card__nome {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-dark);
    transition: color 0.2s;
}

/* ══════════════════════════════════════════════════
   GRID DE DESTAQUE
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
.destaque-card__dificuldade--difícil { background: rgba(220,38,38,0.85); color: #fff; }

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

.destaque-card__titulo a:hover {
    color: var(--laranja);
}

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

.btn-curtida:hover {
    border-color: var(--laranja);
    color: var(--laranja);
}

.btn-curtida--ativo {
    border-color: var(--laranja);
    color: var(--laranja);
    background: #fff3ee;
}

/* ══════════════════════════════════════════════════
   EMPTY STATE
══════════════════════════════════════════════════ */
.home-empty {
    text-align: center;
    padding: 3rem 1rem;
    color: var(--text-muted);
    background: var(--ciano-light);
    border-radius: var(--radius);
    border: 1px dashed var(--ciano-dark);
}

.home-empty span { font-size: 3rem; display: block; margin-bottom: 0.75rem; }

.autor-link {
    display: flex;
    flex-direction: row;
    gap: 5px;
    align-items: center;
    text-decoration: none;

    & span {
        font-weight: bold;
    }
}

/* ══════════════════════════════════════════════════
   RESPONSIVO
══════════════════════════════════════════════════ */
@media (max-width: 768px) {
    .home-hero {
        flex-direction: column;
        padding: 2rem 1.5rem;
        text-align: center;
    }

    .home-hero__subtitle { max-width: 100%; }
    .home-hero__actions  { justify-content: center; }
    .home-hero__illustration { display: none; }

    .categorias-grid { gap: 0.6rem; }
    .categoria-card  { min-width: 80px; padding: 1rem; }

    .receitas-destaque-grid { grid-template-columns: 1fr; }
}
</style>
@endpush
