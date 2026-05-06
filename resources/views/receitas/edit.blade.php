{{-- resources/views/receitas/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Editar — ' . $receita->titulo)

@section('content')

<div class="edit-wrapper pb-5">

    {{-- ── CABEÇALHO ──────────────────────────────────────── --}}
    <div class="edit-header mb-5">
        <div class="edit-header__left">
            <a href="{{ route('receitas.index') }}" class="edit-back-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                Minhas Receitas
            </a>
            <h1 class="edit-header__title">Editar Receita</h1>
            <p class="edit-header__sub">{{ $receita->titulo }}</p>
        </div>
        <div class="edit-header__right">
            <span class="status-pill status-pill--{{ $receita->status }}">
                <span class="status-dot"></span>
                {{ strtoupper($receita->status) }}
            </span>
        </div>
    </div>

    {{-- ── ALERTAS ─────────────────────────────────────────── --}}
    @if (session('success') || session('success_ingrediente') || session('success_passo'))
        <div class="edit-alert edit-alert--success mb-4">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
            {{ session('success') ?? session('success_ingrediente') ?? session('success_passo') }}
        </div>
    @endif

    <div class="edit-grid">

        {{-- ══════════════════════════════════════════════════ --}}
        {{-- COLUNA ESQUERDA                                    --}}
        {{-- ══════════════════════════════════════════════════ --}}
        <div class="edit-col-main">

            {{-- ── INFORMAÇÕES BÁSICAS ──────────────────────── --}}
            <div class="edit-card mb-4">
                <div class="edit-card__header">
                    <span class="edit-card__icon">📝</span>
                    <h2 class="edit-card__title">Informações Básicas</h2>
                </div>
                <div class="edit-card__body">
                    <form action="{{ route('receitas.update', $receita) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="edit-label" for="titulo">Título <span class="req">*</span></label>
                                <input type="text" id="titulo" name="titulo"
                                    class="edit-input @error('titulo') is-invalid @enderror"
                                    value="{{ old('titulo', $receita->titulo) }}"
                                    placeholder="Ex: Lasanha à Bolonhesa">
                                @error('titulo') <div class="edit-error">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label class="edit-label" for="descricao">Descrição <span class="req">*</span></label>
                                <textarea id="descricao" name="descricao"
                                    class="edit-input edit-textarea @error('descricao') is-invalid @enderror"
                                    rows="3"
                                    placeholder="Uma breve introdução sobre a receita...">{{ old('descricao', $receita->descricao) }}</textarea>
                                @error('descricao') <div class="edit-error">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="edit-label">Tempo de Preparo</label>
                                <div class="edit-input-group">
                                    <span class="edit-input-icon">⏱</span>
                                    <input type="text" name="tempo_preparo"
                                        class="edit-input edit-input--icon"
                                        value="{{ old('tempo_preparo', $receita->tempo_preparo) }}"
                                        placeholder="Ex: 45 min">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="edit-label">Dificuldade</label>
                                <select name="dificuldade" class="edit-select @error('dificuldade') is-invalid @enderror">
                                    <option value="fácil"   {{ old('dificuldade', $receita->dificuldade) == 'fácil'   ? 'selected' : '' }}>🟢 Fácil</option>
                                    <option value="médio"   {{ old('dificuldade', $receita->dificuldade) == 'médio'   ? 'selected' : '' }}>🟡 Médio</option>
                                    <option value="difícil" {{ old('dificuldade', $receita->dificuldade) == 'difícil' ? 'selected' : '' }}>🔴 Difícil</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="edit-label">Custo Médio (R$)</label>
                                <div class="edit-input-group">
                                    <span class="edit-input-icon">R$</span>
                                    <input type="number" name="custo_medio" step="0.01"
                                        class="edit-input edit-input--icon"
                                        value="{{ old('custo_medio', $receita->custo_medio) }}"
                                        placeholder="0,00">
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="edit-label" for="endereco_video">Vídeo do YouTube (URL)</label>
                                <input type="url" id="endereco_video" name="endereco_video"
                                    class="edit-input @error('endereco_video') is-invalid @enderror"
                                    value="{{ old('endereco_video', $receita->endereco_video) }}"
                                    placeholder="https://youtube.com/watch?v=...">
                                @error('endereco_video') <div class="edit-error">{{ $message }}</div> @enderror

                                <div id="video-preview" class="video-preview-wrap mt-3" style="display:none">
                                    <div class="video-preview-ratio">
                                        <iframe id="video-iframe" src="" frameborder="0" allowfullscreen></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ── GALERIA DE IMAGENS ───────────────────────── --}}
            <div class="edit-card mb-4">
                <div class="edit-card__header">
                    <span class="edit-card__icon">🖼️</span>
                    <div>
                        <h2 class="edit-card__title">Galeria de Imagens</h2>
                        <p class="edit-card__sub">Clique em ⭐ para definir a capa. A primeira enviada já é a principal.</p>
                    </div>
                </div>
                <div class="edit-card__body">

                    {{-- Grid de imagens existentes --}}
                    <div id="galeria-grid" class="galeria-grid">
                        @foreach($receita->imagens as $img)
                            <div class="galeria-item {{ $img->principal ? 'galeria-item--principal' : '' }}" id="galeria-item-{{ $img->id }}">
                                <div class="galeria-item__img-wrap">
                                    <img src="{{ $img->url() }}" alt="Imagem da receita" class="galeria-item__img">
                                    @if($img->principal)
                                        <div class="galeria-item__badge">CAPA</div>
                                    @endif
                                </div>
                                <div class="galeria-item__actions">
                                    <button type="button"
                                        class="btn-gal-principal {{ $img->principal ? 'btn-gal-principal--ativo' : '' }}"
                                        data-id="{{ $img->id }}"
                                        data-url="{{ route('receitas.imagens.principal', [$receita, $img]) }}">
                                        {{ $img->principal ? '⭐ Capa' : '☆ Capa' }}
                                    </button>
                                    <button type="button"
                                        class="btn-gal-remove"
                                        data-id="{{ $img->id }}"
                                        data-url="{{ route('receitas.imagens.destroy', [$receita, $img]) }}"
                                        title="Remover imagem">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach

                        {{-- Botão de upload integrado ao grid --}}
                        <div class="galeria-add" id="galeria-add-btn">
                            <input type="file" id="galeria-input" accept="image/jpg,image/jpeg,image/png,image/webp" multiple style="display:none">
                            <div class="galeria-add__inner" id="btn-galeria-upload">
                                <span class="galeria-add__icon">+</span>
                                <span class="galeria-add__label">Adicionar</span>
                                <small class="galeria-add__hint">JPG, PNG, WEBP · 3MB</small>
                            </div>
                        </div>
                    </div>

                    <div id="galeria-erro" class="edit-error mt-2" style="display:none"></div>
                </div>
            </div>

        </div>

        {{-- ══════════════════════════════════════════════════ --}}
        {{-- COLUNA DIREITA                                     --}}
        {{-- ══════════════════════════════════════════════════ --}}
        <div class="edit-col-side">

            {{-- ── PUBLICAÇÃO ───────────────────────────────── --}}
            <div class="edit-card edit-card--accent mb-4">
                <div class="edit-card__header">
                    <span class="edit-card__icon">🚀</span>
                    <h2 class="edit-card__title">Publicação</h2>
                </div>
                <div class="edit-card__body">
                    @if ($receita->estaCompleta())
                        <div class="status-banner status-banner--ok">✅ Pronta para o público!</div>
                    @else
                        <div class="status-banner status-banner--warn">⚠️ Faltam ingredientes ou passos.</div>
                    @endif

                    <div class="d-grid gap-2 mt-3">
                        <button type="submit" class="btn-edit btn-edit--primary">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            Salvar Informações
                        </button>

                        <a href="{{ route('receitas.index') }}" class="btn-edit btn-edit--ghost">Cancelar</a>

                        @if ($receita->estaCompleta() && $receita->status !== 'publicada')
                            <form action="{{ route('receitas.publicar', $receita) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-edit btn-edit--success w-100">🚀 Publicar Agora</button>
                            </form>
                        @endif
                        <a href="{{ route('receitas.show', $receita) }}" class="btn-edit btn-edit--ghost w-100 text-center" target="_blank">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            Visualizar
                        </a>
                    </div>
                </div>
            </div>

            {{-- ── INGREDIENTES ─────────────────────────────── --}}
            <div class="edit-card mb-4">
                <div class="edit-card__header">
                    <span class="edit-card__icon">🥕</span>
                    <h2 class="edit-card__title">Ingredientes</h2>
                </div>
                <div class="edit-card__body">
                    <ul class="side-list mb-3">
                        @forelse ($receita->ingredientes as $ing)
                            <li class="side-list__item">
                                <span class="side-list__text">
                                    <strong>{{ $ing->nome }}</strong>
                                    @if($ing->quantidade)<em class="side-list__qty"> — {{ $ing->quantidade }}</em>@endif
                                </span>
                                <form action="{{ route('receitas.ingredientes.destroy', [$receita, $ing]) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="side-list__remove" title="Remover">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    </button>
                                </form>
                            </li>
                        @empty
                            <li class="side-list__empty">Nenhum ingrediente adicionado.</li>
                        @endforelse
                    </ul>

                    <form action="{{ route('receitas.ingredientes.store', $receita) }}" method="POST">
                        @csrf
                        <div class="row g-1">
                            <div class="col-7">
                                <input type="text" name="nome" class="edit-input edit-input--sm" placeholder="Nome..." required>
                            </div>
                            <div class="col-5">
                                <input type="text" name="quantidade" class="edit-input edit-input--sm" placeholder="Qtd.">
                            </div>
                            <div class="col-12 mt-1">
                                <button class="btn-edit btn-edit--primary btn-edit--sm w-100">+ Adicionar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ── PASSOS ───────────────────────────────────── --}}
            <div class="edit-card">
                <div class="edit-card__header">
                    <span class="edit-card__icon">👨‍🍳</span>
                    <h2 class="edit-card__title">Passo a Passo</h2>
                </div>
                <div class="edit-card__body">
                    <div class="passo-list mb-3">
                        @foreach ($receita->passos->sortBy('ordem') as $p)
                            <div class="passo-item">
                                <span class="passo-num">{{ $loop->iteration }}</span>
                                <p class="passo-text">{{ Str::limit($p->descricao, 70) }}</p>
                                <form action="{{ route('receitas.passos.destroy', [$receita, $p]) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="side-list__remove" title="Remover">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>

                    <form action="{{ route('receitas.passos.store', $receita) }}" method="POST">
                        @csrf
                        <textarea name="descricao" rows="2"
                            class="edit-input edit-textarea edit-input--sm mb-2"
                            placeholder="Descreva o próximo passo..." required></textarea>
                        <button class="btn-edit btn-edit--primary btn-edit--sm w-100">+ Adicionar Passo</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    // ── GALERIA ─────────────────────────────────────────────
    const grid      = document.getElementById('galeria-grid');
    const input     = document.getElementById('galeria-input');
    const btnUpload = document.getElementById('btn-galeria-upload');
    const erroDiv   = document.getElementById('galeria-erro');
    const csrf      = document.querySelector('meta[name="csrf-token"]').content;
    const urlStore  = "{{ route('receitas.imagens.store', $receita) }}";

    btnUpload.addEventListener('click', () => input.click());

    input.addEventListener('change', async function () {
        erroDiv.style.display = 'none';
        for (const file of Array.from(this.files)) {
            await uploadImagem(file);
        }
        this.value = '';
    });

    // Drag & drop
    const addBtn = document.getElementById('galeria-add-btn');
    addBtn.addEventListener('dragover', e => { e.preventDefault(); addBtn.classList.add('galeria-add--drag'); });
    addBtn.addEventListener('dragleave', () => addBtn.classList.remove('galeria-add--drag'));
    addBtn.addEventListener('drop', async e => {
        e.preventDefault();
        addBtn.classList.remove('galeria-add--drag');
        for (const file of Array.from(e.dataTransfer.files)) {
            if (file.type.startsWith('image/')) await uploadImagem(file);
        }
    });

    async function uploadImagem(file) {
        if (file.size > 3 * 1024 * 1024) {
            mostrarErro(`"${file.name}" ultrapassa 3MB.`);
            return;
        }

        const tempId  = 'temp-' + Date.now();
        const tempUrl = URL.createObjectURL(file);
        adicionarCard(tempId, tempUrl, false, true);

        const form = new FormData();
        form.append('imagem', file);
        form.append('_token', csrf);

        try {
            const resp = await fetch(urlStore, { method: 'POST', body: form });
            if (!resp.ok) {
                const data = await resp.json().catch(() => ({}));
                removerCard(tempId);
                mostrarErro(data?.message ?? 'Erro ao enviar imagem.');
                return;
            }

            const data = await resp.json();
            const tempCard = document.getElementById('galeria-item-' + tempId);

            if (tempCard) {
                tempCard.id = 'galeria-item-' + data.id;
                tempCard.querySelector('img').src = data.url;
                tempCard.classList.remove('galeria-item--loading');

                const btnP = tempCard.querySelector('.btn-gal-principal');
                const btnR = tempCard.querySelector('.btn-gal-remove');

                btnP.dataset.id  = data.id;
                btnP.dataset.url = urlStore.replace('/imagens', `/imagens/${data.id}/principal`);
                btnR.dataset.id  = data.id;
                btnR.dataset.url = urlStore.replace('/imagens', `/imagens/${data.id}`);

                if (data.principal) marcarPrincipal(data.id);
            }
        } catch (e) {
            removerCard(tempId);
            mostrarErro('Erro de conexão.');
        }
    }

    // Delegação de eventos
    grid.addEventListener('click', async function (e) {
        const btnP = e.target.closest('.btn-gal-principal');
        const btnR = e.target.closest('.btn-gal-remove');

        if (btnP && !btnP.classList.contains('btn-gal-principal--ativo')) {
            await setPrincipal(btnP.dataset.id, btnP.dataset.url);
        }
        if (btnR) {
            await removerImagem(btnR.dataset.id, btnR.dataset.url);
        }
    });

    async function setPrincipal(id, url) {
        const resp = await fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        });
        if (resp.ok) marcarPrincipal(id);
    }

    async function removerImagem(id, url) {
        if (!confirm('Remover esta imagem?')) return;
        const resp = await fetch(url, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        });
        if (resp.ok) removerCard(id);
    }

    function marcarPrincipal(id) {
        grid.querySelectorAll('.galeria-item').forEach(c => {
            c.classList.remove('galeria-item--principal');
            const badge = c.querySelector('.galeria-item__badge');
            if (badge) badge.remove();
        });
        grid.querySelectorAll('.btn-gal-principal').forEach(btn => {
            btn.classList.remove('btn-gal-principal--ativo');
            btn.textContent = '☆ Capa';
        });

        const card = document.getElementById('galeria-item-' + id);
        if (card) {
            card.classList.add('galeria-item--principal');
            const btn = card.querySelector('.btn-gal-principal');
            btn.classList.add('btn-gal-principal--ativo');
            btn.textContent = '⭐ Capa';

            const imgWrap = card.querySelector('.galeria-item__img-wrap');
            const badge = document.createElement('div');
            badge.className = 'galeria-item__badge';
            badge.textContent = 'CAPA';
            imgWrap.appendChild(badge);
        }
    }

    function adicionarCard(id, url, principal, loading = false) {
        const addCard = document.getElementById('galeria-add-btn');
        const div = document.createElement('div');
        div.className = 'galeria-item' + (loading ? ' galeria-item--loading' : '');
        div.id = 'galeria-item-' + id;
        div.innerHTML = `
            <div class="galeria-item__img-wrap">
                <img src="${url}" alt="Imagem" class="galeria-item__img">
                ${loading ? '<div class="galeria-item__loader"><div class="loader-spin"></div></div>' : ''}
                ${principal ? '<div class="galeria-item__badge">CAPA</div>' : ''}
            </div>
            <div class="galeria-item__actions">
                <button type="button" class="btn-gal-principal ${principal ? 'btn-gal-principal--ativo' : ''}"
                    data-id="${id}" data-url="">${principal ? '⭐ Capa' : '☆ Capa'}</button>
                <button type="button" class="btn-gal-remove" data-id="${id}" data-url="" title="Remover">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>`;
        grid.insertBefore(div, addCard);
    }

    function removerCard(id) {
        document.getElementById('galeria-item-' + id)?.remove();
    }

    function mostrarErro(msg) {
        erroDiv.textContent = msg;
        erroDiv.style.display = 'block';
        setTimeout(() => erroDiv.style.display = 'none', 5000);
    }

    // ── VIDEO PREVIEW ────────────────────────────────────────
    (function () {
        const input   = document.getElementById('endereco_video');
        const preview = document.getElementById('video-preview');
        const iframe  = document.getElementById('video-iframe');

        function extractId(url) {
            const patterns = [/v=([a-zA-Z0-9_-]{11})/, /youtu\.be\/([a-zA-Z0-9_-]{11})/, /embed\/([a-zA-Z0-9_-]{11})/];
            for (const p of patterns) { const m = url.match(p); if (m) return m[1]; }
            return null;
        }

        function update() {
            const id = extractId(input.value.trim());
            if (id) { iframe.src = `https://www.youtube.com/embed/${id}`; preview.style.display = 'block'; }
            else    { preview.style.display = 'none'; iframe.src = ''; }
        }

        input.addEventListener('input', update);
        if (input.value) update();
    })();
})();
</script>
@endpush

