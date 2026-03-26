<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>@yield('title')</title>

        <!-- Bootstrap CSS CDN -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    </head>
    <body>
<!-- menu da pagina inicial  -->
<nav class="navbar navbar-expand-lg bg-body-tertiary custom-navbar">
    <div class="container-fluid">
      <a class="navbar-brand text-white" href="#">Navbar</a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav ms-auto"> <!-- aqui -->
          <li class="nav-item">
            <a class="nav-link active text-white" href="index.blade.php">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="index.noticia.blade"></a>
          </li>
           <li class="nav-item">
            <a class="nav-link text-white" href="index.noticia.blade"></a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="#">Pricing</a>
          </li>
          <!--
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-white" href="#" data-bs-toggle="dropdown">
              Dropdown link
            </a> -->
            <!--
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="#">Action</a></li>
              <li><a class="dropdown-item" href="#">Another action</a></li>
              <li><a class="dropdown-item" href="#">Something else here</a></li>
            </ul>
          </li> -->
        </ul>
      </div>
    </div>
  </nav>
  </nav>



  <style>

/* Fundo preto da navbar */
.custom-navbar {
    background-color: #000 !important;
}

/* Cor branca nos links */
.custom-navbar .nav-link,
.custom-navbar .navbar-brand {
    color: #fff !important;
}

/* Hover */
.custom-navbar .nav-link:hover {
    color: #ccc !important;
}

/* Ícone do botão mobile branco */
.navbar-toggler {
    border-color: #fff !important;
}

.navbar-toggler-icon {
    filter: invert(1);
}

</style>


<!--
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('users.index') }}">
                Parceiros da Cozinha
        </div>
    </nav> -->

    <div class="container mt-4">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    </body>
</html>
