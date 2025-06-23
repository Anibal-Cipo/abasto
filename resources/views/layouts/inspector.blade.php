<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Inspector') - {{ config('app.name') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

    <!-- Custom Inspector Styles -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .inspector-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .inspector-nav {
            background: white;
            border-bottom: 3px solid #28a745;
            padding: 0.5rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .nav-inspector {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }

        .nav-inspector .nav-link {
            color: #495057;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-inspector .nav-link:hover {
            background-color: #e8f5e8;
            color: #28a745;
            transform: translateY(-2px);
        }

        .nav-inspector .nav-link.active {
            background-color: #28a745;
            color: white;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }

        .inspector-content {
            min-height: calc(100vh - 200px);
            padding: 1rem 0;
        }

        .btn-inspector-primary {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            border-radius: 15px;
            padding: 1rem 2rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }

        .btn-inspector-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
            color: white;
        }

        .btn-inspector-info {
            background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
            border: none;
            border-radius: 15px;
            padding: 1rem 2rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
        }

        .btn-inspector-info:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(23, 162, 184, 0.4);
            color: white;
        }

        .card-inspector {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card-inspector:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .stats-card-inspector {
            text-align: center;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-radius: 15px;
            color: white;
            font-weight: 600;
        }

        .stats-today {
            background: linear-gradient(135deg, #007bff 0%, #6610f2 100%);
        }

        .stats-stock {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .stats-active {
            background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
        }

        .footer-inspector {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 1rem 0;
            margin-top: 2rem;
        }

        /* Mobile optimizations */
        @media (max-width: 768px) {
            .nav-inspector {
                flex-direction: column;
                gap: 0.5rem;
            }

            .nav-inspector .nav-link {
                text-align: center;
                margin: 0 1rem;
            }

            .inspector-content {
                padding: 0.5rem 0;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Header del Inspector -->
    <header class="inspector-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col">
                    <h1 class="h3 mb-0">
                        <i class="bi bi-search me-2"></i>
                        <span class="d-none d-md-inline">Sistema de Inspección</span>
                        <span class="d-md-none">Inspector</span>
                    </h1>
                    <small class="opacity-75">{{ auth()->user()->name }}</small>
                </div>
                <div class="col-auto">
                    <div class="dropdown">
                        <button class="btn btn-outline-light btn-sm dropdown-toggle" type="button"
                            data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="#"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                                </a>
                            </li>
                        </ul>
                    </div>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Navegación del Inspector -->
    <nav class="inspector-nav">
        <div class="container">
            <div class="nav-inspector">
                <a href="{{ route('inspector.index') }}"
                    class="nav-link {{ request()->routeIs('inspector.index') ? 'active' : '' }}">
                    <i class="bi bi-house"></i>
                    <span class="d-none d-sm-inline">Inicio</span>
                </a>
                <a href="{{ route('inspector.buscar') }}"
                    class="nav-link {{ request()->routeIs('inspector.buscar*') ? 'active' : '' }}">
                    <i class="bi bi-search"></i>
                    Buscar Introductor
                </a>
                <a href="{{ route('inspector.qr.scanner') }}"
                    class="nav-link {{ request()->routeIs('inspector.qr.scanner') ? 'active' : '' }}">
                    <i class="bi bi-qr-code-scan"></i>
                    Escanear QR
                </a>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <main class="inspector-content">
        <div class="container">
            <!-- Breadcrumb (opcional) -->
            @hasSection('breadcrumb')
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb">
                        @yield('breadcrumb')
                    </ol>
                </nav>
            @endif

            <!-- Header de la página -->
            @hasSection('header')
                <div class="mb-4">
                    @yield('header')
                </div>
            @endif

            <!-- Mensajes de alerta -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Por favor corrige los siguientes errores:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Contenido de la página -->
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer-inspector">
        <div class="container">
            <small>
                <i class="bi bi-shield-check me-1"></i>
                Municipalidad de Cipolletti - Sistema de Inspección
                <span class="d-none d-md-inline">| Departamento de Abasto</span>
            </small>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Scripts adicionales -->
    @stack('scripts')

    <!-- Auto-refresh cada 5 minutos -->
    <script>
        // Auto-refresh para mantener datos actualizados
        setTimeout(function() {
            if (window.location.pathname === '/inspector') {
                location.reload();
            }
        }, 300000);

        // Detector de conexión
        window.addEventListener('online', function() {
            console.log('Conexión restaurada');
        });

        window.addEventListener('offline', function() {
            console.log('Sin conexión');
        });
    </script>
</body>

</html>
