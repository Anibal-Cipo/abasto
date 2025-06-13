@extends('layouts.app')

@section('title', 'Detalle Introducción')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('introducciones.index') }}">Introducciones</a></li>
    <li class="breadcrumb-item active">{{ $introduccion->numero_remito ?? 'Sin número' }}</li>
@endsection

@section('header')
    <div>
        <h1 class="h2">
            <i class="bi bi-truck"></i> Remito {{ $introduccion->numero_remito ?? 'Sin número' }}
            @if ($stockDisponible && $stockDisponible->where('stock_disponible', '>', 0)->count() > 0)
                <span class="badge bg-success ms-2">Con Stock</span>
            @else
                <span class="badge bg-secondary ms-2">Sin Stock</span>
            @endif
        </h1>
        <p class="text-muted mb-0">
            {{ $introduccion->fecha_hora ?? 'Sin fecha' }} -
            {{ $introduccion->introductor?->razon_social ?? 'Introductor no disponible' }}
        </p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            @if (Auth::user()->puedeEditar() && $introduccion->id && !$introduccion->redespachos()->exists())
                <a href="{{ route('introducciones.edit', $introduccion->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Editar
                </a>
            @endif

            @if ($stockDisponible && $stockDisponible->where('stock_disponible', '>', 0)->count() > 0 && $introduccion->id)
                <a href="{{ route('redespachos.create', $introduccion->id) }}" class="btn btn-primary">
                    <i class="bi bi-arrow-repeat"></i> Nuevo Redespacho
                </a>
            @endif

            @if ($introduccion->qr_code)
                <button type="button" class="btn btn-info" onclick="mostrarQR()">
                    <i class="bi bi-qr-code"></i> Ver QR
                </button>
            @endif

            <a href="{{ route('introducciones.imprimir', $introduccion->id) }}" class="btn btn-success" target="_blank">
                <i class="bi bi-printer"></i> Imprimir Remito
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <!-- Información principal -->
        <div class="col-md-8">
            <!-- Datos del remito -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-info-circle"></i> Información del Remito
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-5">Introductor:</dt>
                                <dd class="col-sm-7">
                                    @if ($introduccion->introductor)
                                        <strong>{{ $introduccion->introductor->razon_social }}</strong><br>
                                        <small class="text-muted">{{ $introduccion->introductor->cuit_formateado }}</small>
                                    @else
                                        <span class="text-muted">Introductor no disponible</span>
                                    @endif
                                </dd>

                                <dt class="col-sm-5">Fecha/Hora:</dt>
                                <dd class="col-sm-7">{{ $introduccion->fecha_hora ?? 'Sin fecha/hora' }}</dd>

                                <dt class="col-sm-5">Vehículo:</dt>
                                <dd class="col-sm-7">
                                    {{ $introduccion->vehiculo ?: 'No especificado' }}
                                    @if ($introduccion->dominio)
                                        <br><strong>{{ $introduccion->dominio }}</strong>
                                    @endif
                                </dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl class="row">
                                @if ($introduccion->temperatura)
                                    <dt class="col-sm-5">Temperatura:</dt>
                                    <dd class="col-sm-7">
                                        <span
                                            class="badge {{ $introduccion->temperatura <= 4 ? 'bg-success' : 'bg-warning' }}">
                                            {{ $introduccion->temperatura }}°C
                                        </span>
                                    </dd>
                                @endif

                                @if ($introduccion->pt_numero)
                                    <dt class="col-sm-5">P.T. N°:</dt>
                                    <dd class="col-sm-7">{{ $introduccion->pt_numero }}</dd>
                                @endif

                                @if ($introduccion->ptr_numero)
                                    <dt class="col-sm-5">P.T.R. N°:</dt>
                                    <dd class="col-sm-7">{{ $introduccion->ptr_numero }}</dd>
                                @endif

                                <dt class="col-sm-5">Registrado por:</dt>
                                <dd class="col-sm-7">
                                    @if ($introduccion->usuario)
                                        <span class="badge bg-info">{{ $introduccion->usuario->name }}</span>
                                    @else
                                        <span class="text-muted">Usuario no disponible</span>
                                    @endif
                                </dd>
                            </dl>
                        </div>
                    </div>

                    @if ($introduccion->receptores)
                        <hr>
                        <dl class="row">
                            <dt class="col-sm-2">Receptores:</dt>
                            <dd class="col-sm-10">{{ $introduccion->receptores }}</dd>
                        </dl>
                    @endif

                    @if ($introduccion->observaciones)
                        <hr>
                        <dl class="row">
                            <dt class="col-sm-2">Observaciones:</dt>
                            <dd class="col-sm-10">{{ $introduccion->observaciones }}</dd>
                        </dl>
                    @endif
                </div>
            </div>

            <!-- Productos -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-box"></i> Productos Introducidos
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if ($stockDisponible && $stockDisponible->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Producto</th>
                                        <th class="text-center">Introducido</th>
                                        <th class="text-center">Redespachado</th>
                                        <th class="text-center">Disponible</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stockDisponible as $item)
                                        <tr>
                                            <td>
                                                @if ($item->producto)
                                                    <strong>{{ $item->producto->nombre }}</strong><br>
                                                    <small class="text-muted">{{ $item->producto->categoria }}</small>
                                                @else
                                                    <span class="text-muted">Producto no disponible</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="badge bg-primary">{{ $item->cantidad_display ?? $item->cantidad_secundaria }}</span>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $redespachado = method_exists($item, 'getStockRedespachado')
                                                        ? $item->getStockRedespachado()
                                                        : 0;
                                                @endphp
                                                @if ($redespachado > 0)
                                                    <span class="badge bg-warning">{{ $redespachado }}
                                                        {{ $item->producto?->unidad_secundaria ?? '' }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($item->stock_disponible > 0)
                                                    <span class="badge bg-success">{{ $item->stock_disponible }}
                                                        {{ $item->producto?->unidad_secundaria ?? '' }}</span>
                                                @else
                                                    <span class="badge bg-secondary">Agotado</span>
                                                @endif
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

            <!-- Redespachos -->
            @if ($introduccion->redespachos && $introduccion->redespachos->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-arrow-repeat"></i> Redespachos Realizados
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>N° Redespacho</th>
                                        <th>Fecha/Hora</th>
                                        <th>Destino</th>
                                        <th>Productos</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($introduccion->redespachos as $redespacho)
                                        <tr>
                                            <td><strong>{{ $redespacho->numero_redespacho ?? 'Sin número' }}</strong></td>
                                            <td>{{ $redespacho->fecha_hora ?? 'Sin fecha' }}</td>
                                            <td>{{ $redespacho->destino ?? 'Sin destino' }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-info">{{ $redespacho->productos ? $redespacho->productos->count() : 0 }}
                                                    productos</span>
                                            </td>
                                            <td class="text-center">
                                                @if ($redespacho->id)
                                                    <a href="{{ route('redespachos.show', $redespacho->id) }}"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- QR Code -->
            @if ($introduccion->qr_code)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-qr-code"></i> Código QR
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <div id="qrcode" style="display: inline-block;"></div>
                        <p class="mt-2 mb-0">
                            <small class="text-muted">{{ $introduccion->qr_code }}</small>
                        </p>
                    </div>
                </div>
            @endif

            <!-- Archivos -->
            @if ($introduccion->archivos && $introduccion->archivos->count() > 0)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-paperclip"></i> Archivos Adjuntos
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach ($introduccion->archivos as $archivo)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <strong>{{ $archivo->tipo_archivo ?? 'Archivo' }}</strong><br>
                                    <small class="text-muted">{{ $archivo->nombre_original ?? 'Sin nombre' }}</small><br>
                                    <small
                                        class="text-muted">{{ $archivo->tamaño_humano ?? 'Tamaño desconocido' }}</small>
                                </div>
                                @if ($archivo->url ?? false)
                                    <a href="{{ $archivo->url }}" target="_blank"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-download"></i>
                                    </a>
                                @endif
                            </div>
                            @if (!$loop->last)
                                <hr>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

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
                            {{ $introduccion->created_at ? $introduccion->created_at->format('d/m/Y H:i') : 'Fecha desconocida' }}
                        </dd>

                        @if ($introduccion->updated_at && $introduccion->created_at && $introduccion->updated_at->ne($introduccion->created_at))
                            <dt class="col-sm-6">Modificado:</dt>
                            <dd class="col-sm-6">
                                {{ $introduccion->updated_at->format('d/m/Y H:i') }}
                            </dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal QR -->
    @if ($introduccion->qr_code)
        <div class="modal fade" id="qrModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Código QR - {{ $introduccion->numero_remito ?? 'Sin número' }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <div id="qrcodeLarge" style="display: inline-block;"></div>
                        <p class="mt-3">
                            <strong>{{ $introduccion->qr_code }}</strong>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    <script>
        // Generar QR codes
        @if ($introduccion->qr_code)
            document.addEventListener('DOMContentLoaded', function() {
                if (document.getElementById('qrcode')) {
                    QRCode.toCanvas(document.getElementById('qrcode'), '{{ $introduccion->qr_code }}', {
                        width: 150,
                        margin: 2
                    }, function(error) {
                        if (error) console.error('Error generando QR:', error);
                    });
                }
            });
        @endif

        function mostrarQR() {
            @if ($introduccion->qr_code)
                // Generar QR grande para el modal
                const qrLargeElement = document.getElementById('qrcodeLarge');
                if (qrLargeElement) {
                    // Limpiar contenido anterior
                    qrLargeElement.innerHTML = '';

                    QRCode.toCanvas(qrLargeElement, '{{ $introduccion->qr_code }}', {
                        width: 300,
                        margin: 2
                    }, function(error) {
                        if (error) {
                            console.error('Error generando QR grande:', error);
                            qrLargeElement.innerHTML = '<p class="text-danger">Error generando código QR</p>';
                        }
                    });
                }

                const modal = new bootstrap.Modal(document.getElementById('qrModal'));
                modal.show();
            @else
                alert('Esta introducción no tiene código QR generado');
            @endif
        }
    </script>
@endpush
