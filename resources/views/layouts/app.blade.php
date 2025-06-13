<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistema Abasto') }} - @yield('title', 'Dashboard')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

    <style>
        :root {
            --primary: #A8CF45;
            --primary-dark: #8FB639;
            --primary-light: #C4E055;
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
            --info: #17a2b8;
            --light: #f8f9fa;
            --dark: #343a40;
        }

        .bg-primary {
            background-color: var(--primary) !important;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .text-primary {
            color: var(--primary) !important;
        }

        .border-primary {
            border-color: var(--primary) !important;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }

        .sidebar {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            min-height: calc(100vh - 56px);
        }

        .sidebar .nav-link {
            color: white;
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            margin: 0.125rem 0;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: all 0.15s ease-in-out;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .stats-card {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
        }

        .badge-stock-alto {
            background-color: var(--success);
        }

        .badge-stock-medio {
            background-color: var(--warning);
        }

        .badge-stock-bajo {
            background-color: var(--danger);
        }

        .badge-vencido {
            background-color: var(--danger);
        }

        .badge-por-vencer {
            background-color: var(--warning);
        }

        .table th {
            background-color: var(--light);
            border-top: none;
            font-weight: 600;
        }

        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(168, 207, 69, 0.25);
        }

        .page-link {
            color: var(--primary);
        }

        .page-link:hover {
            color: var(--primary-dark);
            background-color: var(--primary-light);
            border-color: var(--primary-light);
        }

        .page-item.active .page-link {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .alert-success {
            background-color: rgba(168, 207, 69, 0.1);
            border-color: var(--primary);
            color: var(--primary-dark);
        }
    </style>

    @stack('styles')
</head>

<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="bi bi-truck"></i> Sistema Abasto
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                </ul>

                <!-- Usuario -->
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                                <span class="badge bg-light text-dark ms-1">{{ ucfirst(Auth::user()->role) }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-person"></i> Perfil</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                            </a>
                        </li>
                    @endauth
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person"></i> Perfil</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar p-0">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column px-3">
                        <!-- Dashboard -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2 me-2"></i> Dashboard
                            </a>
                        </li>

                        <!-- Introductores -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('introductores.*') ? 'active' : '' }}"
                                href="{{ route('introductores.index') }}">
                                <i class="bi bi-people me-2"></i> Introductores
                            </a>
                        </li>

                        <!-- Introducciones -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('introducciones.*') ? 'active' : '' }}"
                                href="{{ route('introducciones.index') }}">
                                <i class="bi bi-truck me-2"></i> Introducciones
                            </a>
                        </li>

                        <!-- Redespachos -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('redespachos.*') ? 'active' : '' }}"
                                href="{{ route('redespachos.index') }}">
                                <i class="bi bi-arrow-repeat"></i> Redespachos
                            </a>
                        </li>
                        @auth
                            @if (Auth::user()->puedeEditar())
                                <hr class="text-white">

                                <!-- Productos -->
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('productos.*') ? 'active' : '' }}"
                                        href="{{ route('productos.index') }}">
                                        <i class="bi bi-box me-2"></i> Productos
                                    </a>
                                </li>

                                <!-- Reportes -->
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('reportes.*') ? 'active' : '' }}"
                                        href="{{ route('reportes.index') }}">
                                        <i class="bi bi-graph-up me-2"></i> Reportes
                                    </a>
                                </li>
                            @endif
                        @endauth

                        @auth
                            @if (Auth::user()->esAdmin())
                                <hr class="text-white">

                                <!-- Usuarios -->
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}"
                                        href="{{ route('usuarios.index') }}">
                                        <i class="bi bi-person-gear me-2"></i> Usuarios
                                    </a>
                                </li>
                            @endif
                        @endauth

                        @auth
                            @if (Auth::user()->esInspector())
                                <hr class="text-white">

                                <!-- Inspector Tools -->
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('inspector.*') ? 'active' : '' }}"
                                        href="{{ route('inspector.index') }}">
                                        <i class="bi bi-search me-2"></i> Buscar Introductor
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('inspector.qr.scanner') }}">
                                        <i class="bi bi-qr-code-scan me-2"></i> Escanear QR
                                    </a>
                                </li>
                            @endif
                        @endauth
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Breadcrumb -->
                @hasSection('breadcrumb')
                    <nav aria-label="breadcrumb" class="mt-3">
                        <ol class="breadcrumb">
                            @yield('breadcrumb')
                        </ol>
                    </nav>
                @endif

                <!-- Page Header -->
                @hasSection('header')
                    <div
                        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        @yield('header')
                    </div>
                @endif

                <!-- Alerts -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        <strong>Por favor corrige los siguientes errores:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Page Content -->
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <script>
        // Configuración global de DataTables
        $.extend(true, $.fn.dataTable.defaults, {
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
            },
            responsive: true,
            pageLength: 25,
            order: [],
            columnDefs: [{
                targets: 'no-sort',
                orderable: false
            }]
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);

        // CUIT formatting
        function formatCuit(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.length >= 11) {
                value = value.substring(0, 11);
                value = value.replace(/(\d{2})(\d{8})(\d{1})/, '$1-$2-$3');
            }
            input.value = value;
        }

        // Number formatting for inputs
        function formatNumber(input, decimals = 2) {
            let value = parseFloat(input.value);
            if (!isNaN(value)) {
                input.value = value.toFixed(decimals);
            }
        }
    </script>

    @stack('scripts')
</body>

</html>
