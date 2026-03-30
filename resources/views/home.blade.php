@extends('layouts.app')

@section('title', 'Parceiros de Cozinha')

@section('content')

    <!-- HERO -->
    <section class="hero text-white d-flex align-items-center mb-5">
        <div class="container text-center">
            <h1 class="display-4 fw-bold">Descubra receitas incríveis 🍲</h1>
            <p class="lead">Compartilhe, aprenda e cozinhe como um chef!</p>
            <a href="{{ route('receitas.index') }}" class="btn btn-warning btn-lg mt-3">
                Ver Receitas
            </a>
        </div>
    </section>

    <!-- CATEGORIAS -->
    <section class="mb-5">
        <h2 class="mb-4">Categorias</h2>
        <div class="row g-3">

            @foreach (['Massas', 'Doces', 'Carnes', 'Vegano', 'Bebidas', 'Rápidas'] as $categoria)
                <div class="col-md-4 col-lg-2">
                    <div class="card text-center shadow-sm categoria-card">
                        <div class="card-body">
                            <h5>{{ $categoria }}</h5>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </section>

    <!-- RECEITAS -->
    <section class="mb-5">
        <h2 class="mb-4">Receitas em destaque</h2>

        <div class="row g-4">

            @for ($i = 1; $i <= 6; $i++)
                <div class="col-md-6 col-lg-4">
                    <div class="card receita-card h-100 shadow-sm">

                        <img src="https://picsum.photos/400/250?random={{ $i }}" class="card-img-top">

                        <div class="card-body">
                            <h5 class="card-title">Receita deliciosa {{ $i }}</h5>
                            <p class="card-text text-muted">
                                Uma receita incrível para testar na sua cozinha hoje mesmo.
                            </p>
                        </div>

                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <small>⭐ 4.{{ $i }}</small>
                            <small>👨‍🍳 Chef {{ $i }}</small>
                        </div>

                    </div>
                </div>
            @endfor

        </div>
    </section>

    <!-- COMENTÁRIOS -->
    <section class="mb-5">
        <h2 class="mb-4">O que estão dizendo</h2>

        <div class="row g-4">

            @for ($i = 1; $i <= 3; $i++)
                <div class="col-md-4">
                    <div class="card shadow-sm comentario-card h-100">
                        <div class="card-body">
                            <p class="card-text">
                                "Essa receita mudou minha vida! Muito fácil e deliciosa 😍"
                            </p>
                        </div>
                        <div class="card-footer">
                            <strong>Usuário {{ $i }}</strong>
                        </div>
                    </div>
                </div>
            @endfor

        </div>
    </section>

@endsection


<!-- ESTILOS -->
<style>
    /* HERO */
    .hero {
        height: 350px;
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
            url('https://picsum.photos/1200/400?food') center/cover;
        border-radius: 10px;
    }

    /* CATEGORIAS */
    .categoria-card {
        cursor: pointer;
        transition: 0.3s;
    }

    .categoria-card:hover {
        background-color: #ea5c20;
        color: white;
        transform: translateY(-5px);
    }

    /* RECEITAS */
    .receita-card img {
        height: 200px;
        object-fit: cover;
    }

    .receita-card {
        transition: 0.3s;
    }

    .receita-card:hover {
        transform: scale(1.02);
    }

    /* COMENTÁRIOS */
    .comentario-card {
        border-left: 5px solid #ea5c20;
    }
</style>
