@extends('layouts.app')
@section('title', 'Nova Receita')

@section('content')

    {{-- STEPPER --}}
    <div class="stepper mb-4">
        <div class="step active">
            <div class="step-circle">1</div>
            <span>Informações</span>
        </div>
        <div class="step-line"></div>
        <div class="step">
            <div class="step-circle">2</div>
            <span>Ingredientes e Passos</span>
        </div>
        <div class="step-line"></div>
        <div class="step">
            <div class="step-circle">3</div>
            <span>Publicar</span>
        </div>
    </div>

    <div class="form-card">
        <div class="form-card-header">
            <h2>📝 Informações da Receita</h2>
            <p>Preencha os dados básicos da sua receita</p>
        </div>

        <form action="{{ route('receitas.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">

                <div class="col-12">
                    <label class="form-label">Título da receita <span class="text-danger">*</span></label>
                    <input type="text" name="titulo" value="{{ old('titulo') }}"
                        class="form-control @error('titulo') is-invalid @enderror"
                        placeholder="Ex: Bolo de Chocolate da Vovó">
                    @error('titulo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Descrição <span class="text-danger">*</span></label>
                    <textarea name="descricao" rows="4" class="form-control @error('descricao') is-invalid @enderror"
                        placeholder="Conte um pouco sobre essa receita...">{{ old('descricao') }}</textarea>
                    @error('descricao')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tempo de preparo</label>
                    <input type="text" name="tempo_preparo" value="{{ old('tempo_preparo') }}"
                        class="form-control @error('tempo_preparo') is-invalid @enderror" placeholder="Ex: 45 minutos">
                    @error('tempo_preparo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Dificuldade</label>
                    <select name="dificuldade" class="form-select @error('dificuldade') is-invalid @enderror">
                        <option value="">Selecione...</option>
                        @foreach (['fácil', 'médio', 'difícil'] as $nivel)
                            <option value="{{ $nivel }}" {{ old('dificuldade') === $nivel ? 'selected' : '' }}>
                                {{ ucfirst($nivel) }}
                            </option>
                        @endforeach
                    </select>
                    @error('dificuldade')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Custo médio (R$)</label>
                    <input type="number" name="custo_medio" value="{{ old('custo_medio') }}"
                        class="form-control @error('custo_medio') is-invalid @enderror" placeholder="Ex: 25.00"
                        step="0.01" min="0">
                    @error('custo_medio')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Link do vídeo (YouTube)</label>
                    <input type="url" name="endereco_video" id="endereco_video" value="{{ old('endereco_video') }}"
                        class="form-control @error('endereco_video') is-invalid @enderror"
                        placeholder="https://youtube.com/watch?v=... ou https://youtu.be/..." autocomplete="off">

                        @error('endereco_video')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    {{-- Preview do vídeo --}}
                    <div id="video-preview" class="video-preview" style="display:none">
                        <div class="video-preview__label">
                            <span>▶ Preview do vídeo</span>
                            <button type="button" id="btn-fechar-preview" class="video-preview__fechar">✕ Fechar</button>
                        </div>
                        <div class="video-preview__wrap">
                            <iframe id="video-iframe" src="" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Imagem da receita</label>
                    <input type="file" name="imagem" accept="image/jpg,image/jpeg,image/png,image/webp"
                        class="form-control @error('imagem') is-invalid @enderror">
                    <div class="form-text">Formatos aceitos: JPG, PNG, WEBP. Máximo: 2MB.</div>
                    @error('imagem')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

    </div>

    <div class="d-flex justify-content-between align-items-center mt-4">
        <a href="{{ route('receitas.index') }}" class="btn btn-outline-secondary">
            ← Cancelar
        </a>
        <button type="submit" class="btn btn-primary-orange">
            Continuar → Ingredientes
        </button>
    </div>

    </form>
    </div>

    @push('scripts')
        <script>
            (function() {
                const input = document.getElementById('endereco_video');
                const preview = document.getElementById('video-preview');
                const iframe = document.getElementById('video-iframe');
                const btnFechar = document.getElementById('btn-fechar-preview');

                // Extrai o ID do YouTube de qualquer URL
                function extractYoutubeId(url) {
                    const patterns = [
                        /youtube\.com\/watch\?.*v=([a-zA-Z0-9_-]{11})/,
                        /youtu\.be\/([a-zA-Z0-9_-]{11})/,
                        /youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/,
                        /youtube\.com\/shorts\/([a-zA-Z0-9_-]{11})/,
                    ];
                    for (const pattern of patterns) {
                        const match = url.match(pattern);
                        if (match) return match[1];
                    }
                    return null;
                }

                function showPreview(id) {
                    iframe.src = `https://www.youtube.com/embed/${id}`;
                    preview.style.display = 'block';
                }

                function hidePreview() {
                    iframe.src = '';
                    preview.style.display = 'none';
                }

                // Mostrar preview ao digitar (com debounce de 800ms)
                let debounce;
                input.addEventListener('input', function() {
                    clearTimeout(debounce);
                    const url = this.value.trim();

                    if (!url) {
                        hidePreview();
                        return;
                    }

                    debounce = setTimeout(() => {
                        const id = extractYoutubeId(url);
                        if (id) {
                            showPreview(id);
                        } else {
                            hidePreview();
                        }
                    }, 800);
                });

                // Fechar preview manualmente
                btnFechar.addEventListener('click', hidePreview);

                // Se já tem valor (old() após erro de validação), mostra preview
                if (input.value.trim()) {
                    const id = extractYoutubeId(input.value.trim());
                    if (id) showPreview(id);
                }
            })();
        </script>
    @endpush

    @push('styles')
        <style>
            /* ─── VIDEO PREVIEW ─────────────────────────────────────── */
            .video-preview {
                margin-top: 0.75rem;
                border-radius: 12px;
                overflow: hidden;
                border: 1.5px solid #e0e0e0;
                background: #000;
            }

            .video-preview__label {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.5rem 0.75rem;
                background: #1a1a1a;
                color: #fff;
                font-size: 0.8rem;
                font-weight: 600;
            }

            .video-preview__fechar {
                background: none;
                border: none;
                color: #aaa;
                font-size: 0.78rem;
                cursor: pointer;
                padding: 0;
                transition: color 0.15s;
            }

            .video-preview__fechar:hover {
                color: #fff;
            }

            .video-preview__wrap {
                position: relative;
                padding-bottom: 56.25%;
                /* 16:9 */
                height: 0;
                overflow: hidden;
            }

            .video-preview__wrap iframe {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                border: none;
            }
        </style>
    @endpush

@endsection
