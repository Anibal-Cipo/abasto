@extends('layouts.inspector')

@section('title', 'Detalle Introducción')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('inspector.index') }}">Inicio</a></li>
    <li class="breadcrumb-item active">Remito {{ $introduccion->numero_remito }}</li>
@endsection

@section('header')
    <div class="text-center">
        <h2 class="h4 mb-1">
            <i class="bi bi-truck text-success"></i>
            Remito {{ $introduccion->numero_remito }}
        </h2>
        @if ($stockDisponible && $stockDisponible->where('stock_disponible', '>', 0)->count() > 0)
            <span class="badge bg-success">Con Stock Disponible</span>
        @else
            <span class="badge bg-secondary">Sin Stock</span>
        @endif
    </div>
@endsection

@section('content')
    <!-- Información Principal -->
    <div class="row">
        <div class="col-12">
            <div class="card-inspector mb-3">
                <div class="card-header bg-success text-white text-center">
                    <h6 class="mb-0">
                        <i class="bi bi-building"></i>
                        Introductor
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h5 class="text-success">{{ $introduccion->introductor->razon_social }}</h5>
                        <p class="text-muted mb-1">CUIT: {{ $introduccion->introductor->cuit_formateado }}</p>
                        @if ($introduccion->introductor->direccion)
                            <small class="text-muted">{{ $introduccion->introductor->direccion }}</small>
                        @endif
                    </div>

                    <div class="row text-center">
                        <div class="col-6">
                            <div class="p-2 bg-light rounded">
                                <i class="bi bi-calendar text-primary"></i>
                                <div class="small">{{ $introduccion->fecha_formateada }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 bg-light rounded">
                                <i class="bi bi-clock text-info"></i>
                                <div class="small">{{ $introduccion->hora_formateada }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detalles del Transporte -->
    @if ($introduccion->vehiculo || $introduccion->dominio || $introduccion->temperatura)
        <div class="row">
            <div class="col-12">
                <div class="card-inspector mb-3">
                    <div class="card-header bg-info text-white text-center">
                        <h6 class="mb-0">
                            <i class="bi bi-truck"></i>
                            Transporte
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if ($introduccion->vehiculo)
                                <div class="col-6 mb-2">
                                    <small class="text-muted">Vehículo:</small>
                                    <div class="fw-bold">{{ $introduccion->vehiculo }}</div>
                                </div>
                            @endif

                            @if ($introduccion->dominio)
                                <div class="col-6 mb-2">
                                    <small class="text-muted">Dominio:</small>
                                    <div class="fw-bold">{{ $introduccion->dominio }}</div>
                                </div>
                            @endif

                            @if ($introduccion->temperatura)
                                <div class="col-12 text-center">
                                    <small class="text-muted">Temperatura:</small>
                                    <div>
                                        <span
                                            class="badge {{ $introduccion->temperatura <= 4 ? 'bg-success' : 'bg-warning' }} fs-6">
                                            {{ $introduccion->temperatura }}°C
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Productos y Stock -->
    <div class="row">
        <div class="col-12">
            <div class="card-inspector mb-3">
                <div class="card-header bg-primary text-white text-center">
                    <h6 class="mb-0">
                        <i class="bi bi-box"></i>
                        Productos y Stock
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if ($stockDisponible->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="small">Producto</th>
                                        <th class="text-center small">Ingresado</th>
                                        <th class="text-center small">Disponible</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stockDisponible as $item)
                                        <tr>
                                            <td>
                                                <div class="small fw-bold">{{ $item->producto->nombre }}</div>
                                                <div class="text-muted" style="font-size: 0.75rem;">
                                                    {{ $item->producto->categoria }}</div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info small">
                                                    {{ $item->cantidad_secundaria }}
                                                    {{ $item->producto->unidad_secundaria }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if ($item->stock_disponible > 0)
                                                    <span class="badge bg-success small">
                                                        {{ $item->stock_disponible }}
                                                        {{ $item->producto->unidad_secundaria }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary small">Agotado</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Redespachos -->
    @if ($introduccion->redespachos->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card-inspector mb-3">
                    <div class="card-header bg-warning text-dark text-center">
                        <h6 class="mb-0">
                            <i class="bi bi-arrow-repeat"></i>
                            Redespachos Realizados ({{ $introduccion->redespachos->count() }})
                        </h6>
                    </div>
                    <div class="card-body">
                        @foreach ($introduccion->redespachos as $redespacho)
                            <div class="border rounded p-2 mb-2 bg-light">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <div class="small fw-bold">{{ $redespacho->numero_redespacho }}</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">
                                            {{ $redespacho->fecha->format('d/m/Y') }} - {{ $redespacho->destino }}
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <a href="{{ route('inspector.redespacho.show', $redespacho->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Información Adicional -->
    @if ($introduccion->observaciones || $introduccion->pt_numero || $introduccion->ptr_numero)
        <div class="row">
            <div class="col-12">
                <div class="card-inspector mb-3">
                    <div class="card-header bg-secondary text-white text-center">
                        <h6 class="mb-0">
                            <i class="bi bi-info-circle"></i>
                            Información Adicional
                        </h6>
                    </div>
                    <div class="card-body">
                        @if ($introduccion->pt_numero)
                            <div class="mb-2">
                                <small class="text-muted">P.T. N°:</small>
                                <span class="fw-bold">{{ $introduccion->pt_numero }}</span>
                            </div>
                        @endif

                        @if ($introduccion->ptr_numero)
                            <div class="mb-2">
                                <small class="text-muted">P.T.R. N°:</small>
                                <span class="fw-bold">{{ $introduccion->ptr_numero }}</span>
                            </div>
                        @endif

                        @if ($introduccion->observaciones)
                            <div class="mb-2">
                                <small class="text-muted">Observaciones:</small>
                                <div class="small">{{ $introduccion->observaciones }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Código QR -->
    @if ($introduccion->qr_code)
        <div class="row">
            <div class="col-12">
                <div class="card-inspector mb-3">
                    <div class="card-body text-center">
                        <h6 class="mb-3">
                            <i class="bi bi-qr-code text-info"></i>
                            Código QR
                        </h6>
                        <button type="button" class="btn btn-inspector-info"
                            onclick="mostrarQR('{{ $introduccion->qr_code }}', '{{ $introduccion->numero_remito }}')">
                            <i class="bi bi-qr-code me-2"></i>
                            Mostrar Código QR
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Botones de Acción -->
    <div class="row">
        <div class="col-12">
            <div class="d-grid gap-2">
                <a href="{{ route('introducciones.imprimir', $introduccion->id) }}" class="btn btn-inspector-primary"
                    target="_blank">
                    <i class="bi bi-printer me-2"></i>
                    Imprimir Remito PDF
                </a>

                <a href="{{ route('inspector.buscar') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>
                    Volver a Búsqueda
                </a>

                <a href="{{ route('inspector.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-house me-2"></i>
                    Ir al Inicio
                </a>
            </div>
        </div>
    </div>

    <!-- Modal QR -->
    <div class="modal fade" id="qrModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 15px;">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="qrModalTitle">
                        <i class="bi bi-qr-code"></i> Código QR
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <div id="qrcode" style="display: inline-block; border-radius: 10px; overflow: hidden;"></div>
                    <p class="mt-3 text-muted" id="qrText"></p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    <script>
        function mostrarQR(qrCode, numeroRemito) {
            document.getElementById('qrModalTitle').innerHTML = '<i class="bi bi-qr-code"></i> QR - ' + numeroRemito;
            document.getElementById('qrText').textContent = qrCode;

            // Limpiar canvas anterior
            const qrContainer = document.getElementById('qrcode');
            qrContainer.innerHTML = '';

            // Generar nuevo QR
            QRCode.toCanvas(qrContainer, qrCode, {
                width: 250,
                margin: 2,
                color: {
                    dark: '#000000',
                    light: '#FFFFFF'
                }
            }, function(error) {
                if (error) console.error(error);
            });

            new bootstrap.Modal(document.getElementById('qrModal')).show();
        }
    </script>
@endpush
