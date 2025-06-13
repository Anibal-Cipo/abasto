@extends('layouts.app')

@section('title', 'Detalle Introductor')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('introductores.index') }}">Introductores</a></li>
<li class="breadcrumb-item active">{{ $introductor->razon_social }}</li>
@endsection

@section('header')
<div>
    <h1 class="h2">
        <i class="bi bi-building"></i> {{ $introductor->razon_social }}
        @if($introductor->activo)
            <span class="badge bg-success ms-2">Activo</span>
        @else
            <span class="badge bg-secondary ms-2">Inactivo</span>
        @endif
    </h1>
    <p class="text-muted mb-0">CUIT: {{ $introductor->cuit_formateado }}</p>
</div>
<div class="btn-toolbar mb-2 mb-md-0">
    @if(Auth::user()->puedeEditar())
    <div class="btn-group me-2">
        <a href="{{ route('introductores.edit', $introductor) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Editar
        </a>
        <a href="{{ route('introducciones.create') }}?introductor_id={{ $introductor->id }}" 
           class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nueva Introducción
        </a>
    </div>
    @endif
</div>
@endsection

@section('content')
<div class="row">
    <!-- Información del introductor -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-info-circle"></i> Información
                </h5>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-4">Dirección:</dt>
                    <dd class="col-sm-8">{{ $introductor->direccion }}</dd>

                    @if($introductor->telefono)
                    <dt class="col-sm-4">Teléfono:</dt>
                    <dd class="col-sm-8">
                        <i class="bi bi-telephone"></i> {{ $introductor->telefono }}
                    </dd>
                    @endif

                    @if($introductor->email)
                    <dt class="col-sm-4">Email:</dt>
                    <dd class="col-sm-8">
                        <i class="bi bi-envelope"></i> 
                        <a href="mailto:{{ $introductor->email }}">{{ $introductor->email }}</a>
                    </dd>
                    @endif

                    <dt class="col-sm-4">Habilitación:</dt>
                    <dd class="col-sm-8">
                        @if($introductor->habilitacion_municipal)
                            <span class="badge bg-success">{{ $introductor->habilitacion_municipal }}</span>
                        @else
                            <span class="badge bg-warning">Sin habilitación</span>
                        @endif
                    </dd>

                    <dt class="col-sm-4">Registrado:</dt>
                    <dd class="col-sm-8">{{ $introductor->created_at->format('d/m/Y') }}</dd>
                </dl>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-graph-up"></i> Estadísticas
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-primary">{{ $stats['total_introducciones'] }}</h4>
                        <small class="text-muted">Total Introducciones</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-info">{{ $stats['introducciones_mes'] }}</h4>
                        <small class="text-muted">Este Mes</small>
                    </div>
                    <div class="col-6 mt-3">
                        <h4 class="text-success">{{ $stats['con_stock'] }}</h4>
                        <small class="text-muted">Con Stock</small>
                    </div>
                    <div class="col-6 mt-3">
                        @if($stats['ultima_introduccion'])
                        <h6 class="text-warning">{{ $stats['ultima_introduccion']->fecha->diffForHumans() }}</h6>
                        @else
                        <h6 class="text-muted">-</h6>
                        @endif
                        <small class="text-muted">Última Introducción</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Últimas introducciones -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-clock-history"></i> Últimas Introducciones
                </h5>
                <a href="{{ route('introducciones.index') }}?introductor_id={{ $introductor->id }}" 
                   class="btn btn-sm btn-outline-primary">
                    Ver todas
                </a>
            </div>
            <div class="card-body p-0">
                @if($introductor->introduccionesRecientes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Remito</th>
                                <th>Fecha/Hora</th>
                                <th>Productos</th>
                                <th>Stock</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($introductor->introduccionesRecientes as $introduccion)
                            <tr>
                                <td>
                                    <strong>{{ $introduccion->numero_remito }}</strong>
                                    @if($introduccion->qr_code)
                                        <br><small class="text-muted">QR: {{ $introduccion->qr_code }}</small>
                                    @endif
                                </td>
                                <td>
                                    {{ $introduccion->fecha->format('d/m/Y') }}<br>
                                    <small class="text-muted">{{ $introduccion->hora->format('H:i') }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $introduccion->productos->count() }} productos</span>
                                </td>
                                <td>
                                    @php
                                        $tieneStock = $introduccion->redespachos->count() < $introduccion->productos->count();
                                    @endphp
                                    @if($tieneStock)
                                        <span class="badge bg-success">Disponible</span>
                                    @else
                                        <span class="badge bg-secondary">Agotado</span>
                                    @endif
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
                    <i class="bi bi-inbox display-3 text-muted"></i>
                    <h5 class="text-muted mt-2">Sin introducciones registradas</h5>
                    <p class="text-muted">Las introducciones de este operador aparecerán aquí.</p>
                    @if(Auth::user()->puedeEditar())
                    <a href="{{ route('introducciones.create') }}?introductor_id={{ $introductor->id }}" 
                       class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Registrar Primera Introducción
                    </a>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection