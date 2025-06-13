{{-- resources/views/introducciones/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Introducciones')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Introducciones</li>
@endsection

@section('header')
<h1 class="h2"><i class="bi bi-truck"></i> Introducciones</h1>
<div class="btn-toolbar mb-2 mb-md-0">
    @if(Auth::user()->puedeEditar())
    <a href="{{ route('introducciones.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nueva Introducción
    </a>
    @endif
</div>
@endsection

@section('content')
<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('introducciones.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="introductor_id" class="form-label">Introductor</label>
                <select class="form-select" id="introductor_id" name="introductor_id">
                    <option value="">Todos los introductores</option>
                    @foreach($introductores as $id => $razon_social)
                    <option value="{{ $id }}" {{ request('introductor_id') == $id ? 'selected' : '' }}>
                        {{ $razon_social }}
                    </option>
                    @endforeach
                </select>
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
                <a href="{{ route('introducciones.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-clockwise"></i> Limpiar
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Lista de introducciones -->
<div class="card">
    <div class="card-body p-0">
        @if($introducciones->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Remito</th>
                        <th>Introductor</th>
                        <th>Fecha/Hora</th>
                        <th>Productos</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">QR</th>
                        <th class="text-center no-sort">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($introducciones as $introduccion)
                    <tr>
                        <td>
                            <strong>{{ $introduccion->numero_remito }}</strong>
                            @if($introduccion->temperatura)
                                <br><small class="text-muted">Temp: {{ $introduccion->temperatura }}°C</small>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $introduccion->introductor->razon_social }}</strong><br>
                            <small class="text-muted">{{ $introduccion->introductor->cuit_formateado }}</small>
                        </td>
                        <td>
                            {{ $introduccion->fecha_formateada }}<br>
                            <small class="text-muted">{{ $introduccion->hora_formateada }}</small>
                        </td>
                        <td>
                            @if($introduccion->productos->count() > 0)
                                <span class="badge bg-info">{{ $introduccion->productos->count() }} productos</span>
                                <br>
                                <small class="text-muted">
                                    {{ $introduccion->productos->first()->producto->nombre ?? 'Sin productos' }}
                                    @if($introduccion->productos->count() > 1)
                                        y {{ $introduccion->productos->count() - 1 }} más...
                                    @endif
                                </small>
                            @else
                                <span class="badge bg-warning">Sin productos</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @php
                                $stockDisponible = $introduccion->stockDisponible();
                                $tieneStock = $stockDisponible->where('stock_disponible', '>', 0)->count() > 0;
                            @endphp
                            @if($tieneStock)
                                <span class="badge bg-success">Con Stock</span>
                            @else
                                <span class="badge bg-secondary">Sin Stock</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($introduccion->qr_code)
                                <button type="button" class="btn btn-sm btn-outline-info" 
                                        onclick="mostrarQR('{{ $introduccion->qr_code }}', '{{ $introduccion->numero_remito }}')">
                                    <i class="bi bi-qr-code"></i>
                                </button>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="/introducciones/{{ $introduccion->id }}" 
                                   class="btn btn-sm btn-outline-primary" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                
                                @if(Auth::user()->puedeEditar() && !$introduccion->redespachos()->exists())
                                <a href="/introducciones/{{ $introduccion->id }}/edit" 
                                   class="btn btn-sm btn-outline-warning" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endif
                                
                                @if($tieneStock)
                                <a href="/introducciones/{{ $introduccion->id }}/redespachos/create" 
                                   class="btn btn-sm btn-outline-success" title="Redespachar">
                                    <i class="bi bi-arrow-repeat"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($introducciones->hasPages())
        <div class="d-flex justify-content-between align-items-center p-3">
            <div>
                Mostrando {{ $introducciones->firstItem() }} a {{ $introducciones->lastItem() }} 
                de {{ $introducciones->total() }} resultados
            </div>
            {{ $introducciones->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-5">
            <i class="bi bi-truck display-1 text-muted"></i>
            <h4 class="text-muted mt-3">No se encontraron introducciones</h4>
            <p class="text-muted">Las introducciones que registres aparecerán aquí.</p>
            @if(Auth::user()->puedeEditar())
            <a href="{{ route('introducciones.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Crear Primera Introducción
            </a>
            @endif
        </div>
        @endif
    </div>
</div>

<!-- Modal QR -->
<div class="modal fade" id="qrModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrModalTitle">Código QR</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div id="qrcode" style="display: inline-block;"></div>
                <p class="mt-3" id="qrText"></p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
<script>
function mostrarQR(qrCode, numeroRemito) {
    document.getElementById('qrModalTitle').textContent = 'QR - ' + numeroRemito;
    document.getElementById('qrText').textContent = qrCode;
    
    // Limpiar canvas anterior
    const qrContainer = document.getElementById('qrcode');
    qrContainer.innerHTML = '';
    
    // Generar nuevo QR
    QRCode.toCanvas(qrContainer, qrCode, {
        width: 300,
        margin: 2
    }, function(error) {
        if (error) console.error(error);
    });
    
    new bootstrap.Modal(document.getElementById('qrModal')).show();
}

$(document).ready(function() {
    // DataTable básico si hay resultados
    @if($introducciones->count() > 0)
    $('table').DataTable({
        searching: false,
        info: false,
        paging: false,
        order: [[2, 'desc']], // Ordenar por fecha
        columnDefs: [
            { targets: [4, 5, 6], orderable: false }
        ]
    });
    @endif
});
</script>
@endpush