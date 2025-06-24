{{-- resources/views/infracciones/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Acta de Contravención #' . $acta->id)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                {{-- Header del Acta --}}
                <div class="card mb-3">
                    <div class="card-header bg-danger text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                <i class="fas fa-file-alt me-2"></i>
                                Acta #{{ $acta->id }}
                            </h4>
                            <span class="badge bg-light text-dark">
                                {{ $acta->tipo_acta_descripcion }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Fecha y Hora:</strong> {{ $acta->fecha_hora->format('d/m/Y H:i') }}</p>
                                <p><strong>Inspector:</strong> {{ $acta->usuario }}</p>
                                <p><strong>Ubicación:</strong> {{ $acta->ubicacion }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Destino:</strong> {{ $acta->destino_acta }}</p>
                                <p><strong>Estado:</strong> {{ $acta->estado ?? 'Pendiente' }}</p>
                                @if ($acta->monto > 0)
                                    <p><strong>Monto:</strong> ${{ number_format($acta->monto, 2) }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Datos de la Persona --}}
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fas fa-user me-2"></i>
                            Datos de la Persona
                        </h6>
                    </div>
                    <div class="card-body">
                        @if ($acta->persona)
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>DNI:</strong> {{ number_format($acta->persona->dni, 0, '', '.') }}</p>
                                    <p><strong>Nombre:</strong> {{ $acta->persona->nombre_completo }}</p>
                                    <p><strong>Nacionalidad:</strong>
                                        {{ $acta->persona->nacionalidad ?? 'No especificada' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Dirección:</strong>
                                        {{ $acta->persona->direccion_completa ?: 'No especificada' }}</p>
                                    <p><strong>Teléfono:</strong> {{ $acta->persona->telefono ?? 'No especificado' }}</p>
                                    @if ($acta->persona->fecha_nacimiento)
                                        <p><strong>Fecha de Nacimiento:</strong>
                                            {{ $acta->persona->fecha_nacimiento->format('d/m/Y') }}</p>
                                    @endif
                                </div>
                            </div>
                        @else
                            <p class="text-muted">No hay datos de persona asociados</p>
                        @endif
                    </div>
                </div>

                {{-- Datos del Vehículo --}}
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fas fa-car me-2"></i>
                            Datos del Vehículo
                        </h6>
                    </div>
                    <div class="card-body">
                        @if ($acta->vehiculo)
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Dominio:</strong> {{ $acta->vehiculo->dominio }}</p>
                                    <p><strong>Marca/Modelo:</strong> {{ $acta->vehiculo->marca_modelo }}</p>
                                    <p><strong>Color:</strong> {{ $acta->vehiculo->color ?? 'No especificado' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Tipo:</strong> {{ $acta->vehiculo->tipo_vehiculo ?? 'No especificado' }}</p>
                                    @if ($acta->vehiculo->motor)
                                        <p><strong>Motor:</strong> {{ $acta->vehiculo->motor }}</p>
                                    @endif
                                    @if ($acta->vehiculo->chasis)
                                        <p><strong>Chasis:</strong> {{ $acta->vehiculo->chasis }}</p>
                                    @endif
                                </div>
                            </div>
                        @else
                            <p class="text-muted">No hay datos de vehículo asociados</p>
                        @endif
                    </div>
                </div>

                {{-- Infracciones --}}
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Infracciones Cometidas
                        </h6>
                    </div>
                    <div class="card-body">
                        @if ($acta->tipos->count() > 0)
                            <div class="list-group">
                                @foreach ($acta->tipos as $tipo)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">{{ $tipo->tipoInfraccion->codigo }}</h6>
                                                <p class="mb-1">{{ $tipo->tipoInfraccion->descripcion }}</p>
                                            </div>
                                            @if ($tipo->tipoInfraccion->sam > 0)
                                                <span class="badge bg-warning text-dark">
                                                    ${{ number_format($tipo->tipoInfraccion->sam, 2) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">No hay infracciones registradas</p>
                        @endif
                    </div>
                </div>

                {{-- Motivo y Observaciones --}}
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fas fa-comment me-2"></i>
                            Motivo y Observaciones
                        </h6>
                    </div>
                    <div class="card-body">
                        @if ($acta->motivo)
                            <div class="mb-3">
                                <strong>Motivo:</strong>
                                <p class="mt-1">{{ $acta->motivo }}</p>
                            </div>
                        @endif

                        @if ($acta->observaciones)
                            <div class="mb-3">
                                <strong>Observaciones:</strong>
                                <p class="mt-1">{{ $acta->observaciones }}</p>
                            </div>
                        @endif

                        {{-- Información adicional --}}
                        <div class="row">
                            <div class="col-md-4">
                                <p><strong>Es Verbal:</strong> {{ $acta->es_verbal == 'S' ? 'Sí' : 'No' }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Retiene Licencia:</strong> {{ $acta->retiene_licencia == 'S' ? 'Sí' : 'No' }}
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Retiene Vehículo:</strong> {{ $acta->retiene_vehiculo == 'S' ? 'Sí' : 'No' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Documentación Fotográfica --}}
                @if ($acta->documentacion->count() > 0)
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="fas fa-images me-2"></i>
                                Documentación Fotográfica
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach ($acta->documentacion as $doc)
                                    <div class="col-md-4 mb-3">
                                        <div class="card">
                                            <img src="{{ $doc->url }}" class="card-img-top" alt="Foto del acta"
                                                style="height: 200px; object-fit: cover; cursor: pointer;"
                                                onclick="mostrarImagenCompleta('{{ $doc->url }}')">
                                            <div class="card-body">
                                                <p class="card-text small">{{ $doc->descripcion }}</p>
                                                <small
                                                    class="text-muted">{{ $doc->fecha_subida->format('d/m/Y H:i') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Ubicación --}}
                @if ($acta->latitud && $acta->longitud)
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                Ubicación GPS
                            </h6>
                        </div>
                        <div class="card-body">
                            <p><strong>Coordenadas:</strong> {{ $acta->latitud }}, {{ $acta->longitud }}</p>
                            <a href="https://www.google.com/maps?q={{ $acta->latitud }},{{ $acta->longitud }}"
                                target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-external-link-alt me-1"></i>
                                Ver en Google Maps
                            </a>
                        </div>
                    </div>
                @endif

                {{-- Botones de Acción --}}
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            {{-- URL Pública --}}
                            <div class="mb-3">
                                <label class="form-label">URL Pública del Acta:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" value="{{ $acta->url_publica }}"
                                        id="urlPublica" readonly>
                                    <button class="btn btn-outline-secondary" type="button" onclick="copiarUrl()">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Use esta URL para compartir el acta públicamente</small>
                            </div>

                            {{-- Botones de acción --}}
                            <a href="{{ route('infracciones.imprimir-termica', $acta) }}" class="btn btn-success btn-lg"
                                target="_blank">
                                <i class="fas fa-print me-2"></i>
                                Imprimir Ticket Térmico
                            </a>

                            <a href="{{ route('infracciones.mis-actas') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-list me-2"></i>
                                Ver Mis Actas
                            </a>

                            <a href="{{ route('infracciones.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Volver al Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para mostrar imagen completa --}}
    <div class="modal fade" id="modalImagen" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Imagen del Acta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="imagenCompleta" src="" class="img-fluid" alt="Imagen completa">
                </div>
            </div>
        </div>
    </div>

    <script>
        function mostrarImagenCompleta(url) {
            document.getElementById('imagenCompleta').src = url;
            new bootstrap.Modal(document.getElementById('modalImagen')).show();
        }

        function copiarUrl() {
            const urlInput = document.getElementById('urlPublica');
            urlInput.select();
            urlInput.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(urlInput.value).then(function() {
                alert('URL copiada al portapapeles');
            });
        }
    </script>
@endsection

{{-- resources/views/infracciones/imprimir-termica.blade.php --}}
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acta #{{ $acta->id }} - Impresión Térmica</title>
    <style>
        @page {
            size: 58mm auto;
            margin: 0;
        }

        body {
            width: 58mm;
            margin: 0;
            padding: 2mm;
            font-family: 'Courier New', monospace;
            font-size: 8px;
            line-height: 1.2;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 3mm;
            border-bottom: 1px dashed #000;
            padding-bottom: 2mm;
        }

        .logo {
            font-weight: bold;
            font-size: 10px;
            margin-bottom: 1mm;
        }

        .titulo {
            font-weight: bold;
            font-size: 9px;
            margin: 1mm 0;
        }

        .section {
            margin: 2mm 0;
            padding: 1mm 0;
        }

        .section-title {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 1mm;
        }

        .row {
            margin: 0.5mm 0;
        }

        .label {
            font-weight: bold;
        }

        .separator {
            border-top: 1px dashed #000;
            margin: 2mm 0;
        }

        .footer {
            text-align: center;
            font-size: 7px;
            margin-top: 3mm;
            padding-top: 2mm;
            border-top: 1px dashed #000;
        }

        .qr-code {
            text-align: center;
            margin: 2mm 0;
        }

        .qr-code img {
            max-width: 25mm;
            max-height: 25mm;
        }

        .text-center {
            text-align: center;
        }

        .small {
            font-size: 7px;
        }

        @media print {
            body {
                width: 58mm;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    {{-- Header --}}
    <div class="header">
        <div class="logo">MUNICIPALIDAD</div>
        <div>DE CIPOLLETTI</div>
        <div class="titulo">ACTA DE CONTRAVENCIÓN</div>
        <div>N° {{ str_pad($acta->id, 8, '0', STR_PAD_LEFT) }}</div>
    </div>

    {{-- Fecha y Hora --}}
    <div class="section">
        <div class="section-title">FECHA Y HORA</div>
        <div class="row">{{ $acta->fecha_hora->format('d/m/Y H:i') }}</div>
    </div>

    {{-- Inspector --}}
    <div class="section">
        <div class="section-title">INSPECTOR</div>
        <div class="row">{{ $acta->usuario }}</div>
    </div>

    {{-- Persona --}}
    @if ($acta->persona)
        <div class="section">
            <div class="section-title">IMPUTADO</div>
            <div class="row">
                <span class="label">DNI:</span> {{ number_format($acta->persona->dni, 0, '', '.') }}
            </div>
            <div class="row">{{ $acta->persona->nombre_completo }}</div>
            @if ($acta->persona->direccion_completa)
                <div class="row small">{{ $acta->persona->direccion_completa }}</div>
            @endif
        </div>
    @endif

    {{-- Vehículo --}}
    @if ($acta->vehiculo)
        <div class="section">
            <div class="section-title">VEHÍCULO</div>
            <div class="row">
                <span class="label">Dominio:</span> {{ $acta->vehiculo->dominio }}
            </div>
            @if ($acta->vehiculo->marca_modelo != 'No especificado')
                <div class="row">{{ $acta->vehiculo->marca_modelo }}</div>
            @endif
            @if ($acta->vehiculo->color)
                <div class="row">
                    <span class="label">Color:</span> {{ $acta->vehiculo->color }}
                </div>
            @endif
        </div>
    @endif

    {{-- Infracciones --}}
    @if ($acta->tipos->count() > 0)
        <div class="section">
            <div class="section-title">INFRACCIONES</div>
            @foreach ($acta->tipos as $tipo)
                <div class="row">
                    <div><span class="label">{{ $tipo->tipoInfraccion->codigo }}:</span></div>
                    <div class="small">{{ $tipo->tipoInfraccion->descripcion }}</div>
                    @if ($tipo->tipoInfraccion->sam > 0)
                        <div class="small"><span class="label">SAM:</span>
                            ${{ number_format($tipo->tipoInfraccion->sam, 2) }}</div>
                    @endif
                </div>
                @if (!$loop->last)
                    <div style="margin: 1mm 0; border-top: 1px dotted #000;"></div>
                @endif
            @endforeach
        </div>
    @endif

    {{-- Ubicación --}}
    <div class="section">
        <div class="section-title">UBICACIÓN</div>
        <div class="row small">{{ $acta->ubicacion }}</div>
    </div>

    {{-- Motivo (resumido) --}}
    @if ($acta->motivo)
        <div class="section">
            <div class="section-title">MOTIVO</div>
            <div class="row small">{{ \Str::limit($acta->motivo, 80) }}</div>
        </div>
    @endif

    {{-- Destino --}}
    <div class="section">
        <div class="section-title">DESTINO</div>
        <div class="row">{{ $acta->destino_acta }}</div>
    </div>

    {{-- Separador --}}
    <div class="separator"></div>

    {{-- QR Code --}}
    <div class="qr-code">
        <div class="small">Consulte el acta completa:</div>
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&format=png&data={{ urlencode($acta->url_publica) }}"
            alt="QR Code">
        <div class="small">{{ parse_url($acta->url_publica, PHP_URL_HOST) }}</div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <div>Sistema de Gestión Municipal</div>
        <div>{{ now()->format('d/m/Y H:i') }}</div>
        <div class="small">ID: {{ $acta->id }}</div>
    </div>

    {{-- Botón de impresión (solo visible en pantalla) --}}
    <div class="no-print" style="margin-top: 5mm; text-align: center;">
        <button onclick="window.print()" style="padding: 5px 10px; font-size: 10px;">
            Imprimir
        </button>
        <button onclick="window.close()" style="padding: 5px 10px; font-size: 10px; margin-left: 5px;">
            Cerrar
        </button>
    </div>

    <script>
        // Auto-imprimir al cargar la página
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 500);
        });
    </script>
</body>

</html>

{{-- resources/views/infracciones/publica.blade.php --}}
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acta de Contravención #{{ $acta->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .acta-header {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            padding: 2rem 0;
            text-align: center;
        }

        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;
            margin-bottom: 1.5rem;
        }

        .badge-oficial {
            background-color: #28a745;
            font-size: 0.9rem;
        }

        .info-item {
            border-bottom: 1px solid #eee;
            padding: 0.5rem 0;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .foto-acta {
            max-width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }
    </style>
</head>

<body>
    {{-- Header Oficial --}}
    <div class="acta-header">
        <div class="container">
            <h1><i class="fas fa-file-alt me-2"></i>ACTA DE CONTRAVENCIÓN</h1>
            <h2>N° {{ str_pad($acta->id, 8, '0', STR_PAD_LEFT) }}</h2>
            <p class="mb-0">Municipalidad de Cipolletti</p>
            <span class="badge badge-oficial">DOCUMENTO OFICIAL</span>
        </div>
    </div>

    <div class="container my-4">
        {{-- Información General --}}
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Información General</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-item">
                            <strong>Fecha y Hora:</strong> {{ $acta->fecha_hora->format('d/m/Y H:i') }}
                        </div>
                        <div class="info-item">
                            <strong>Tipo de Acta:</strong> {{ $acta->tipo_acta_descripcion }}
                        </div>
                        <div class="info-item">
                            <strong>Inspector:</strong> {{ $acta->usuario }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <strong>Ubicación:</strong> {{ $acta->ubicacion }}
                        </div>
                        <div class="info-item">
                            <strong>Destino:</strong> {{ $acta->destino_acta }}
                        </div>
                        <div class="info-item">
                            <strong>Estado:</strong> {{ $acta->estado ?? 'En Proceso' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Datos del Imputado --}}
        @if ($acta->persona)
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Datos del Imputado</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item">
                                <strong>DNI:</strong> {{ number_format($acta->persona->dni, 0, '', '.') }}
                            </div>
                            <div class="info-item">
                                <strong>Nombre Completo:</strong> {{ $acta->persona->nombre_completo }}
                            </div>
                            <div class="info-item">
                                <strong>Nacionalidad:</strong> {{ $acta->persona->nacionalidad ?? 'No especificada' }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            @if ($acta->persona->direccion_completa)
                                <div class="info-item">
                                    <strong>Dirección:</strong> {{ $acta->persona->direccion_completa }}
                                </div>
                            @endif
                            @if ($acta->persona->telefono)
                                <div class="info-item">
                                    <strong>Teléfono:</strong> {{ $acta->persona->telefono }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Datos del Vehículo --}}
        @if ($acta->vehiculo)
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-car me-2"></i>Datos del Vehículo</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item">
                                <strong>Dominio:</strong> {{ $acta->vehiculo->dominio }}
                            </div>
                            <div class="info-item">
                                <strong>Marca/Modelo:</strong> {{ $acta->vehiculo->marca_modelo }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            @if ($acta->vehiculo->color)
                                <div class="info-item">
                                    <strong>Color:</strong> {{ $acta->vehiculo->color }}
                                </div>
                            @endif
                            @if ($acta->vehiculo->tipo_vehiculo)
                                <div class="info-item">
                                    <strong>Tipo:</strong> {{ $acta->vehiculo->tipo_vehiculo }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Infracciones --}}
        @if ($acta->tipos->count() > 0)
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Infracciones Cometidas</h5>
                </div>
                <div class="card-body">
                    @foreach ($acta->tipos as $tipo)
                        <div class="alert alert-warning d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="alert-heading">Código {{ $tipo->tipoInfraccion->codigo }}</h6>
                                <p class="mb-0">{{ $tipo->tipoInfraccion->descripcion }}</p>
                            </div>
                            @if ($tipo->tipoInfraccion->sam > 0)
                                <span class="badge bg-danger">
                                    ${{ number_format($tipo->tipoInfraccion->sam, 2) }}
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Motivo y Observaciones --}}
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-comment me-2"></i>Motivo y Observaciones</h5>
            </div>
            <div class="card-body">
                @if ($acta->motivo)
                    <div class="mb-3">
                        <strong>Motivo:</strong>
                        <p class="mt-2">{{ $acta->motivo }}</p>
                    </div>
                @endif

                @if ($acta->observaciones)
                    <div class="mb-3">
                        <strong>Observaciones:</strong>
                        <p class="mt-2">{{ $acta->observaciones }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Documentación Fotográfica --}}
        @if ($acta->documentacion->count() > 0)
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-images me-2"></i>Documentación Fotográfica</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($acta->documentacion as $doc)
                            <div class="col-md-4 mb-3">
                                <img src="{{ $doc->url }}" class="foto-acta" alt="Foto del acta"
                                    onclick="mostrarImagenCompleta('{{ $doc->url }}')" style="cursor: pointer;">
                                <p class="text-muted small mt-1">{{ $doc->fecha_subida->format('d/m/Y H:i') }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- Footer Oficial --}}
        <div class="card">
            <div class="card-body text-center">
                <p class="mb-2"><strong>Este es un documento oficial emitido por la Municipalidad de
                        Cipolletti</strong></p>
                <p class="text-muted small mb-0">
                    Para consultas o reclamos, dirigirse a las oficinas municipales.<br>
                    Documento generado el: {{ now()->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>
    </div>

    {{-- Modal para imagen completa --}}
    <div class="modal fade" id="modalImagen" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Imagen del Acta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="imagenCompleta" src="" class="img-fluid" alt="Imagen completa">
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function mostrarImagenCompleta(url) {
            document.getElementById('imagenCompleta').src = url;
            new bootstrap.Modal(document.getElementById('modalImagen')).show();
        }
    </script>
</body>

</html>
