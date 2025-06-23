@extends('layouts.app')

@section('title', 'Dashboard de Reportes')

@section('header')
    <div>
        <h1 class="h2"><i class="bi bi-graph-up"></i> Dashboard de Reportes</h1>
        <p class="text-muted mb-0">Métricas y estadísticas del sistema de abasto</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="actualizarDashboard()">
                <i class="bi bi-arrow-clockwise"></i> Actualizar
            </button>
            <div class="btn-group">
                <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-calendar"></i> <span id="periodo-actual">Este Mes</span>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="cambiarPeriodo('hoy', 'Hoy')">Hoy</a></li>
                    <li><a class="dropdown-item" href="#" onclick="cambiarPeriodo('semana', 'Esta Semana')">Esta
                            Semana</a></li>
                    <li><a class="dropdown-item" href="#" onclick="cambiarPeriodo('mes', 'Este Mes')">Este Mes</a>
                    </li>
                    <li><a class="dropdown-item" href="#"
                            onclick="cambiarPeriodo('trimestre', 'Trimestre')">Trimestre</a></li>
                    <li><a class="dropdown-item" href="#" onclick="cambiarPeriodo('año', 'Este Año')">Este Año</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <!-- Métricas Principales -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stats-card border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted mb-2">Introducciones</h6>
                            <h3 class="mb-0" id="total-introducciones">{{ $stats['total_introducciones'] }}</h3>
                            <small class="text-muted">en el período</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-truck display-4 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stats-card border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted mb-2">Total Ingresado</h6>
                            <h3 class="mb-0" id="total-ingresado">{{ number_format($stats['total_ingresado'], 0) }}</h3>
                            <small class="text-muted">kg en el período</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-box-arrow-in-down display-4 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stats-card border-start border-warning border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted mb-2">Total Redespachado</h6>
                            <h3 class="mb-0" id="total-redespachado">{{ number_format($stats['total_redespachado'], 0) }}
                            </h3>
                            <small class="text-muted">kg en el período</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-box-arrow-up display-4 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stats-card border-start border-info border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted mb-2">Consumo Ciudad</h6>
                            <h3 class="mb-0 text-info" id="consumo-ciudad">{{ number_format($stats['consumo_ciudad'], 0) }}
                            </h3>
                            <small class="text-muted">kg consumidos localmente</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-building display-4 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos Principales -->
    <div class="row mb-4">
        <!-- Gráfico de Introducciones vs Redespachos -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-bar-chart"></i> Tendencia de Introducciones vs Redespachos
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="graficoTendencias" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Distribución por Categorías -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-pie-chart"></i> Distribución por Categorías
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="graficoCategorias" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Fila de Gráficos Secundarios -->
    <div class="row mb-4">
        <!-- Top Introductores -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-trophy"></i> Top 5 Introductores
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="graficoIntroductores" height="150"></canvas>
                </div>
            </div>
        </div>

        <!-- Stock por Categoría -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-boxes"></i> Stock Disponible por Categoría
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="graficoStock" height="150"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas y Notificaciones -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-exclamation-triangle"></i> Productos Próximos a Vencer
                    </h6>
                </div>
                <div class="card-body">
                    @if ($proximosVencer->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach ($proximosVencer->take(5) as $producto)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <h6 class="mb-1">{{ $producto['producto_nombre'] }}</h6>
                                        <small class="text-muted">{{ $producto['introductor'] }} -
                                            {{ $producto['fecha_vencimiento'] }}</small>
                                    </div>
                                    <span class="badge bg-warning">{{ $producto['dias_restantes'] }} días</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="bi bi-check-circle text-success display-4"></i>
                            <p class="text-muted mt-2 mb-0">No hay productos próximos a vencer</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-info-circle"></i> Resumen del Día
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <h4 class="text-primary">{{ $stats['introducciones_hoy'] }}</h4>
                            <small class="text-muted">Introducciones</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-warning">{{ $stats['redespachos_hoy'] }}</h4>
                            <small class="text-muted">Redespachos</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-success">{{ $stats['introductores_activos_hoy'] }}</h4>
                            <small class="text-muted">Introductores</small>
                        </div>
                    </div>

                    <hr>

                    <div class="row text-center">
                        <div class="col-6">
                            <h5 class="text-info">{{ number_format($stats['consumo_ciudad_hoy'], 0) }} kg</h5>
                            <small class="text-muted">Consumo local hoy</small>
                        </div>
                        <div class="col-6">
                            @php
                                $porcentajeConsumo =
                                    $stats['total_ingresado_hoy'] > 0
                                        ? ($stats['consumo_ciudad_hoy'] / $stats['total_ingresado_hoy']) * 100
                                        : 0;
                            @endphp
                            <h5 class="text-info">{{ number_format($porcentajeConsumo, 1) }}%</h5>
                            <small class="text-muted">% consumo local</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Actividad Reciente -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clock-history"></i> Actividad Reciente
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Hora</th>
                                    <th>Tipo</th>
                                    <th>Introductor</th>
                                    <th>Producto Principal</th>
                                    <th class="text-end">Cantidad</th>
                                    <th class="text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($actividadReciente as $actividad)
                                    <tr>
                                        <td>
                                            <small class="text-muted">{{ $actividad['hora'] }}</small>
                                        </td>
                                        <td>
                                            @if ($actividad['tipo'] == 'introduccion')
                                                <span class="badge bg-primary">Introducción</span>
                                            @else
                                                <span class="badge bg-warning">Redespacho</span>
                                            @endif
                                        </td>
                                        <td>{{ $actividad['introductor'] }}</td>
                                        <td>{{ $actividad['producto'] }}</td>
                                        <td class="text-end">{{ number_format($actividad['cantidad'], 0) }} kg</td>
                                        <td class="text-center">
                                            @if ($actividad['stock_disponible'] > 0)
                                                <i class="bi bi-check-circle text-success" title="Con stock"></i>
                                            @else
                                                <i class="bi bi-dash-circle text-secondary" title="Sin stock"></i>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            No hay actividad reciente
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .stats-card {
            transition: transform 0.2s;
        }

        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .border-start {
            border-left-width: 4px !important;
        }

        #graficoTendencias {
            max-height: 300px;
        }

        .card-header {
            border-bottom: 2px solid #dee2e6;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let periodoActual = 'mes';
        let charts = {};

        // Configuración de colores
        const colores = {
            primary: '#0d6efd',
            success: '#198754',
            warning: '#ffc107',
            info: '#0dcaf0',
            danger: '#dc3545'
        };

        // Datos del servidor (pasados desde el controlador)
        const datosIniciales = {
            tendencias: @json($datosTendencias),
            categorias: @json($datosCategorias),
            introductores: @json($datosIntroductores),
            stock: @json($datosStock)
        };

        document.addEventListener('DOMContentLoaded', function() {
            inicializarGraficos();
        });

        function inicializarGraficos() {
            // Gráfico de tendencias
            const ctxTendencias = document.getElementById('graficoTendencias').getContext('2d');
            charts.tendencias = new Chart(ctxTendencias, {
                type: 'line',
                data: {
                    labels: datosIniciales.tendencias.labels,
                    datasets: [{
                            label: 'Introducciones (kg)',
                            data: datosIniciales.tendencias.introducciones,
                            borderColor: colores.primary,
                            backgroundColor: colores.primary + '20',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Redespachos (kg)',
                            data: datosIniciales.tendencias.redespachos,
                            borderColor: colores.warning,
                            backgroundColor: colores.warning + '20',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Consumo Ciudad (kg)',
                            data: datosIniciales.tendencias.consumo,
                            borderColor: colores.info,
                            backgroundColor: colores.info + '20',
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString() + ' kg';
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' +
                                        context.parsed.y.toLocaleString() + ' kg';
                                }
                            }
                        }
                    }
                }
            });

            // Gráfico de categorías (dona)
            const ctxCategorias = document.getElementById('graficoCategorias').getContext('2d');
            charts.categorias = new Chart(ctxCategorias, {
                type: 'doughnut',
                data: {
                    labels: datosIniciales.categorias.labels,
                    datasets: [{
                        data: datosIniciales.categorias.valores,
                        backgroundColor: [
                            colores.primary,
                            colores.success,
                            colores.warning,
                            colores.info,
                            colores.danger,
                            '#6f42c1',
                            '#fd7e14'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Gráfico de top introductores
            const ctxIntroductores = document.getElementById('graficoIntroductores').getContext('2d');
            charts.introductores = new Chart(ctxIntroductores, {
                type: 'bar',
                data: {
                    labels: datosIniciales.introductores.labels,
                    datasets: [{
                        label: 'kg introducidos',
                        data: datosIniciales.introductores.valores,
                        backgroundColor: colores.success
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Gráfico de stock
            const ctxStock = document.getElementById('graficoStock').getContext('2d');
            charts.stock = new Chart(ctxStock, {
                type: 'bar',
                data: {
                    labels: datosIniciales.stock.labels,
                    datasets: [{
                        label: 'Stock disponible (kg)',
                        data: datosIniciales.stock.valores,
                        backgroundColor: colores.info
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function cambiarPeriodo(periodo, texto) {
            periodoActual = periodo;
            document.getElementById('periodo-actual').textContent = texto;
            actualizarDashboard();
        }

        function actualizarDashboard() {
            // Mostrar indicador de carga
            const btn = document.querySelector('[onclick="actualizarDashboard()"]');
            const iconoOriginal = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Actualizando...';
            btn.disabled = true;

            // Hacer petición AJAX para actualizar datos
            fetch('/reportes/dashboard-data?periodo=' + periodoActual)
                .then(response => response.json())
                .then(data => {
                    // Actualizar métricas
                    document.getElementById('total-introducciones').textContent = data.stats.total_introducciones;
                    document.getElementById('total-ingresado').textContent = data.stats.total_ingresado
                .toLocaleString();
                    document.getElementById('total-redespachado').textContent = data.stats.total_redespachado
                        .toLocaleString();
                    document.getElementById('consumo-ciudad').textContent = data.stats.consumo_ciudad.toLocaleString();

                    // Actualizar gráficos
                    actualizarGraficos(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al actualizar el dashboard');
                })
                .finally(() => {
                    // Restaurar botón
                    btn.innerHTML = iconoOriginal;
                    btn.disabled = false;
                });
        }

        function actualizarGraficos(data) {
            // Actualizar gráfico de tendencias
            charts.tendencias.data.labels = data.tendencias.labels;
            charts.tendencias.data.datasets[0].data = data.tendencias.introducciones;
            charts.tendencias.data.datasets[1].data = data.tendencias.redespachos;
            charts.tendencias.data.datasets[2].data = data.tendencias.consumo;
            charts.tendencias.update();

            // Actualizar otros gráficos...
            charts.categorias.data.labels = data.categorias.labels;
            charts.categorias.data.datasets[0].data = data.categorias.valores;
            charts.categorias.update();

            charts.introductores.data.labels = data.introductores.labels;
            charts.introductores.data.datasets[0].data = data.introductores.valores;
            charts.introductores.update();

            charts.stock.data.labels = data.stock.labels;
            charts.stock.data.datasets[0].data = data.stock.valores;
            charts.stock.update();
        }
    </script>
@endpush