@push('styles')
<style>
/* ══════════════════════════════════════════════════════════
   VARIÁVEIS & BASE
══════════════════════════════════════════════════════════ */
:root {
    --orange:        #e85d2f;
    --orange-dark:   #c44d22;
    --orange-light:  #ff7a47;
    --orange-glow:   rgba(232,93,47,.18);
    --black:         #0f0f0f;
    --surface:       #181818;
    --surface2:      #222222;
    --surface3:      #2c2c2c;
    --border:        rgba(255,255,255,.08);
    --border-hover:  rgba(232,93,47,.5);
    --text:          #f0ede8;
    --text-muted:    #888;
    --text-dim:      #555;
    --radius:        12px;
    --radius-sm:     8px;
    --shadow:        0 4px 24px rgba(0,0,0,.4);
    --shadow-orange: 0 0 0 3px var(--orange-glow);
}

/* ── WRAPPER ─────────────────────────────────────────────── */
.edit-wrapper {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
    color: var(--text);
}

/* ── CABEÇALHO ───────────────────────────────────────────── */
.edit-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 1rem;
}

.edit-back-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: var(--text-muted);
    text-decoration: none;
    font-size: .8rem;
    letter-spacing: .05em;
    text-transform: uppercase;
    transition: color .2s;
    margin-bottom: .5rem;
}
.edit-back-link:hover { color: var(--orange); }

