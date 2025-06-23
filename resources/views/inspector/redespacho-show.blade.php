@extends('layouts.inspector')

@section('title', 'Detalle Redespacho')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('inspector.index') }}">Inicio</a></li>
    <li class="breadcrumb-item active">Redespacho {{ $redespacho->numero_redespacho }}</li>
@endsection

@section('header')
    <div class="text-center">
        <h2 class="h4 mb-1">
            <i class="bi bi-arrow-repeat text-warning"></i>
            Redespacho {{ $redespacho->numero_redespacho }}
        </h2>
        <p class="text-muted mb-0">{{ $redespacho->fecha->format('d/m/Y') }} - {{ substr($redespacho->hora, 0, 5) }}</p>
    </div>
@endsection

@section('content')
    <!-- Información Principal del Redespacho -->
    <div class="row">
        <div class="col-12">
            <div class="card-inspector mb-3">
                <div class="card-header bg-warning text-dark text-center">
                    <h6 class="mb-0">
                        <i class="bi bi-info-circle"></i>
                        Información del Redespacho
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <div class="p-2 bg-light rounded">
                                <i class="bi bi-calendar text-primary"></i>
                                <div class="small">{{ $redespacho->fecha->format('d/m/Y') }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 bg-light rounded">
                                <i class="bi bi-clock text-info"></i>
                                <div class="small">{{ substr($redespacho->hora, 0, 5) }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mb-2">
                            <small class="text-muted">Destino:</small>
                            <div class="fw-bold">{{ $redespacho->destino }}</div>
                        </div>

                        @if ($redespacho->dominio)
                            <div class="col-6 mb-2">
                                <small class="text-muted">Dominio:</small>
                                <div class="fw-bold">{{ $redespacho->dominio }}</div>
                            </div>
                        @endif

                        @if ($redespacho->habilitacion_destino)
                            <div class="col-6 mb-2">
                                <small class="text-muted">Habilitación:</small>
                                <div class="fw-bold">{{ $redespacho->habilitacion_destino }}</div>
                            </div>
                        @endif

                        <div class="col-12 text-center mt-2">
                            <small class="text-muted">Certificado Sanitario:</small>
                            <div>
                                <span
                                    class="badge {{ $redespacho->certificado_sanitario ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $redespacho->certificado_sanitario ? 'SÍ' : 'NO' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Introducción Original -->
    <div class="row">
        <div class="col-12">
            <div class="card-inspector mb-3">
                <div class="card-header bg-success text-white text-center">
                    <h6 class="mb-0">
                        <i class="bi bi-truck"></i>
                        Introducción Original
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h6 class="text-success">{{ $redespacho->introduccion->numero_remito }}</h6>
                        <small class="text-muted">{{ $redespacho->introduccion->fecha->format('d/m/Y') }}</small>
                    </div>

                    <div class="border rounded p-2 bg-light">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <small class="text-muted">Introductor:</small>
                                <div class="fw-bold">{{ $redespacho->introduccion->introductor->razon_social }}</div>
                                <small
                                    class="text-muted">{{ $redespacho->introduccion->introductor->cuit_formateado }}</small>
                            </div>
                        </div>

                        <div class="text-center mt-2">
                            <a href="{{ route('inspector.introduccion.show', $redespacho->introduccion->id) }}"
                                class="btn btn-sm btn-outline-success">
                                <i class="bi bi-eye me-1"></i>
                                Ver Introducción Original
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Productos Redespachados -->
    @if ($redespacho->productos->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card-inspector mb-3">
                    <div class="card-header bg-primary text-white text-center">
                        <h6 class="mb-0">
                            <i class="bi bi-box"></i>
                            Productos Redespachados
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="small">Producto</th>
                                        <th class="text-center small">Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($redespacho->productos as $item)
                                        <tr>
                                            <td>
                                                <div class="small fw-bold">{{ $item->producto->nombre }}</div>
                                                <div class="text-muted" style="font-size: 0.75rem;">
                                                    {{ $item->producto->categoria }}</div>
                                                @if ($item->observaciones)
                                                    <div class="text-muted" style="font-size: 0.7rem;">
                                                        <i class="bi bi-chat-left-text"></i> {{ $item->observaciones }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-primary small">
                                                    {{ $item->cantidad_secundaria }}
                                                    {{ $item->producto->unidad_secundaria }}
                                                </span>
                                                @if ($item->cantidad_primaria)
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ $item->cantidad_primaria }}
                                                        {{ $item->producto->unidad_primaria }}
                                                    </small>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Observaciones -->
    @if ($redespacho->observaciones)
        <div class="row">
            <div class="col-12">
                <div class="card-inspector mb-3">
                    <div class="card-header bg-secondary text-white text-center">
                        <h6 class="mb-0">
                            <i class="bi bi-chat-left-text"></i>
                            Observaciones
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="small">{{ $redespacho->observaciones }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Información del Sistema -->
    <div class="row">
        <div class="col-12">
            <div class="card-inspector mb-3">
                <div class="card-header bg-light text-dark text-center">
                    <h6 class="mb-0">
                        <i class="bi bi-person-check"></i>
                        Información del Sistema
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <small class="text-muted">Registrado por:</small>
                            <div class="fw-bold">{{ $redespacho->usuario->name ?? 'N/A' }}</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Fecha de registro:</small>
                            <div class="small">{{ $redespacho->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botones de Acción -->
    <div class="row">
        <div class="col-12">
            <div class="d-grid gap-2">
                <a href="{{ route('redespachos.imprimir', $redespacho->id) }}" class="btn btn-inspector-primary"
                    target="_blank">
                    <i class="bi bi-printer me-2"></i>
                    Imprimir Redespacho PDF
                </a>

                <a href="{{ route('inspector.introduccion.show', $redespacho->introduccion->id) }}"
                    class="btn btn-outline-success">
                    <i class="bi bi-arrow-left me-2"></i>
                    Volver a Introducción
                </a>

                <a href="{{ route('inspector.buscar') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-search me-2"></i>
                    Volver a Búsqueda
                </a>

                <a href="{{ route('inspector.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-house me-2"></i>
                    Ir al Inicio
                </a>
            </div>
        </div>
    </div>
@endsection
