@extends('layouts.app')

@section('title', 'Resultados de Búsqueda')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('inspector.index') }}">Inspector</a></li>
<li class="breadcrumb-item"><a href="{{ route('inspector.buscar') }}">Buscar</a></li>
<li class="breadcrumb-item active">Resultados</li>
@endsection

@section('header')
<div>
    <h1 class="h2"><i class="bi bi-search"></i> Resultados de Búsqueda</h1>
    <p class="text-muted mb-0">Búsqueda: "{{ $request->termino }}"</p>
</div>
<div class="btn-toolbar mb-2 mb-md-0">
    <a href="{{ route('inspector.buscar') }}" class="btn btn-primary">
        <i class="bi bi-arrow-left"></i> Nueva Búsqueda
    </a>
</div>
@endsection

@section('content')
@if($introductores->count() > 0)
    @foreach($introductores as $introductor)
    <div class="card mb-4">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-building"></i> {{ $introductor->razon_social }}
                        @if($introductor->activo)
                            <span class="badge bg-success ms-2">Activo</span>
                        @else
                            <span class="badge bg-secondary ms-2">Inactivo</span>
                        @endif
                    </h5>
                    <small class="text-muted">CUIT: {{ $introductor->cuit_formateado }}</small>
                </div>
                <div class="col-auto">
                    <a href="{{ route('introductores.show', $introductor) }}" 
                       class="btn btn-outline-primary">
                        <i class="bi bi-eye"></i> Ver Detalle
                    </a>
                </div>
            </div>
        </div>
        
        @if($introductor->introduccionesRecientes->count() > 0)
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Remito</th>
                            <th>Fecha/Hora</th>
                            <th>Productos</th>
                            <th class="text-center">Estado Stock</th>
                            <th class="text-center">QR</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($introductor->introduccionesRecientes as $introduccion)
                        <tr>
                            <td>
                                <strong>{{ $introduccion->numero_remito }}</strong>
                            </td>
                            <td>
                                {{ $introduccion->fecha->format('d/m/Y') }}<br>
                                <small class="text-muted">{{ $introduccion->hora->format('H:i') }}</small>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $introduccion->productos->count() }} productos</span>
                                @if($introduccion->productos->count() > 0)
                                <br>
                                <small class="text-muted">
                                    {{ $introduccion->productos->first()->producto->nombre }}
                                    @if($introduccion->productos->count() > 1)
                                        y {{ $introduccion->productos->count() - 1 }} más...
                                    @endif
                                </small>
                                @endif
                            </td>
                            <td class="text-center">
                                @php
                                    $stockDisponible = $introduccion->stockDisponible();
                                    $tieneStock = $stockDisponible->where('stock_disponible', '>', 0)->count() > 0;
                                @endphp
                                @if($tieneStock)
                                    <span class="badge bg-success">Disponible</span>
                                @else
                                    <span class="badge bg-secondary">Agotado</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($introduccion->qr_code)
                                    <button type="button" class="btn btn-sm btn-info" 
                                            onclick="mostrarQR('{{ $introduccion->qr_code }}', '{{ $introduccion->numero_remito }}')">
                                        <i class="bi bi-qr-code"></i>
                                    </button>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ route('introducciones.show', $introduccion) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($tieneStock)
                                    <a href="{{ route('redespachos.create', $introduccion) }}" 
                                       class="btn btn-sm btn-outline-success">
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
        </div>
        @else
        <div class="card-body">
            <div class="text-center py-3">
                <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                <p class="text-muted mt-2 mb-0">Sin introducciones recientes</p>
            </div>
        </div>
        @endif
    </div>
    @endforeach
@else
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-search display-1 text-muted"></i>
            <h4 class="text-muted mt-3">No se encontraron resultados</h4>
            <p class="text-muted">
                No hay introductores que coincidan con "{{ $request->termino }}"
            </p>
            <a href="{{ route('inspector.buscar') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> Intentar nueva búsqueda
            </a>
        </div>
    </div>
@endif

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
</script>
@endpush