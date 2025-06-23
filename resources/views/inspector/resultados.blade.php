@extends('layouts.inspector')

@section('title', 'Resultados de Búsqueda')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('inspector.index') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ route('inspector.buscar') }}">Buscar</a></li>
    <li class="breadcrumb-item active">Resultados</li>
@endsection

@section('header')
    <div class="text-center">
        <h2 class="h4 mb-1">
            <i class="bi bi-search text-success"></i>
            Resultados de Búsqueda
        </h2>
        <p class="text-muted mb-0">Búsqueda: "<strong>{{ $request->termino }}</strong>"</p>
        <div class="mt-2">
            <a href="{{ route('inspector.buscar') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Nueva Búsqueda
            </a>
        </div>
    </div>
@endsection

@section('content')
    @if ($introductores->count() > 0)
        @foreach ($introductores as $introductor)
            <div class="card-inspector mb-4">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-1">
                                <i class="bi bi-building text-primary"></i>
                                {{ $introductor->razon_social }}
                                @if ($introductor->activo)
                                    <span class="badge bg-success ms-2">Activo</span>
                                @else
                                    <span class="badge bg-secondary ms-2">Inactivo</span>
                                @endif
                            </h6>
                            <small class="text-muted">CUIT: {{ $introductor->cuit_formateado }}</small>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('introductores.show', $introductor) }}"
                                class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>

                @if ($introductor->introduccionesRecientes->count() > 0)
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 small">
                                <thead class="table-light">
                                    <tr>
                                        <th>Remito</th>
                                        <th>Fecha</th>
                                        <th>Productos</th>
                                        <th class="text-center">Stock</th>
                                        <th class="text-center">QR</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($introductor->introduccionesRecientes as $introduccion)
                                        <tr>
                                            <td>
                                                <strong class="small">{{ $introduccion->numero_remito }}</strong>
                                            </td>
                                            <td>
                                                <div class="small">{{ $introduccion->fecha->format('d/m/Y') }}</div>
                                                <div class="text-muted" style="font-size: 0.75rem;">
                                                    {{ $introduccion->hora_formateada }}</div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info small">{{ $introduccion->productos->count() }}
                                                    items</span>
                                                @if ($introduccion->productos->count() > 0)
                                                    <div class="text-muted" style="font-size: 0.75rem;">
                                                        {{ Str::limit($introduccion->productos->first()->producto->nombre, 20) }}
                                                        @if ($introduccion->productos->count() > 1)
                                                            +{{ $introduccion->productos->count() - 1 }}
                                                        @endif
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $stockDisponible = $introduccion->stockDisponible();
                                                    $tieneStock =
                                                        $stockDisponible->where('stock_disponible', '>', 0)->count() >
                                                        0;
                                                @endphp
                                                @if ($tieneStock)
                                                    <span class="badge bg-success small">Disponible</span>
                                                @else
                                                    <span class="badge bg-secondary small">Agotado</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($introduccion->qr_code)
                                                    <button type="button" class="btn btn-sm btn-info"
                                                        onclick="mostrarQR('{{ $introduccion->qr_code }}', '{{ $introduccion->numero_remito }}')">
                                                        <i class="bi bi-qr-code"></i>
                                                    </button>
                                                @else
                                                    <span class="text-muted small">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group-vertical" role="group">
                                                    <a href="{{ route('inspector.introduccion.show', $introduccion->id) }}"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    @if ($tieneStock)
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
        <div class="card-inspector">
            <div class="card-body text-center py-5">
                <i class="bi bi-search display-1 text-muted"></i>
                <h4 class="text-muted mt-3">No se encontraron resultados</h4>
                <p class="text-muted mb-4">
                    No hay introductores que coincidan con "<strong>{{ $request->termino }}</strong>"
                </p>
                <div class="d-grid gap-2">
                    <a href="{{ route('inspector.buscar') }}" class="btn btn-inspector-primary">
                        <i class="bi bi-arrow-left me-2"></i>
                        Intentar nueva búsqueda
                    </a>
                    <a href="{{ route('inspector.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-house me-2"></i>
                        Volver al Inicio
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal QR optimizado para móvil -->
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