.edit-header__title {
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--orange);
    margin: 0;
    line-height: 1.1;
}

.edit-header__sub {
    color: var(--text-muted);
    margin: .25rem 0 0;
    font-size: .9rem;
}

/* ── STATUS PILL ─────────────────────────────────────────── */
.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .08em;
}
.status-dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: currentColor;
    opacity: .7;
}
.status-pill--publicada { background: rgba(22,163,74,.15); color: #4ade80; border: 1px solid rgba(22,163,74,.3); }
.status-pill--rascunho  { background: rgba(234,179,8,.12);  color: #fbbf24; border: 1px solid rgba(234,179,8,.3);  }
.status-pill--pendente  { background: rgba(232,93,47,.12);  color: var(--orange-light); border: 1px solid rgba(232,93,47,.3); }

/* ── ALERTA ──────────────────────────────────────────────── */
.edit-alert {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: .875rem 1.25rem;
    border-radius: var(--radius-sm);
    font-size: .9rem;
    font-weight: 500;
}
.edit-alert--success {
    background: rgba(22,163,74,.1);
    border: 1px solid rgba(22,163,74,.25);
    color: #4ade80;
}

/* ── GRID ────────────────────────────────────────────────── */
.edit-grid {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 1.5rem;
    align-items: start;
}
@media (max-width: 900px) {
    .edit-grid { grid-template-columns: 1fr; }
}

/* ── CARDS ───────────────────────────────────────────────── */
.edit-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow);
}
.edit-card--accent {
    border-top: 3px solid var(--orange);
}

