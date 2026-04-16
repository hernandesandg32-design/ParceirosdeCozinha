<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* ─── STEPPER ──────────────────────────────────────────────── */
        .stepper {
            display: flex;
            align-items: center;
            gap: 0;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            font-size: 0.78rem;
            color: #999;
            min-width: 90px;
        }

        .step-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 2px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            background: #fff;
            color: #aaa;
            font-size: 0.9rem;
        }

        .step.active .step-circle {
            border-color: #ea5c20;
            color: #ea5c20;
        }

        .step.active span { color: #ea5c20; font-weight: 600; }

        .step.done .step-circle {
            background: #ea5c20;
            border-color: #ea5c20;
            color: #fff;
        }

        .step-line {
            flex: 1;
            height: 2px;
            background: #ddd;
            margin-bottom: 18px;
        }

        .step-line.active { background: #ea5c20; }

        /* ─── FORM CARD ─────────────────────────────────────────────── */
        .form-card {
            background: #fff;
            border-radius: 14px;
            padding: 1.75rem;
            box-shadow: 0 2px 16px rgba(0,0,0,0.07);
            margin-bottom: 1rem;
        }

        .form-card-header {
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .form-card-header h2,
        .form-card-header h5 {
            margin: 0 0 4px;
            font-weight: 700;
            color: #111;
        }

        .form-card-header p,
        .form-card-header small {
            color: #6c757d;
            font-size: 0.875rem;
            margin: 0;
        }

        /* ─── BOTÕES ─────────────────────────────────────────────────── */
        .btn-primary-orange {
            background: #ea5c20;
            border: none;
            color: #fff;
            border-radius: 8px;
            padding: 0.55rem 1.25rem;
            font-weight: 600;
            transition: background 0.2s, transform 0.1s;
        }

        .btn-primary-orange:hover {
            background: #c94d17;
            color: #fff;
            transform: translateY(-1px);
        }

        .btn-success-green {
            background: #16a34a;
            border: none;
            color: #fff;
            border-radius: 8px;
            padding: 0.55rem 1.25rem;
            font-weight: 600;
            transition: background 0.2s;
        }

        .btn-success-green:hover { background: #15803d; color: #fff; }

        /* ─── INGREDIENTES ───────────────────────────────────────────── */
        .ingredient-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .ingredient-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem 0.75rem;
            background: #fafafa;
            border-radius: 8px;
            margin-bottom: 0.4rem;
            font-size: 0.9rem;
            border: 1px solid #f0f0f0;
        }

        /* ─── PASSOS ─────────────────────────────────────────────────── */
        .passo-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .passo-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.6rem 0.75rem;
            background: #fafafa;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            border: 1px solid #f0f0f0;
        }

        .passo-numero {
            min-width: 26px;
            height: 26px;
            background: #ea5c20;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .passo-texto { line-height: 1.5; }

        /* ─── BOTÃO REMOVER ──────────────────────────────────────────── */
        .btn-remove {
            background: none;
            border: none;
            color: #dc3545;
            font-size: 0.85rem;
            padding: 2px 6px;
            border-radius: 4px;
            cursor: pointer;
            flex-shrink: 0;
            transition: background 0.15s;
        }

        .btn-remove:hover { background: #fee2e2; }

        /* ─── BADGES STATUS ──────────────────────────────────────────── */
        .badge-completa {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
            border-radius: 8px;
            padding: 0.4rem 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .badge-pendente {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fcd34d;
            border-radius: 8px;
            padding: 0.4rem 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        /* ─── FORM CONTROLS ──────────────────────────────────────────── */
        .form-control:focus,
        .form-select:focus {
            border-color: #ea5c20;
            box-shadow: 0 0 0 3px rgba(234,92,32,0.15);
        }

        /* ─── RESPONSIVO ─────────────────────────────────────────────── */
        @media (max-width: 576px) {
            .stepper { gap: 0; }
            .step { min-width: 60px; font-size: 0.7rem; }
            .form-card { padding: 1.25rem; }
        }
        </style>

        @stack('styles')
</head>

<body>
    @include('layouts.partials.navbar')

    <div class="container mt-4">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
