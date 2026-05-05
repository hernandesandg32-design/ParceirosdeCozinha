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

{{-- ── GALERIA DE IMAGENS ────────────────────────────────── --}}
<div class="form-card mt-4">
    <div class="form-card-header">
        <h5>🖼️ Imagens da Receita</h5>
        <small class="text-muted">Clique em "⭐ Principal" para definir a capa. A primeira enviada já é a principal.</small>
    </div>

    {{-- Preview das imagens já salvas --}}
    <div id="galeria-grid" class="galeria-grid mb-3">
        @foreach($receita->imagens as $img)
            <div class="galeria-item" id="galeria-item-{{ $img->id }}">
                <img src="{{ $img->url() }}" alt="Imagem da receita" class="galeria-item__img">

                <div class="galeria-item__actions">
                    <button
                        type="button"
                        class="btn-galeria-principal {{ $img->principal ? 'btn-galeria-principal--ativo' : '' }}"
                        data-id="{{ $img->id }}"
                        data-url="{{ route('receitas.imagens.principal', [$receita, $img]) }}"
                        title="Definir como principal"
                    >
                        {{ $img->principal ? '⭐ Principal' : '☆ Principal' }}
                    </button>

                    <button
                        type="button"
                        class="btn-galeria-remover"
                        data-id="{{ $img->id }}"
                        data-url="{{ route('receitas.imagens.destroy', [$receita, $img]) }}"
                        title="Remover"
                    >✕</button>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Upload de nova imagem --}}
    <div class="galeria-upload-area" id="galeria-upload-area">
        <input
            type="file"
            id="galeria-input"
            accept="image/jpg,image/jpeg,image/png,image/webp"
            multiple
            style="display:none"
        >
        <button type="button" class="btn-upload" id="btn-galeria-upload">
            <span style="font-size:1.5rem">📷</span>
            <span>Clique para adicionar imagens</span>
            <small>JPG, PNG, WEBP · máx. 3MB cada</small>
        </button>
    </div>

    <div id="galeria-erro" class="text-danger small mt-2" style="display:none"></div>
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

