@extends('layouts.app')

@section('title', 'Redespachos')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Redespachos</li>
@endsection

@section('header')
<h1 class="h2"><i class="bi bi-arrow-repeat"></i> Redespachos</h1>
@endsection

@section('content')
<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('redespachos.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="introduccion_id" class="form-label">Introducción</label>
                <input type="text" class="form-control" id="introduccion_id" name="introduccion_id" 
                       placeholder="ID de introducción" value="{{ request('introduccion_id') }}">
            </div>
            <div class="col-md-3">
                <label for="fecha_desde" class="form-label">Fecha Desde</label>
                <input type="date" class="form-control" id="fecha_desde" name="fecha_desde" 
                       value="{{ request('fecha_desde') }}">
            </div>
            <div class="col-md-3">
                <label for="fecha_hasta" class="form-label">Fecha Hasta</label>
                <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta" 
                       value="{{ request('fecha_hasta') }}">
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-outline-primary">
                    <i class="bi bi-search"></i> Filtrar
                </button>
                <a href="{{ route('redespachos.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-clockwise"></i> Limpiar
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Lista de redespachos -->
<div class="card">
    <div class="card-body p-0">
        @if($redespachos->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>N° Redespacho</th>
                        <th>Introducción</th>
                        <th>Fecha/Hora</th>
                        <th>Destino</th>
                        <th>Productos</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($redespachos as $redespacho)
                    <tr>
                        <td>
                            <strong>{{ $redespacho->numero_redespacho }}</strong>
                            @if($redespacho->certificado_sanitario)
                                <br><span class="badge bg-success">Con Cert. Sanitario</span>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $redespacho->introduccion->numero_remito }}</strong><br>
                            <small class="text-muted">{{ $redespacho->introduccion->introductor->razon_social }}</small>
                        </td>
                        <td>
                            {{ $redespacho->fecha->format('d/m/Y') }}<br>
                            <small class="text-muted">{{ substr($redespacho->hora, 0, 5) }}</small>
                        </td>
                        <td>
                            {{ $redespacho->destino }}
                            @if($redespacho->dominio)
                                <br><small class="text-muted">{{ $redespacho->dominio }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $redespacho->productos->count() }} productos</span>
                            @if($redespacho->productos->count() > 0)
                                <br><small class="text-muted">{{ $redespacho->productos->first()->producto->nombre }}</small>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('redespachos.show', $redespacho) }}" 
                                   class="btn btn-sm btn-outline-primary" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(Auth::user()->puedeEditar())
                                <form action="{{ route('redespachos.destroy', $redespacho) }}" method="POST" 
                                      style="display: inline;" onsubmit="return confirm('¿Estás seguro?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($redespachos->hasPages())
        <div class="d-flex justify-content-between align-items-center p-3">
            <div>
                Mostrando {{ $redespachos->firstItem() }} a {{ $redespachos->lastItem() }} 
                de {{ $redespachos->total() }} resultados
            </div>
            {{ $redespachos->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-5">
            <i class="bi bi-arrow-repeat display-1 text-muted"></i>
            <h4 class="text-muted mt-3">No se encontraron redespachos</h4>
            <p class="text-muted">Los redespachos que realices aparecerán aquí.</p>
        </div>
        @endif
    </div>
</div>
@endsection