.edit-card__header {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--border);
    background: var(--surface2);
}

.edit-card__icon { font-size: 1.1rem; line-height: 1; }

.edit-card__title {
    font-size: .95rem;
    font-weight: 700;
    color: var(--text);
    margin: 0;
    letter-spacing: -.01em;
}

.edit-card__sub {
    font-size: .75rem;
    color: var(--text-muted);
    margin: 2px 0 0;
}

.edit-card__body { padding: 1.25rem; }

/* ── FORM CONTROLS ───────────────────────────────────────── */
.edit-label {
    display: block;
    font-size: .8rem;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: .06em;
    margin-bottom: .4rem;
}
.req { color: var(--orange); }

.edit-input {
    width: 100%;
    background: var(--surface2);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    color: var(--text);
    padding: .6rem .875rem;
    font-size: .9rem;
    transition: border-color .2s, box-shadow .2s;
    outline: none;
}
.edit-input::placeholder { color: var(--text-dim); }
.edit-input:focus {
    border-color: var(--orange);
    box-shadow: var(--shadow-orange);
}
.edit-input.is-invalid { border-color: #f87171; }

.edit-textarea { resize: vertical; min-height: 80px; font-family: inherit; }
.edit-input--sm { padding: .45rem .75rem; font-size: .85rem; }

.edit-input-group {
    position: relative;
    display: flex;
    align-items: center;
}
.edit-input-icon {
    position: absolute;
    left: .75rem;
    color: var(--text-muted);
    font-size: .85rem;
    pointer-events: none;
    white-space: nowrap;
}
.edit-input--icon { padding-left: 2.5rem; }

.edit-select {
    width: 100%;
    background: var(--surface2);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    color: var(--text);
    padding: .6rem .875rem;
    font-size: .9rem;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23888' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right .875rem center;
    padding-right: 2.5rem;
}
.edit-select:focus {
    border-color: var(--orange);
    box-shadow: var(--shadow-orange);
}

.edit-error {
    font-size: .78rem;
    color: #f87171;
    margin-top: .35rem;
}

.edit-form-actions {
    display: flex;
    justify-content: flex-end;
    gap: .75rem;
}

/* ── BOTÕES ──────────────────────────────────────────────── */
.btn-edit {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: .6rem 1.25rem;
    border-radius: var(--radius-sm);
    font-size: .875rem;
    font-weight: 600;
    border: none;
    cursor: pointer;
    text-decoration: none;
    transition: all .2s;
    white-space: nowrap;
}
.btn-edit--primary {
    background: var(--orange);
    color: #fff;
}
.btn-edit--primary:hover { background: var(--orange-dark); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(232,93,47,.4); color: #fff; }

.btn-edit--ghost {
    background: transparent;
    color: var(--text-muted);
    border: 1px solid var(--border);
}
.btn-edit--ghost:hover { border-color: var(--border-hover); color: var(--text); }

.btn-edit--success {
    background: #16a34a;
    color: #fff;
}
.btn-edit--success:hover { background: #15803d; color: #fff; }

.btn-edit--sm { padding: .45rem .875rem; font-size: .82rem; }
.w-100 { width: 100%; }

/* ── GALERIA ─────────────────────────────────────────────── */
.galeria-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
    gap: .75rem;
}

.galeria-item {
    border-radius: var(--radius-sm);
    border: 2px solid var(--border);
    overflow: hidden;
    background: var(--surface2);
    transition: border-color .2s, transform .15s;
}
.galeria-item:hover { border-color: rgba(255,255,255,.15); }
.galeria-item--principal { border-color: var(--orange) !important; }
.galeria-item--loading { opacity: .55; }

.galeria-item__img-wrap {
    position: relative;
    height: 100px;
    overflow: hidden;
}
.galeria-item__img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform .3s;
}
.galeria-item:hover .galeria-item__img { transform: scale(1.04); }

.galeria-item__badge {
    position: absolute;
    top: 6px; left: 6px;
    background: var(--orange);
    color: #fff;
    font-size: .6rem;
    font-weight: 800;
    letter-spacing: .08em;
    padding: 2px 6px;
    border-radius: 4px;
}

.galeria-item__loader {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0,0,0,.5);
}
.loader-spin {
    width: 22px; height: 22px;
    border: 2px solid rgba(255,255,255,.2);
    border-top-color: var(--orange);
    border-radius: 50%;
    animation: spin .7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

.galeria-item__actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: .3rem .4rem;
    background: var(--surface3);
    gap: .25rem;
}

