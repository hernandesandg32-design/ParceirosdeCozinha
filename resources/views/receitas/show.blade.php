{{-- resources/views/receitas/show.blade.php --}}
@extends('layouts.app')

@section('title', $receita->titulo)

@section('content')

{{-- CABEÇALHO --}}
<div class="show-header__img-wrap">
    @php $imagens = $receita->imagens; $principal = $receita->imagemPrincipal(); @endphp

    {{-- Imagem principal --}}
    <img
        id="show-img-principal"
        src="{{ $principal ? $principal->url() : asset('img/placeholder.png') }}"
        alt="{{ $receita->titulo }}"
        class="show-header__img"
    >

    {{-- Miniaturas (só aparece se tiver mais de 1 imagem) --}}
    @if($imagens->count() > 1)
        <div class="show-thumbnails">
            @foreach($imagens as $img)
                <button
                    type="button"
                    class="show-thumb {{ $img->principal ? 'show-thumb--ativo' : '' }}"
                    data-url="{{ $img->url() }}"
                    title="Ver imagem"
                >
                    <img src="{{ $img->url() }}" alt="">
                </button>
            @endforeach
        </div>
    @endif
</div>

{{-- CONTEÚDO PRINCIPAL --}}
<div class="show-body">

    {{-- INGREDIENTES --}}
    <div class="show-card">
        <h2 class="show-card__title">🥕 Ingredientes</h2>
        @if($receita->ingredientes->count())
            <ul class="show-ingredientes">
                @foreach($receita->ingredientes as $ingrediente)
                    <li class="show-ingrediente">
                        <span class="show-ingrediente__check">✓</span>
                        <span class="show-ingrediente__nome">{{ $ingrediente->nome }}</span>
                        @if($ingrediente->quantidade)
                            <span class="show-ingrediente__qtd">{{ $ingrediente->quantidade }}</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-muted">Nenhum ingrediente cadastrado.</p>
        @endif
    </div>

    {{-- MODO DE PREPARO --}}
    <div class="show-card">
        <h2 class="show-card__title">👨‍🍳 Modo de Preparo</h2>
        @if($receita->passos->count())
            <ol class="show-passos">
                @foreach($receita->passos as $passo)
                    <li class="show-passo">
                        <div class="show-passo__num">{{ $passo->ordem }}</div>
                        <p class="show-passo__texto">{{ $passo->descricao }}</p>
                    </li>
                @endforeach
            </ol>
        @else
            <p class="text-muted">Nenhum passo cadastrado.</p>
        @endif
    </div>

{{-- VÍDEO --}}
@php
    $embedUrl = \App\Helpers\YoutubeHelper::toEmbed($receita->endereco_video);
@endphp

@if($embedUrl)
    <div class="show-card show-card--video">
        <h2 class="show-card__title">🎬 Vídeo da Receita</h2>
        <div class="show-video-wrap">
            <iframe
                src="{{ $embedUrl }}"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen
                title="Vídeo: {{ $receita->titulo }}"
            ></iframe>
        </div>
    </div>
@endif
</div>

{{-- AÇÕES DO DONO --}}
@auth
    @if(auth()->id() === $receita->user_id)
        <div class="show-owner-actions">
            <a href="{{ route('receitas.edit', $receita) }}" class="btn-hero btn-hero--outline">
                ✏️ Editar receita
            </a>
            <form action="{{ route('receitas.destroy', $receita) }}" method="POST"
                onsubmit="return confirm('Deletar esta receita?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-hero btn-hero--danger">
                    🗑️ Deletar
                </button>
            </form>
        </div>
    @endif
@endauth

<div class="mt-4">
    <a href="{{ route('receitas.index') }}" class="btn-hero btn-hero--outline">
        ← Voltar para receitas
    </a>
</div>

@endsection

@push('scripts')
<script>
(function () {
    const principal = document.getElementById('show-img-principal');
    const thumbs    = document.querySelectorAll('.show-thumb');

    thumbs.forEach(thumb => {
        thumb.addEventListener('click', function () {
            // Atualiza a imagem principal com fade
            principal.style.opacity = '0';
            setTimeout(() => {
                principal.src = this.dataset.url;
                principal.style.opacity = '1';
            }, 150);

            // Atualiza o estado ativo das miniaturas
            thumbs.forEach(t => t.classList.remove('show-thumb--ativo'));
            this.classList.add('show-thumb--ativo');
        });
    });

    // Adiciona transição suave à imagem principal
    principal.style.transition = 'opacity 0.15s ease';
})();
</script>
@endpush

@push('styles')
<style>
:root {
    --laranja:      #e85d2f;
    --laranja-dark: #c44d22;
    --ciano:        #8df7e7;
    --ciano-light:  #e8fdfb;
    --text-dark:    #1a1a2e;
    --text-muted:   #6b7280;
}

/* HEADER */
.show-header {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    margin-bottom: 2rem;
}

.show-header__img-wrap {
    height: 420px;
    overflow: hidden;
    background: var(--ciano-light);
}

.show-header__img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.show-header__img-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 6rem;
    background: linear-gradient(135deg, var(--ciano-light), var(--ciano));
}

