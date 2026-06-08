<nav class="navbar navbar-expand-lg custom-navbar">
    <div class="container">
        <!-- LOGO -->
        <a class="navbar-brand" href="{{ route('home') }}">
            🍳 𝓟𝓪𝓻𝓬𝓮𝓲𝓻𝓸𝓼 𝓭𝓮 𝓒𝓸𝔃𝓲𝓷𝓱𝓪
        </a>

        <!-- MOBILE -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <!-- MENU ESQUERDA -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active fw-bold' : '' }}"
                        href="{{ route('home') }}">
                        Início
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('receitas.*') ? 'active fw-bold' : '' }}"
                        href="{{ route('receitas.index') }}">
                        Receitas
                    </a>
                </li>
            </ul>

            <!-- MENU DIREITA -->
            <ul class="navbar-nav ms-auto align-items-center">

                <!-- BOTÃO DE DESTAQUE -->
                @auth
                    <li class="nav-item me-2">
                        <a class="btn btn-success" href="{{ route('receitas.create') }}">
                            + Nova Receita
                        </a>
                    </li>
                @endauth

                <!-- USUÁRIO LOGADO -->
                @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('users.show') ? 'active fw-bold' : '' }}"
                            href="{{ route('users.show') }}">
                            Perfil
                        </a>
                    </li>

                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="nav-link btn btn-link text-white" type="submit">
                                Sair
                            </button>
                        </form>
                    </li>
                @endauth

                <!-- VISITANTE -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('login') ? 'active fw-bold' : '' }}"
                            href="{{ route('login') }}">
                            Login
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('users.create') ? 'active fw-bold' : '' }}"
                            href="{{ route('users.create') }}">
                            Cadastrar
                        </a>
                    </li>
                @endguest

            </ul>
        </div>
    </div>
</nav>

<style>
    .custom-navbar {
        background-color: #000;
    }

    .custom-navbar .nav-link,
    .custom-navbar .navbar-brand {
        color: #fff;
    }

    .custom-navbar .nav-link:hover {
        color: #ccc;
    }

    .custom-navbar .nav-link.active {
        border-bottom: 2px solid #ea5c20;
        color: #ea5c20;
    }

    .navbar-toggler {
        border-color: #ea5c20;
        background-color: #ea5c20;
    }

    .navbar-toggler-icon {
        filter: invert(1);
    }
</style>