.btn-gal-principal {
    background: none;
    border: none;
    font-size: .68rem;
    font-weight: 600;
    color: var(--text-dim);
    cursor: pointer;
    padding: 2px 4px;
    border-radius: 4px;
    white-space: nowrap;
    transition: color .15s;
}
.btn-gal-principal:hover, .btn-gal-principal--ativo { color: var(--orange); }

.btn-gal-remove {
    background: none;
    border: none;
    color: var(--text-dim);
    cursor: pointer;
    padding: 2px 5px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    transition: color .15s, background .15s;
    flex-shrink: 0;
}
.btn-gal-remove:hover { color: #f87171; background: rgba(248,113,113,.1); }

/* ── BOTÃO ADICIONAR (grid item) ─────────────────────────── */
.galeria-add {
    border-radius: var(--radius-sm);
    border: 2px dashed var(--border);
    overflow: hidden;
    transition: border-color .2s, background .2s;
    cursor: pointer;
    min-height: 100px;
    display: flex;
}
.galeria-add:hover, .galeria-add--drag {
    border-color: var(--orange);
    background: rgba(232,93,47,.05);
}

.galeria-add__inner {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: .2rem;
    flex: 1;
    padding: 1rem .5rem;
    text-align: center;
    color: var(--text-muted);
}

.galeria-add__icon {
    font-size: 1.5rem;
    line-height: 1;
    color: var(--orange);
    font-weight: 300;
}

.galeria-add__label {
    font-size: .78rem;
    font-weight: 600;
    color: var(--text-muted);
}

.galeria-add__hint {
    font-size: .65rem;
    color: var(--text-dim);
}

/* ── VIDEO PREVIEW ───────────────────────────────────────── */
.video-preview-wrap {
    border-radius: var(--radius-sm);
    overflow: hidden;
    background: #000;
    border: 1px solid var(--border);
}
.video-preview-ratio {
    position: relative;
    padding-bottom: 56.25%;
    height: 0;
}
.video-preview-ratio iframe {
    position: absolute;
    inset: 0;
    width: 100%; height: 100%;
    border: none;
}

/* ── LISTA LATERAL ───────────────────────────────────────── */
.side-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: .35rem;
}
.side-list__item {
    display: flex;
    align-items: center;
    gap: .5rem;
    padding: .4rem .6rem;
    background: var(--surface2);
    border-radius: var(--radius-sm);
    border: 1px solid var(--border);
    font-size: .83rem;
}
.side-list__text { flex: 1; color: var(--text); }
.side-list__qty  { color: var(--text-muted); font-style: normal; }
.side-list__empty { color: var(--text-dim); font-size: .83rem; text-align: center; padding: .5rem 0; }

