@extends('layouts.inspector')

@section('title', 'Dashboard')

@section('header')
    <div class="text-center">
        <h2 class="h4 mb-1">Dashboard del Inspector</h2>
        <p class="text-muted mb-0">Estadísticas del sistema en tiempo real</p>
    </div>
@endsection

@push('styles')
    <style>
        /* Estilos específicos para móvil */
        @media (max-width: 768px) {
            .btn-mobile {
                padding: 1rem;
                margin-bottom: 1rem;
                font-size: 1.1rem;
                border-radius: 10px;
            }

            .stats-card-mobile {
                padding: 1rem;
                margin-bottom: 0.5rem;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .icon-large {
                font-size: 2.5rem !important;
            }
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }
    </style>
@endpush

@section('content')
    <!-- Estadísticas rápidas -->
    <div class="row mb-4">
        <div class="col-4">
            <div class="card-inspector stats-card-inspector stats-today">
                <i class="bi bi-truck" style="font-size: 2rem;"></i>
                <h3 class="mb-0 mt-2">{{ $stats['introducciones_hoy'] }}</h3>
                <small>Hoy</small>
            </div>
        </div>
        <div class="col-4">
            <div class="card-inspector stats-card-inspector stats-stock">
                <i class="bi bi-boxes" style="font-size: 2rem;"></i>
                <h3 class="mb-0 mt-2">{{ $stats['introducciones_con_stock'] }}</h3>
                <small>Con Stock</small>
            </div>
        </div>
        <div class="col-4">
            <div class="card-inspector stats-card-inspector stats-active">
                <i class="bi bi-people" style="font-size: 2rem;"></i>
                <h3 class="mb-0 mt-2">{{ $stats['introductores_activos'] }}</h3>
                <small>Activos</small>
            </div>
        </div>
    </div>

    <!-- Botones principales optimizados para móvil -->
    <div class="row g-3">
        <div class="col-12">
            <div class="card-inspector">
                <div class="card-body text-center p-4">
                    <i class="bi bi-search text-success mb-3" style="font-size: 3rem;"></i>
                    <h4 class="card-title mb-3">Buscar Introductor</h4>
                    <p class="text-muted mb-4">
                        Busca por CUIT o razón social para ver las últimas introducciones y stock disponible.
                    </p>
                    <a href="{{ route('inspector.buscar') }}" class="btn btn-inspector-primary w-100">
                        <i class="bi bi-search me-2"></i>
                        Iniciar Búsqueda
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-2">
        <div class="col-12">
            <div class="card-inspector">
                <div class="card-body text-center p-4">
                    <i class="bi bi-qr-code-scan text-info mb-3" style="font-size: 3rem;"></i>
                    <h4 class="card-title mb-3">Escanear Código QR</h4>
                    <p class="text-muted mb-4">
                        Escanea el QR de una introducción para obtener información instantánea del stock.
                    </p>
                    <a href="{{ route('inspector.qr.scanner') }}" class="btn btn-inspector-info w-100">
                        <i class="bi bi-qr-code-scan me-2"></i>
                        Abrir Scanner
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Instrucciones rápidas -->
    <div class="row g-3 mt-3">
        <div class="col-12">
            <div class="card-inspector">
                <div class="card-header bg-light">
                    <h6 class="mb-0 text-center">
                        <i class="bi bi-lightbulb text-warning"></i>
                        Guía Rápida
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <i class="bi bi-search text-success mb-2" style="font-size: 2rem;"></i>
                            <h6>Búsqueda</h6>
                            <ul class="list-unstyled small text-muted">
                                <li>✓ CUIT completo o parcial</li>
                                <li>✓ Nombre del frigorífico</li>
                                <li>✓ Últimas 5 introducciones</li>
                            </ul>
                        </div>
                        <div class="col-6">
                            <i class="bi bi-qr-code-scan text-info mb-2" style="font-size: 2rem;"></i>
                            <h6>Scanner QR</h6>
                            <ul class="list-unstyled small text-muted">
                                <li>✓ Información instantánea</li>
                                <li>✓ Stock disponible</li>
                                <li>✓ Detalles completos</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Información del sistema -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card-inspector">
                <div class="card-body py-2">
                    <div class="row align-items-center text-center">
                        <div class="col">
                            <small class="text-muted">
                                <i class="bi bi-clock"></i>
                                Actualizado: {{ now()->format('H:i:s') }}
                            </small>
                        </div>
                        <div class="col-auto">
                            <span class="badge bg-success">
                                <i class="bi bi-wifi"></i> Online
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Auto-refresh cada 5 minutos
        setTimeout(function() {
            location.reload();
        }, 300000);

        // Detectar si está offline
        window.addEventListener('offline', function() {
            document.querySelector('.badge.bg-success').className = 'badge bg-danger';
            document.querySelector('.badge.bg-danger').textContent = 'Offline';
        });

        window.addEventListener('online', function() {
            document.querySelector('.badge.bg-danger').className = 'badge bg-success';
            document.querySelector('.badge.bg-success').textContent = 'Online';
        });
    </script>
@endpush
