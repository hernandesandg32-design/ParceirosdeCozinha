@extends('layouts.app')

@section('title', 'Perfil')

@section('content')

<style>
    .profile-container {
        max-width: 900px;
        margin: auto;
    }

    .profile-card {
        background: #fff;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }

    .profile-header {
        text-align: center;
    }

    .profile-header h2 {
        margin-bottom: 5px;
    }

    .profile-info {
        margin-top: 15px;
    }

    .receitas-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .receita-card {
        background: #fff;
        border-radius: 15px;
        padding: 15px;
        box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        transition: 0.3s;
    }

    .receita-card:hover {
        transform: scale(1.03);
    }

    .receita-card h5 {
        margin-bottom: 10px;
    }

    .receita-card p {
        font-size: 14px;
        color: #555;
    }

    @media (max-width: 600px) {
        .profile-card {
            padding: 15px;
        }
    }
</style>

<div class="profile-container">

    <!-- PERFIL -->
    <div class="profile-card">
        <div class="profile-header">
            <h2>{{ $user->name }}</h2>
            <small>{{ $user->email }}</small>
        </div>

        <div class="profile-info mt-3">
            <p><strong>ID:</strong> {{ $user->id }}</p>
        </div>
    </div>

    <!-- RECEITAS -->
    <h3>🍳 Receitas Publicadas</h3>

    <div class="receitas-grid">
        @forelse($receitas as $receita)
            <div class="receita-card">
                <h5>{{ $receita->titulo }}</h5>
                <p>{{ Str::limit($receita->descricao, 80) }}</p>

                <small>
                    ⏱ {{ $receita->tempo_preparo ?? 'N/A' }} <br>
                    ⭐ {{ ucfirst($receita->dificuldade) }}
                </small>
            </div>
        @empty
            <p>Este usuário ainda não publicou receitas.</p>
        @endforelse
    </div>

    <a href="{{ route('users.index') }}" class="btn btn-secondary mt-4">
        Voltar
    </a>

</div>

@endsection