.show-header__info {
    padding: 2rem 2rem 2rem 0;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.show-header__badges { display: flex; gap: 0.5rem; }

.show-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
}

.show-badge--fácil   { background: #dcfce7; color: #16a34a; }
.show-badge--médio   { background: #fef9c3; color: #ca8a04; }
.show-badge--difícil { background: #fee2e2; color: #dc2626; }

.show-header__titulo {
    font-size: 1.75rem;
    font-weight: 800;
    color: var(--text-dark);
    line-height: 1.2;
    margin: 0;
}

.show-header__descricao {
    color: var(--text-muted);
    font-size: 0.95rem;
    line-height: 1.6;
    margin: 0;
}

/* META */
.show-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
}

.show-meta__item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--ciano-light);
    border: 1px solid var(--ciano);
    border-radius: 10px;
    padding: 0.5rem 0.75rem;
}

.show-meta__icon { font-size: 1.2rem; }

.show-meta__item small {
    display: block;
    font-size: 0.7rem;
    color: var(--text-muted);
    line-height: 1;
}

.show-meta__item strong {
    display: block;
    font-size: 0.875rem;
    color: var(--text-dark);
}

/* AUTOR */
.show-autor {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding-top: 1rem;
    border-top: 1px solid var(--ciano-light);
    margin-top: auto;
}

.autor-avatar--lg {
    width: 40px;
    height: 40px;
    font-size: 1rem;
    background: var(--ciano);
    color: var(--text-dark);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    flex-shrink: 0;
}

/* CURTIDA GRANDE */
.btn-curtida-lg {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #fff;
    border: 2px solid #e0e0e0;
    border-radius: 25px;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-muted);
    cursor: pointer;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-curtida-lg:hover {
    border-color: var(--laranja);
    color: var(--laranja);
}

.btn-curtida-lg--ativo {
    border-color: var(--laranja);
    color: var(--laranja);
    background: #fff3ee;
}

/* CORPO */
.show-body {
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.show-card {
    background: #fff;
    border-radius: 14px;
    padding: 1.5rem;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
}

.show-card__title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 1.1rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid var(--ciano-light);
}

/* INGREDIENTES */
.show-ingredientes {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.show-ingrediente {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    padding: 0.5rem 0.75rem;
    background: var(--ciano-light);
    border-radius: 8px;
    font-size: 0.9rem;
}

.show-ingrediente__check {
    color: var(--laranja);
    font-weight: 700;
    flex-shrink: 0;
}

.show-ingrediente__nome { flex: 1; color: var(--text-dark); font-weight: 500; }

.show-ingrediente__qtd {
    font-size: 0.8rem;
    color: var(--text-muted);
    background: #fff;
    border-radius: 6px;
    padding: 2px 8px;
    border: 1px solid var(--ciano);
}

/* PASSOS */
.show-passos {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.show-passo {
    display: flex;
    gap: 1rem;
    align-items: flex-start;
}

.show-passo__num {
    min-width: 32px;
    height: 32px;
    background: var(--laranja);
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.85rem;
    flex-shrink: 0;
    margin-top: 2px;
}

.show-passo__texto {
    font-size: 0.925rem;
    color: var(--text-dark);
    line-height: 1.6;
    margin: 0;
}

/* AÇÕES DO DONO */
.show-owner-actions {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
    margin-bottom: 1rem;
    padding: 1rem 1.25rem;
    background: #fff;
    border-radius: 12px;
    border: 1px solid #f0f0f0;
}

.btn-hero--danger {
    background: #fee2e2;
    color: #dc2626;
    border: 2px solid #fca5a5;
}

.btn-hero--danger:hover {
    background: #dc2626;
    color: #fff;
    border-color: #dc2626;
}

/* RESPONSIVO */
@media (max-width: 768px) {
    .show-header { grid-template-columns: 1fr; }
    .show-header__img-wrap { height: 250px; }
    .show-header__info { padding: 1.5rem; }
    .show-body { grid-template-columns: 1fr; }
}

.show-card--video {
    grid-column: 1 / -1; /* ocupa as duas colunas do show-body */
}

.show-video-wrap {
    position: relative;
    padding-bottom: 56.25%;
    height: 0;
    border-radius: 10px;
    overflow: hidden;
    background: #000;
}

.show-video-wrap iframe {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    border: none;
}

/* ── THUMBNAILS ──────────────────────────────────────── */
.show-thumbnails {
    display: flex;
    gap: 0.5rem;
    padding: 0.6rem;
    background: rgba(0,0,0,0.35);
    backdrop-filter: blur(4px);
    overflow-x: auto;
    scrollbar-width: thin;
}

.show-thumb {
    flex-shrink: 0;
    width: 64px;
    height: 48px;
    border-radius: 6px;
    overflow: hidden;
    border: 2px solid transparent;
    padding: 0;
    cursor: pointer;
    transition: border-color 0.2s, transform 0.15s;
    background: none;
}

.show-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.show-thumb:hover           { border-color: #fff; transform: scale(1.05); }
.show-thumb--ativo          { border-color: #e85d2f; }
</style>
@endpush
