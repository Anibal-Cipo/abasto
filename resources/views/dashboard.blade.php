@extends('layouts.app')

@section('title', 'Dashboard')

@section('header')
<h1 class="h2"><i class="bi bi-speedometer2"></i> Dashboard</h1>
<div class="btn-toolbar mb-2 mb-md-0">
    <div class="btn-group me-2">
        <button type="button" class="btn btn-sm btn-outline-primary" onclick="location.reload()">
            <i class="bi bi-arrow-clockwise"></i> Actualizar
        </button>
    </div>
</div>
@endsection

@section('content')
<!-- Estadísticas principales -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="bi bi-truck display-4"></i>
                <h3 class="card-title mt-2">{{ $stats['introducciones_hoy'] }}</h3>
                <p class="card-text">Introducciones Hoy</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="bi bi-calendar-month display-4"></i>
                <h3 class="card-title mt-2">{{ $stats['introducciones_mes'] }}</h3>
                <p class="card-text">Este Mes</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="bi bi-arrow-repeat display-4"></i>
                <h3 class="card-title mt-2">{{ $stats['redespachos_hoy'] }}</h3>
                <p class="card-text">Redespachos Hoy</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="bi bi-boxes display-4"></i>
                <h3 class="card-title mt-2">{{ $stats['introducciones_con_stock'] }}</h3>
                <p class="card-text">Con Stock</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Últimas introducciones -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-clock-history"></i> Últimas Introducciones
                </h5>
                <a href="{{ route('introducciones.index') }}" class="btn btn-sm btn-outline-primary">
                    Ver todas
                </a>
            </div>
            <div class="card-body p-0">
                @if($ultimasIntroducciones->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Remito</th>
                                <th>Introductor</th>
                                <th>Fecha/Hora</th>
                                <th>Usuario</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ultimasIntroducciones as $introduccion)
                            <tr>
                                <td>
                                    <strong>{{ $introduccion->numero_remito }}</strong>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $introduccion->introductor->cuit_formateado }}</small><br>
                                    {{ $introduccion->introductor->razon_social }}
                                </td>
                                <td>
                                    {{ $introduccion->fecha_formateada }}<br>
                                   {{ $introduccion->hora_formateada }}
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $introduccion->usuario->name }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('introducciones.show', $introduccion) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <p class="text-muted mt-2">No hay introducciones registradas</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Alertas y top introductores -->
    <div class="col-md-4">
        <!-- Próximas a vencer -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-exclamation-triangle text-warning"></i> Próximas a Vencer
                </h5>
            </div>
            <div class="card-body p-0">
                @if($proximasVencer->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($proximasVencer->take(5) as $introduccion)
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">{{ $introduccion->introductor->razon_social }}</h6>
                            <small class="text-danger">{{ $introduccion->dias_vencimiento }} días</small>
                        </div>
                        <p class="mb-1">Remito: {{ $introduccion->numero_remito }}</p>
                        <small>{{ $introduccion->fecha->format('d/m/Y') }}</small>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-3">
                    <i class="bi bi-check-circle text-success"></i>
                    <p class="text-muted mb-0">Sin alertas de vencimiento</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Top introductores -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-trophy text-warning"></i> Top Introductores del Mes
                </h5>
            </div>
            <div class="card-body p-0">
                @if($topIntroductores->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($topIntroductores as $introductor)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">{{ $introductor->razon_social }}</h6>
                            <small class="text-muted">{{ $introductor->cuit_formateado }}</small>
                        </div>
                        <span class="badge bg-primary rounded-pill">{{ $introductor->introducciones_count }}</span>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-3">
                    <i class="bi bi-graph-up text-info"></i>
                    <p class="text-muted mb-0">Sin datos del mes actual</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection