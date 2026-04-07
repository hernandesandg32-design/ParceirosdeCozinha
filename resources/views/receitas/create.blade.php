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

    <form action="{{ route('receitas.store') }}" method="POST">
        @csrf

        <div class="row g-3">

            <div class="col-12">
                <label class="form-label">Título da receita <span class="text-danger">*</span></label>
                <input
                    type="text"
                    name="titulo"
                    value="{{ old('titulo') }}"
                    class="form-control @error('titulo') is-invalid @enderror"
                    placeholder="Ex: Bolo de Chocolate da Vovó"
                >
                @error('titulo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label class="form-label">Descrição <span class="text-danger">*</span></label>
                <textarea
                    name="descricao"
                    rows="4"
                    class="form-control @error('descricao') is-invalid @enderror"
                    placeholder="Conte um pouco sobre essa receita..."
                >{{ old('descricao') }}</textarea>
                @error('descricao')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Tempo de preparo</label>
                <input
                    type="text"
                    name="tempo_preparo"
                    value="{{ old('tempo_preparo') }}"
                    class="form-control @error('tempo_preparo') is-invalid @enderror"
                    placeholder="Ex: 45 minutos"
                >
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
                <input
                    type="number"
                    name="custo_medio"
                    value="{{ old('custo_medio') }}"
                    class="form-control @error('custo_medio') is-invalid @enderror"
                    placeholder="Ex: 25.00"
                    step="0.01"
                    min="0"
                >
                @error('custo_medio')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label class="form-label">Link do vídeo</label>
                <input
                    type="url"
                    name="endereco_video"
                    value="{{ old('endereco_video') }}"
                    class="form-control @error('endereco_video') is-invalid @enderror"
                    placeholder="https://youtube.com/..."
                >
                @error('endereco_video')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
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

@endsection
