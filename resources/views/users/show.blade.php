@extends('layouts.app')
@section('title', $user->name . ' — Perfil')

@section('content')

<style>
    /* ─── VARIÁVEIS ─────────────────────────────────────────────── */
    :root {
        --orange:        #FF5C00;
        --orange-light:  #FF8040;
        --orange-pale:   #FFF1EA;
        --orange-border: #FFD4B8;
        --surface:       #FFFFFF;
        --surface-2:     #F9F7F5;
        --text:          #1A1209;
        --text-muted:    #7A6858;
        --radius-lg:     16px;
        --radius-md:     10px;
        --radius-sm:     6px;
        --shadow-card:   0 2px 12px rgba(255,92,0,.08);
        --shadow-hover:  0 6px 24px rgba(255,92,0,.16);
    }

    /* ─── HERO DO PERFIL ────────────────────────────────────────── */
    .profile-hero {
        background: linear-gradient(135deg, #FF5C00 0%, #FF8040 60%, #FFAB70 100%);
        border-radius: var(--radius-lg);
        padding: 2rem;
        color: #fff;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .profile-hero::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 200px; height: 200px;
        border-radius: 50%;
        background: rgba(255,255,255,.08);
    }

    .profile-hero::after {
        content: '';
        position: absolute;
        bottom: -60px; left: 30%;
        width: 280px; height: 280px;
        border-radius: 50%;
        background: rgba(255,255,255,.06);
    }

    .profile-hero-inner {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: flex-start;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    /* ─── AVATAR ────────────────────────────────────────────────── */
    .avatar-wrap {
        position: relative;
        flex-shrink: 0;
    }

    .avatar {
        width: 96px;
        height: 96px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(255,255,255,.6);
        background: rgba(255,255,255,.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        font-weight: 700;
        color: #fff;
        letter-spacing: -.5px;
        overflow: hidden;
    }

    .avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .avatar-edit-btn {
        position: absolute;
        bottom: 2px; right: 2px;
        width: 28px; height: 28px;
        border-radius: 50%;
        background: #fff;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        color: var(--orange);
        box-shadow: 0 1px 4px rgba(0,0,0,.2);
        transition: transform .15s;
    }

    .avatar-edit-btn:hover { transform: scale(1.12); }

    /* ─── INFO DO USUÁRIO ───────────────────────────────────────── */
    .profile-info { flex: 1; min-width: 180px; }

    .profile-name {
        font-size: 1.6rem;
        font-weight: 700;
        margin: 0 0 .2rem;
        letter-spacing: -.4px;
    }

    .profile-email {
        font-size: .85rem;
        opacity: .8;
        margin: 0 0 .75rem;
    }

    .profile-badges {
        display: flex;
        flex-wrap: wrap;
        gap: .4rem;
        margin-bottom: .75rem;
    }

    .badge-pill {
        font-size: .72rem;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 20px;
        background: rgba(255,255,255,.2);
        color: #fff;
        letter-spacing: .3px;
    }

    .badge-pill.public  { background: rgba(255,255,255,.3); }
    .badge-pill.private { background: rgba(0,0,0,.25); }

    .profile-actions {
        display: flex;
        gap: .5rem;
        flex-wrap: wrap;
    }

    .btn-white {
        background: #fff;
        color: var(--orange);
        border: none;
        border-radius: var(--radius-sm);
        padding: .45rem 1rem;
        font-size: .8rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        transition: transform .15s, box-shadow .15s;
    }

    .btn-white:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,.15);
        color: var(--orange);
        text-decoration: none;
    }

    .btn-ghost-white {
        background: rgba(255,255,255,.15);
        color: #fff;
        border: 1.5px solid rgba(255,255,255,.4);
        border-radius: var(--radius-sm);
        padding: .45rem 1rem;
        font-size: .8rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        transition: background .15s;
    }

    .btn-ghost-white:hover {
        background: rgba(255,255,255,.25);
        color: #fff;
        text-decoration: none;
    }

    /* ─── ESTATÍSTICAS ──────────────────────────────────────────── */
    .stats-row {
        display: flex;
        gap: .5rem;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: var(--surface);
        border: 1px solid var(--orange-border);
        border-radius: var(--radius-md);
        padding: .9rem 1.25rem;
        flex: 1;
        min-width: 100px;
        text-align: center;
        box-shadow: var(--shadow-card);
    }

    .stat-num {
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--orange);
        line-height: 1;
        margin-bottom: .2rem;
        letter-spacing: -1px;
    }

    .stat-label {
        font-size: .72rem;
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    /* ─── SECTION HEADERS ───────────────────────────────────────── */
    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding-bottom: .6rem;
        border-bottom: 2px solid var(--orange-pale);
    }

    .section-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--text);
        margin: 0;
        display: flex;
        align-items: center;
        gap: .4rem;
    }

    .section-title::before {
        content: '';
        display: inline-block;
        width: 4px; height: 18px;
        background: var(--orange);
        border-radius: 2px;
    }

    /* ─── CARDS DE RECEITA ──────────────────────────────────────── */
    .receitas-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .receita-card {
        background: var(--surface);
        border: 1px solid #EEE;
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-card);
        transition: transform .2s, box-shadow .2s;
        display: flex;
        flex-direction: column;
    }

    .receita-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-hover);
    }

    .receita-thumb {
        height: 140px;
        background: linear-gradient(135deg, var(--orange-pale) 0%, #FFE4CC 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        position: relative;
    }

    .receita-status-badge {
        position: absolute;
        top: 8px; right: 8px;
        font-size: .65rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 20px;
        letter-spacing: .4px;
        text-transform: uppercase;
    }

    .badge-publicado  { background: #D1FAE5; color: #065F46; }
    .badge-rascunho   { background: #FEF3C7; color: #92400E; }

    .receita-body {
        padding: .9rem 1rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .receita-titulo {
        font-size: .95rem;
        font-weight: 700;
        color: var(--text);
        margin: 0 0 .3rem;
        line-height: 1.3;
    }

    .receita-desc {
        font-size: .78rem;
        color: var(--text-muted);
        margin: 0 0 .75rem;
        line-height: 1.5;
        flex: 1;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .receita-meta {
        display: flex;
        gap: .5rem;
        flex-wrap: wrap;
        margin-bottom: .75rem;
    }

    .meta-chip {
        font-size: .68rem;
        font-weight: 600;
        padding: 2px 7px;
        border-radius: 20px;
        background: var(--orange-pale);
        color: var(--orange);
    }

    .receita-actions {
        display: flex;
        gap: .4rem;
        border-top: 1px solid #F0EEE8;
        padding-top: .7rem;
    }

    .btn-card-edit {
        flex: 1;
        font-size: .75rem;
        font-weight: 600;
        padding: .4rem .75rem;
        border-radius: var(--radius-sm);
        border: 1.5px solid var(--orange);
        background: transparent;
        color: var(--orange);
        text-decoration: none;
        text-align: center;
        transition: background .15s, color .15s;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .3rem;
    }

    .btn-card-edit:hover {
        background: var(--orange);
        color: #fff;
        text-decoration: none;
    }

    .btn-card-delete {
        font-size: .75rem;
        font-weight: 600;
        padding: .4rem .75rem;
        border-radius: var(--radius-sm);
        border: 1.5px solid #EDEBE6;
        background: transparent;
        color: var(--text-muted);
        cursor: pointer;
        transition: border-color .15s, color .15s;
    }

    .btn-card-delete:hover {
        border-color: #E53935;
        color: #E53935;
    }

    /* ─── EMPTY STATE ───────────────────────────────────────────── */
    .empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
        background: var(--surface-2);
        border-radius: var(--radius-lg);
        border: 2px dashed var(--orange-border);
    }

    .empty-state-icon { font-size: 3.5rem; margin-bottom: .75rem; }
    .empty-state h3 { font-size: 1rem; font-weight: 700; color: var(--text); margin-bottom: .35rem; }
    .empty-state p  { font-size: .85rem; color: var(--text-muted); margin-bottom: 1.25rem; }

    .btn-orange {
        background: var(--orange);
        color: #fff;
        border: none;
        border-radius: var(--radius-sm);
        padding: .55rem 1.25rem;
        font-size: .85rem;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        transition: background .15s, transform .15s;
    }

    .btn-orange:hover {
        background: var(--orange-light);
        color: #fff;
        text-decoration: none;
        transform: translateY(-1px);
    }

    /* ─── MODAL DE EDIÇÃO ───────────────────────────────────────── */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(20,10,0,.55);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    .modal-overlay.open { display: flex; }

    .modal-box {
        background: #fff;
        border-radius: var(--radius-lg);
        width: 100%;
        max-width: 440px;
        overflow: hidden;
        box-shadow: 0 24px 60px rgba(0,0,0,.2);
        animation: modalIn .2s ease;
    }

    @keyframes modalIn {
        from { transform: translateY(20px); opacity: 0; }
        to   { transform: translateY(0);    opacity: 1; }
    }

    .modal-header {
        background: linear-gradient(135deg, #FF5C00, #FF8040);
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-header h4 {
        color: #fff;
        font-size: 1rem;
        font-weight: 700;
        margin: 0;
    }

    .modal-close {
        background: rgba(255,255,255,.2);
        border: none;
        color: #fff;
        border-radius: 50%;
        width: 28px; height: 28px;
        cursor: pointer;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-body { padding: 1.5rem; }

    .form-group { margin-bottom: 1rem; }

    .form-group label {
        display: block;
        font-size: .78rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: .5px;
        margin-bottom: .4rem;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: .6rem .85rem;
        border: 1.5px solid #E8E5DF;
        border-radius: var(--radius-sm);
        font-size: .9rem;
        color: var(--text);
        background: var(--surface-2);
        transition: border-color .15s;
        outline: none;
    }

    .form-group input:focus,
    .form-group select:focus {
        border-color: var(--orange);
        background: #fff;
    }

    .modal-footer {
        padding: 0 1.5rem 1.5rem;
        display: flex;
        gap: .5rem;
        justify-content: flex-end;
    }

    .btn-outline-muted {
        background: transparent;
        border: 1.5px solid #DDD;
        color: var(--text-muted);
        border-radius: var(--radius-sm);
        padding: .5rem 1.1rem;
        font-size: .85rem;
        font-weight: 600;
        cursor: pointer;
        transition: border-color .15s;
    }

    .btn-outline-muted:hover { border-color: #BBB; }

    /* ─── AVATAR UPLOAD ─────────────────────────────────────────── */
    .avatar-upload-zone {
        border: 2px dashed var(--orange-border);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: background .15s;
        background: var(--orange-pale);
    }

    .avatar-upload-zone:hover { background: #FFE8D6; }
    .avatar-upload-zone p { font-size: .8rem; color: var(--text-muted); margin: .4rem 0 0; }

    /* ─── TOGGLE VISIBILIDADE ───────────────────────────────────── */
    .toggle-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: .75rem 0;
        border-top: 1px solid #F0EEE8;
    }

    .toggle-label {
        font-size: .85rem;
        font-weight: 600;
        color: var(--text);
    }

    .toggle-sub {
        font-size: .73rem;
        color: var(--text-muted);
        margin-top: 1px;
    }

    .toggle-switch {
        position: relative;
        width: 44px;
        height: 24px;
        flex-shrink: 0;
    }

    .toggle-switch input { display: none; }

    .toggle-switch .slider {
        position: absolute;
        inset: 0;
        background: #DDD;
        border-radius: 24px;
        cursor: pointer;
        transition: background .2s;
    }

    .toggle-switch .slider::before {
        content: '';
        position: absolute;
        width: 18px; height: 18px;
        border-radius: 50%;
        background: #fff;
        top: 3px; left: 3px;
        transition: transform .2s;
        box-shadow: 0 1px 3px rgba(0,0,0,.2);
    }

    .toggle-switch input:checked + .slider { background: var(--orange); }
    .toggle-switch input:checked + .slider::before { transform: translateX(20px); }

    /* ─── RESPONSIVE ────────────────────────────────────────────── */
    @media (max-width: 575px) {
        .profile-hero { padding: 1.25rem; }
        .avatar { width: 72px; height: 72px; font-size: 1.6rem; }
        .profile-name { font-size: 1.2rem; }
        .receitas-grid { grid-template-columns: 1fr; }
        .stats-row { gap: .4rem; }
        .stat-num { font-size: 1.5rem; }
    }

    /* Container da thumb */
.receita-thumb {
    height: 220px;
    background: linear-gradient(135deg, var(--orange-pale) 0%, #FFE4CC 100%);
    position: relative;
    overflow: hidden;
    padding: 0;
}

/* Link ocupa toda área */
.destaque-card__img-link {
    display: block;
    width: 100%;
    height: 100%;
    position: relative;
    text-decoration: none;
    overflow: hidden;
}

/* Imagem ajustada corretamente */
.destaque-card__img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform .25s ease;
}

/* Hover elegante */
.receita-card:hover .destaque-card__img {
    transform: scale(1.05);
}

/* Placeholder */
.destaque-card__img-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    background: linear-gradient(135deg, var(--orange-pale), #FFE4CC);
    color: var(--orange);
}

/* Badge dificuldade */
.destaque-card__dificuldade {
    position: absolute;
    bottom: 10px;
    left: 10px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: .7rem;
    font-weight: 700;
    color: #fff;
    z-index: 2;
    backdrop-filter: blur(4px);
}

.destaque-card__dificuldade--fácil {
    background: rgba(22,163,74,.85);
}

.destaque-card__dificuldade--médio {
    background: rgba(202,138,4,.85);
}

.destaque-card__dificuldade--difícil {
    background: rgba(220,38,38,.85);
}

/* Badge status continua por cima */
.receita-status-badge {
    z-index: 3;
}
</style>

{{-- ── MENSAGENS FLASH ─────────────────────────────────────────────────── --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2 mb-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ── HERO DO PERFIL ──────────────────────────────────────────────────── --}}
<div class="profile-hero">
    <div class="profile-hero-inner">

        {{-- Avatar --}}
        <div class="avatar-wrap">
            <div class="avatar">
                @if ($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}">
                @else
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                @endif
            </div>
            @auth
                @can('update', $user)
                    <button class="avatar-edit-btn" onclick="openModal('avatar-modal')" title="Trocar foto">
                        📷
                    </button>
                @endif
            @endauth
        </div>

        {{-- Info --}}
        <div class="profile-info">
            <h1 class="profile-name">{{ $user->name }}</h1>
            <p class="profile-email">{{ $user->email }}</p>

            <div class="profile-badges">
                <span class="badge-pill {{ $user->perfil_publico ? 'public' : 'private' }}">
                    {{ $user->perfil_publico ? '🌐 Perfil público' : '🔒 Perfil privado' }}
                </span>
                @if ($user->email_verified_at)
                    <span class="badge-pill">✅ Verificado</span>
                @endif
            </div>

            @auth
                @can('update', $user)
                    <div class="profile-actions">
                        <button class="btn-white" onclick="openModal('edit-modal')">
                            ✏️ Editar perfil
                        </button>

                        <a href="{{ route('receitas.create') }}" class="btn-ghost-white">
                            + Nova receita
                        </a>
                    </div>
                @endif
            @endauth
        </div>

    </div>
</div>

{{-- ── ESTATÍSTICAS ────────────────────────────────────────────────────── --}}
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-num">{{ $receitas->count() }}</div>
        <div class="stat-label">Receitas</div>
    </div>
    <div class="stat-card">
        <div class="stat-num">{{ $receitas->where('status', 'publicado')->count() }}</div>
        <div class="stat-label">Publicadas</div>
    </div>
    <div class="stat-card">
        <div class="stat-num">{{ $receitas->where('status', 'rascunho')->count() }}</div>
        <div class="stat-label">Rascunhos</div>
    </div>
    <div class="stat-card">
        <div class="stat-num">
            {{ $receitas->sum(fn($r) => $r->ingredientes->count()) }}
        </div>
        <div class="stat-label">Ingredientes</div>
    </div>
</div>

{{-- ── RECEITAS ────────────────────────────────────────────────────────── --}}
<div class="section-header">
    <h2 class="section-title">Minhas Receitas</h2>
    @auth
        @if (auth()->id() === $user->id)
            <a href="{{ route('receitas.create') }}" class="btn-orange" style="font-size:.78rem; padding:.4rem .9rem;">
                + Nova
            </a>
        @endif
    @endauth
</div>


@php
    $receitasVisiveis = (auth()->check() && auth()->id() === $user->id)
        ? $receitas
        : $receitas->where('status', 'publicado');
@endphp

@if ($receitasVisiveis->isEmpty())
    <div class="empty-state">
        <div class="empty-state-icon">🍳</div>
        <h3>Nenhuma receita ainda</h3>
        <p>Crie sua primeira receita e compartilhe com o mundo!</p>
        @auth
            @if (auth()->id() === $user->id)
                <a href="{{ route('receitas.create') }}" class="btn-orange">
                    🚀 Criar primeira receita
                </a>
            @endif
        @endauth
    </div>
@else
    <div class="receitas-grid">
        @foreach ($receitasVisiveis as $receita)
            <div class="receita-card">
                <div class="receita-thumb">
                    {{-- IMAGEM --}}
                    <div>
                        <a href="{{ route('receitas.show', $receita) }}" class="destaque-card__img-link">
                            @php
                                $imagens = $receita->imagens;
                                $principal = $receita->imagemPrincipal();
                            @endphp

                            @if($principal)
                                <img
                                    src="{{ $principal ? $principal->url() : asset('img/placeholder.png') }}"
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
                    </div>

                    <span class="receita-status-badge {{ $receita->status === 'publicado' ? 'badge-publicado' : 'badge-rascunho' }}">
                        {{ $receita->status === 'publicado' ? 'Publicado' : 'Rascunho' }}
                    </span>
                </div>

                <div class="receita-body">
                    <h3 class="receita-titulo">{{ $receita->titulo }}</h3>
                    <p class="receita-desc">{{ $receita->descricao }}</p>

                    <div class="receita-meta">
                        @if ($receita->tempo_preparo)
                            <span class="meta-chip">⏱ {{ $receita->tempo_preparo }}</span>
                        @endif
                        @if ($receita->dificuldade)
                            <span class="meta-chip">{{ ucfirst($receita->dificuldade) }}</span>
                        @endif
                        @if ($receita->custo_medio)
                            <span class="meta-chip">R$ {{ number_format($receita->custo_medio, 2, ',', '.') }}</span>
                        @endif
                    </div>

                    @auth
                        @if (auth()->id() === $user->id)
                            <div class="receita-actions">
                                <a href="{{ route('receitas.edit', $receita) }}" class="btn-card-edit">
                                    ✏️ Editar
                                </a>
                                <a href="{{ route('receitas.ingredientes.edit', $receita) }}" class="btn-card-edit" style="font-size:.72rem;">
                                    🥕 Ingredientes
                                </a>
                                <form action="{{ route('receitas.destroy', $receita) }}" method="POST"
                                    onsubmit="return confirm('Deletar esta receita?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-card-delete">🗑</button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        @endforeach
    </div>
@endif


{{-- ════════════════════════════════════════════════════════════════════════
    MODAIS  (só renderizados para o dono do perfil)
    ════════════════════════════════════════════════════════════════════════ --}}
@auth
    @if (auth()->id() === $user->id)

    {{-- ── MODAL: EDITAR PERFIL ──────────────────────────────────── --}}
    <div class="modal-overlay" id="edit-modal">
        <div class="modal-box">
            <div class="modal-header">
                <h4>✏️ Editar perfil</h4>
                <button class="modal-close" onclick="closeModal('edit-modal')">✕</button>
            </div>

            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('POST')

                <div class="modal-body">
                    <div class="form-group">
                        <label>Nome</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <small style="color:#E53935; font-size:.75rem;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>E-mail</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <small style="color:#E53935; font-size:.75rem;">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Toggle de visibilidade do perfil --}}
                    <div class="toggle-row">
                        <div>
                            <div class="toggle-label">Perfil público</div>
                            <div class="toggle-sub">Outros usuários podem visitar seu perfil</div>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" name="perfil_publico" value="1"
                                {{ $user->perfil_publico ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-outline-muted" onclick="closeModal('edit-modal')">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-orange">
                        💾 Salvar alterações
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── MODAL: TROCAR AVATAR ───────────────────────────────────── --}}
    <div class="modal-overlay" id="avatar-modal">
        <div class="modal-box">
            <div class="modal-header">
                <h4>📷 Foto de perfil</h4>
                <button class="modal-close" onclick="closeModal('avatar-modal')">✕</button>
            </div>

            <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')

                <div class="modal-body">
                    <label class="avatar-upload-zone" for="avatar-input">
                        <div style="font-size:2rem;">📷</div>
                        <p>Clique para selecionar uma imagem</p>
                        <p style="font-size:.7rem; margin-top:.25rem;">JPG, PNG ou GIF — máx. 2 MB</p>
                    </label>
                    <input type="file" id="avatar-input" name="avatar"
                        accept="image/*" style="display:none;"
                        onchange="previewAvatar(this)">
                    <div id="avatar-preview" style="display:none; margin-top:1rem; text-align:center;">
                        <img id="avatar-preview-img" src="" alt="preview"
                            style="width:80px; height:80px; border-radius:50%; object-fit:cover; border:3px solid var(--orange);">
                    </div>
                    @error('avatar')
                        <small style="color:#E53935; font-size:.75rem; display:block; margin-top:.5rem;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-outline-muted" onclick="closeModal('avatar-modal')">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-orange">
                        ✅ Salvar foto
                    </button>
                </div>
            </form>
        </div>
    </div>

    @endif
@endauth


{{-- ── SCRIPT DOS MODAIS ───────────────────────────────────────────────── --}}
<script>
    function openModal(id)  { document.getElementById(id).classList.add('open'); }
    function closeModal(id) { document.getElementById(id).classList.remove('open'); }

    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) closeModal(this.id);
        });
    });

    function previewAvatar(input) {
        if (!input.files || !input.files[0]) return;
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('avatar-preview-img').src = e.target.result;
            document.getElementById('avatar-preview').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }

    @if ($errors->any())
        @if ($errors->has('name') || $errors->has('email') || $errors->has('perfil_publico'))
            openModal('edit-modal');
        @endif
        @if ($errors->has('avatar'))
            openModal('avatar-modal');
        @endif
    @endif
</script>

@endsection