@push('scripts')
<script>
(function () {
    const grid       = document.getElementById('galeria-grid');
    const input      = document.getElementById('galeria-input');
    const btnUpload  = document.getElementById('btn-galeria-upload');
    const erroDiv    = document.getElementById('galeria-erro');
    const csrfToken  = document.querySelector('meta[name="csrf-token"]').content;

    // Rotas base vindas do PHP
    const urlStore    = "{{ route('receitas.imagens.store', $receita) }}";

    // Abre o seletor de arquivo
    btnUpload.addEventListener('click', () => input.click());

    // Ao selecionar arquivos, envia um a um
    input.addEventListener('change', async function () {
        erroDiv.style.display = 'none';
        const files = Array.from(this.files);

        for (const file of files) {
            await uploadImagem(file);
        }

        this.value = ''; // limpa o input para permitir reselecionar o mesmo arquivo
    });

    async function uploadImagem(file) {
        if (file.size > 3 * 1024 * 1024) {
            mostrarErro(`"${file.name}" ultrapassa 3MB e foi ignorado.`);
            return;
        }

        // Preview otimista enquanto faz upload
        const tempId  = 'temp-' + Date.now();
        const tempUrl = URL.createObjectURL(file);
        adicionarCard(tempId, tempUrl, false, true); // loading=true

        const form = new FormData();
        form.append('imagem', file);
        form.append('_token', csrfToken);

        try {
            const resp = await fetch(urlStore, { method: 'POST', body: form });

            if (!resp.ok) {
                const data = await resp.json().catch(() => ({}));
                removerCard(tempId);
                mostrarErro(data?.message ?? 'Erro ao enviar imagem.');
                return;
            }

            const data = await resp.json();

            // Substitui o card temporário pelo definitivo
            const tempCard = document.getElementById('galeria-item-' + tempId);
            if (tempCard) {
                tempCard.id = 'galeria-item-' + data.id;
                tempCard.querySelector('img').src = data.url;
                tempCard.classList.remove('galeria-item--loading');

                // Atualiza botões com os IDs/urls reais
                const btnPrincipal = tempCard.querySelector('.btn-galeria-principal');
                const btnRemover   = tempCard.querySelector('.btn-galeria-remover');

                btnPrincipal.dataset.id  = data.id;
                btnPrincipal.dataset.url = urlStore.replace('/imagens', `/imagens/${data.id}/principal`);

                btnRemover.dataset.id  = data.id;
                btnRemover.dataset.url = urlStore.replace('/imagens', `/imagens/${data.id}`);

                if (data.principal) marcarPrincipal(data.id);
            }

        } catch (e) {
            removerCard(tempId);
            mostrarErro('Erro de conexão ao enviar a imagem.');
        }
    }

    // Delegação de eventos: principal e remover
    grid.addEventListener('click', async function (e) {
        const btnP = e.target.closest('.btn-galeria-principal');
        const btnR = e.target.closest('.btn-galeria-remover');

        if (btnP && !btnP.classList.contains('btn-galeria-principal--ativo')) {
            await setPrincipal(btnP.dataset.id, btnP.dataset.url);
        }

        if (btnR) {
            await removerImagem(btnR.dataset.id, btnR.dataset.url);
        }
    });

    async function setPrincipal(id, url) {
        const resp = await fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        });
        if (resp.ok) marcarPrincipal(id);
    }

    async function removerImagem(id, url) {
        if (!confirm('Remover esta imagem?')) return;
        const resp = await fetch(url, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        });
        if (resp.ok) removerCard(id);
    }

    function marcarPrincipal(id) {
        // Remove "principal" de todos
        grid.querySelectorAll('.btn-galeria-principal').forEach(btn => {
            btn.classList.remove('btn-galeria-principal--ativo');
            btn.textContent = '☆ Principal';
        });
        // Marca o selecionado
        const card = document.getElementById('galeria-item-' + id);
        if (card) {
            const btn = card.querySelector('.btn-galeria-principal');
            btn.classList.add('btn-galeria-principal--ativo');
            btn.textContent = '⭐ Principal';
        }
    }

    function adicionarCard(id, url, principal, loading = false) {
        const div = document.createElement('div');
        div.className = 'galeria-item' + (loading ? ' galeria-item--loading' : '');
        div.id = 'galeria-item-' + id;
        div.innerHTML = `
            <img src="${url}" alt="Imagem" class="galeria-item__img">
            ${loading ? '<div class="galeria-item__loader">⏳</div>' : ''}
            <div class="galeria-item__actions">
                <button type="button"
                    class="btn-galeria-principal ${principal ? 'btn-galeria-principal--ativo' : ''}"
                    data-id="${id}" data-url=""
                >${principal ? '⭐ Principal' : '☆ Principal'}</button>
                <button type="button"
                    class="btn-galeria-remover"
                    data-id="${id}" data-url=""
                >✕</button>
            </div>
        `;
        grid.appendChild(div);
    }

    function removerCard(id) {
        document.getElementById('galeria-item-' + id)?.remove();
    }

    function mostrarErro(msg) {
        erroDiv.textContent = msg;
        erroDiv.style.display = 'block';
        setTimeout(() => erroDiv.style.display = 'none', 5000);
    }
})();
</script>

@endpush

@push('styles')
<style>
/* ── GALERIA ──────────────────────────────────────────── */
.galeria-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 0.75rem;
}

.galeria-item {
    position: relative;
    border-radius: 10px;
    overflow: hidden;
    border: 2px solid #e0e0e0;
    transition: border-color 0.2s;
    background: #f9f9f9;
}

.galeria-item--loading {
    opacity: 0.6;
}

.galeria-item__img {
    width: 100%;
    height: 120px;
    object-fit: cover;
    display: block;
}

.galeria-item__loader {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -60%);
    font-size: 1.5rem;
}

.galeria-item__actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.3rem 0.4rem;
    background: #fff;
    gap: 0.25rem;
}

.btn-galeria-principal {
    background: none;
    border: none;
    font-size: 0.7rem;
    font-weight: 600;
    color: #999;
    cursor: pointer;
    padding: 2px 4px;
    border-radius: 4px;
    transition: color 0.15s;
    white-space: nowrap;
}

.btn-galeria-principal:hover         { color: #e85d2f; }
.btn-galeria-principal--ativo        { color: #e85d2f; }

.btn-galeria-remover {
    background: none;
    border: none;
    color: #dc3545;
    font-size: 0.8rem;
    cursor: pointer;
    padding: 2px 6px;
    border-radius: 4px;
    transition: background 0.15s;
    flex-shrink: 0;
}

.btn-galeria-remover:hover { background: #fee2e2; }

/* ── UPLOAD AREA ──────────────────────────────────────── */
.galeria-upload-area {
    border: 2px dashed #8df7e7;
    border-radius: 10px;
    background: #e8fdfb;
    transition: border-color 0.2s, background 0.2s;
}

.galeria-upload-area:hover {
    border-color: #e85d2f;
    background: #fff3ee;
}

.btn-upload {
    width: 100%;
    background: none;
    border: none;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.3rem;
    cursor: pointer;
    color: #555;
    font-size: 0.9rem;
}

.btn-upload small { color: #aaa; font-size: 0.78rem; }
</style>
@endpush

@endsection