.side-list__remove {
    background: none;
    border: none;
    color: var(--text-dim);
    cursor: pointer;
    padding: 2px 5px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    flex-shrink: 0;
    transition: color .15s, background .15s;
}
.side-list__remove:hover { color: #f87171; background: rgba(248,113,113,.1); }

/* ── PASSOS ──────────────────────────────────────────────── */
.passo-list { display: flex; flex-direction: column; gap: .4rem; }
.passo-item {
    display: flex;
    align-items: flex-start;
    gap: .6rem;
    padding: .5rem .6rem;
    background: var(--surface2);
    border-radius: var(--radius-sm);
    border: 1px solid var(--border);
}
.passo-num {
    min-width: 22px; height: 22px;
    background: var(--orange);
    color: #fff;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: .7rem; font-weight: 800;
    flex-shrink: 0; margin-top: 1px;
}
.passo-text { flex: 1; font-size: .82rem; color: var(--text-muted); margin: 0; line-height: 1.45; }

/* ── STATUS BANNER ───────────────────────────────────────── */
.status-banner {
    padding: .6rem 1rem;
    border-radius: var(--radius-sm);
    font-size: .83rem;
    font-weight: 600;
}
.status-banner--ok   { background: rgba(22,163,74,.1);  color: #4ade80; border: 1px solid rgba(22,163,74,.2); }
.status-banner--warn { background: rgba(234,179,8,.1);  color: #fbbf24; border: 1px solid rgba(234,179,8,.2); }

/* ── OVERRIDE BOOTSTRAP PARA DARK ───────────────────────── */
.row { --bs-gutter-x: 1rem; }
</style>
@endpush

@endsection
