<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — Parceiros de Cozinha</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * { font-family: 'Inter', sans-serif; }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f0f0f 0%, #1a1a1a 50%, #0f0f0f 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .auth-wrapper {
            width: 100%;
            max-width: 440px;
        }

        .auth-logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-logo a {
            font-size: 1.6rem;
            font-weight: 700;
            color: #fff;
            text-decoration: none;
        }

        .auth-logo span {
            color: #ea5c20;
        }

        .auth-card {
            background: #fff;
            border-radius: 16px;
            padding: 2.5rem 2rem;
            box-shadow: 0 25px 60px rgba(0,0,0,0.4);
        }

        .auth-card h2 {
            font-size: 1.4rem;
            font-weight: 700;
            color: #111;
            margin-bottom: 0.25rem;
        }

        .auth-card .subtitle {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 1.75rem;
        }

        .form-label {
            font-weight: 500;
            font-size: 0.875rem;
            color: #333;
            margin-bottom: 0.4rem;
        }

        .form-control {
            border-radius: 10px;
            border: 1.5px solid #e0e0e0;
            padding: 0.65rem 1rem;
            font-size: 0.95rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            border-color: #ea5c20;
            box-shadow: 0 0 0 3px rgba(234,92,32,0.15);
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .btn-auth {
            background: #ea5c20;
            border: none;
            color: #fff;
            border-radius: 10px;
            padding: 0.7rem;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            transition: background 0.2s, transform 0.1s;
        }

        .btn-auth:hover {
            background: #c94d17;
            color: #fff;
            transform: translateY(-1px);
        }

        .btn-auth:active { transform: translateY(0); }

        .auth-link {
            color: #ea5c20;
            text-decoration: none;
            font-weight: 500;
        }

        .auth-link:hover {
            color: #c94d17;
            text-decoration: underline;
        }

        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.875rem;
            color: #6c757d;
        }

        .divider {
            border: none;
            border-top: 1px solid #f0f0f0;
            margin: 1.5rem 0;
        }

        .alert-success {
            background: #d1fae5;
            border: 1px solid #6ee7b7;
            color: #065f46;
            border-radius: 10px;
            font-size: 0.9rem;
        }

        .invalid-feedback { font-size: 0.8rem; }

        @media (max-width: 480px) {
            .auth-card { padding: 2rem 1.25rem; }
        }
    </style>
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-logo">
        <a href="{{ route('home') }}">🍳 Parceiros de <span>Cozinha</span></a>
    </div>

    <div class="auth-card">
        @yield('content')
    </div>

    <div class="auth-footer mt-3">
        <a href="{{ route('home') }}" class="auth-link">← Voltar ao início</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
