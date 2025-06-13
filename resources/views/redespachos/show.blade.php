@extends('layouts.app')

@section('title', 'Detalle Redespacho')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('redespachos.index') }}">Redespachos</a></li>
<li class="breadcrumb-item active">{{ $redespacho->numero_redespacho }}</li>
@endsection

@section('header')
<div>
    <h1 class="h2">
        <i class="bi bi-arrow-repeat"></i> Redespacho {{ $redespacho->numero_redespacho }}
        @if($redespacho->certificado_sanitario)
            <span class="badge bg-success ms-2">Con Cert. Sanitario</span>
        @endif
    </h1>
    <p class="text-muted mb-0">
        {{ $redespacho->fecha->format('d/m/Y') }} {{ substr($redespacho->hora, 0, 5) }} - 
        Destino: {{ $redespacho->destino }}
    </p>
</div>
<div class="btn-toolbar mb-2 mb-md-0">
    <!-- AQUÍ AGREGAMOS LOS BOTONES DE IMPRESIÓN -->
    <div class="btn-group me-2" role="group">
        <a href="{{ route('redespachos.imprimir', $redespacho->id) }}" 
           class="btn btn-primary" 
           target="_blank" 
           title="Ver PDF en navegador">
            <i class="bi bi-eye"></i> Ver PDF
        </a>
        <a href="{{ route('redespachos.descargar', $redespacho->id) }}" 
           class="btn btn-outline-primary" 
           title="Descargar PDF">
            <i class="bi bi-download"></i> Descargar PDF
        </a>
    </div>
    
    <div class="btn-group me-2">
        @if(Auth::user()->puedeEditar())
        <form action="{{ route('redespachos.destroy', $redespacho) }}" method="POST" 
              style="display: inline;" onsubmit="return confirm('¿Estás seguro de eliminar este redespacho?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-trash"></i> Eliminar
            </button>
        </form>
        @endif
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <!-- Información principal -->
    <div class="col-md-8">
        <!-- Datos del redespacho -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-info-circle"></i> Información del Redespacho
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-5">N° Redespacho:</dt>
                            <dd class="col-sm-7"><strong>{{ $redespacho->numero_redespacho }}</strong></dd>

                            <dt class="col-sm-5">Fecha/Hora:</dt>
                            <dd class="col-sm-7">{{ $redespacho->fecha->format('d/m/Y') }} {{ substr($redespacho->hora, 0, 5) }}</dd>

                            <dt class="col-sm-5">Destino:</dt>
                            <dd class="col-sm-7">{{ $redespacho->destino }}</dd>

                            @if($redespacho->dominio)
                            <dt class="col-sm-5">Dominio:</dt>
                            <dd class="col-sm-7"><strong>{{ $redespacho->dominio }}</strong></dd>
                            @endif
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <dl class="row">
                            @if($redespacho->habilitacion_destino)
                            <dt class="col-sm-5">Habilitación:</dt>
                            <dd class="col-sm-7">{{ $redespacho->habilitacion_destino }}</dd>
                            @endif

                            <dt class="col-sm-5">Cert. Sanitario:</dt>
                            <dd class="col-sm-7">
                                @if($redespacho->certificado_sanitario)
                                    <span class="badge bg-success">Sí</span>
                                @else
                                    <span class="badge bg-secondary">No</span>
                                @endif
                            </dd>

                            <dt class="col-sm-5">Registrado por:</dt>
                            <dd class="col-sm-7">
                                <span class="badge bg-info">{{ $redespacho->usuario->name }}</span>
                            </dd>
                        </dl>
                    </div>
                </div>

                @if($redespacho->observaciones)
                <hr>
                <dl class="row">
                    <dt class="col-sm-2">Observaciones:</dt>
                    <dd class="col-sm-10">{{ $redespacho->observaciones }}</dd>
                </dl>
                @endif
            </div>
        </div>

        <!-- Introducción base -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-truck"></i> Introducción Base
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-5">N° Remito:</dt>
                            <dd class="col-sm-7">
                                <a href="{{ route('introducciones.show', $redespacho->introduccion) }}" class="text-decoration-none">
                                    <strong>{{ $redespacho->introduccion->numero_remito }}</strong>
                                    <i class="bi bi-box-arrow-up-right ms-1"></i>
                                </a>
                            </dd>

                            <dt class="col-sm-5">Introductor:</dt>
                            <dd class="col-sm-7">{{ $redespacho->introduccion->introductor->razon_social }}</dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-5">Fecha Intro:</dt>
                            <dd class="col-sm-7">{{ $redespacho->introduccion->fecha->format('d/m/Y') }}</dd>

                            <dt class="col-sm-5">CUIT:</dt>
                            <dd class="col-sm-7">{{ $redespacho->introduccion->introductor->cuit_formateado }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Productos redespachados -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-box"></i> Productos Redespachados
                </h5>
            </div>
            <div class="card-body p-0">
                @if($redespacho->productos->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th class="text-center">Cantidad</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($redespacho->productos as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->producto->nombre }}</strong><br>
                                    <small class="text-muted">{{ $item->producto->categoria }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary">
                                        {{ number_format($item->cantidad_secundaria, 2) }} 
                                        {{ $item->producto->unidad_secundaria }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $item->observaciones ?: '-' }}</small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="p-4 text-center text-muted">
                    <i class="bi bi-box display-4"></i>
                    <p class="mt-2">No hay productos registrados</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-md-4">
        <!-- CARD DE IMPRESIÓN - NUEVA SECCIÓN -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-printer"></i> Impresión
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('redespachos.imprimir', $redespacho->id) }}" 
                       class="btn btn-primary" 
                       target="_blank">
                        <i class="bi bi-eye"></i> Ver PDF en navegador
                    </a>
                    
                    <a href="{{ route('redespachos.descargar', $redespacho->id) }}" 
                       class="btn btn-outline-primary">
                        <i class="bi bi-download"></i> Descargar PDF
                    </a>
                </div>
                
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="bi bi-info-circle"></i> 
                        El PDF incluye toda la información del redespacho y la trazabilidad con la introducción original.
                    </small>
                </div>
            </div>
        </div>

        <!-- Información adicional -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-clock-history"></i> Información Adicional
                </h5>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-6">Creado:</dt>
                    <dd class="col-sm-6">
                        {{ $redespacho->created_at->format('d/m/Y H:i') }}
                    </dd>

                    @if($redespacho->updated_at->ne($redespacho->created_at))
                    <dt class="col-sm-6">Modificado:</dt>
                    <dd class="col-sm-6">
                        {{ $redespacho->updated_at->format('d/m/Y H:i') }}
                    </dd>
                    @endif

                    <dt class="col-sm-6">Total Items:</dt>
                    <dd class="col-sm-6">
                        <span class="badge bg-info">{{ $redespacho->productos->count() }}</span>
                    </dd>
                </dl>

                <hr>

                <!-- Resumen de cantidades -->
                <h6>Resumen de Cantidades</h6>
                @foreach($redespacho->productos as $item)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small>{{ $item->producto->nombre }}</small>
                    <span class="badge bg-light text-dark">
                        {{ number_format($item->cantidad_secundaria, 2) }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
// Prueba desde VS Code
@endsection