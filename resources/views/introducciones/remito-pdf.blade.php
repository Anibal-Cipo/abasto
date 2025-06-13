<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Remito - {{ $introduccion->numero_remito }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.3;
            color: #333;
        }

        .remito-container {
            width: 100%;
            max-width: 210mm;
            height: 148mm;
            /* Media página A4 */
            margin: 0 auto;
            padding: 15px;
            border: 2px solid #333;
            position: relative;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .header .subtitle {
            font-size: 12px;
            color: #666;
        }

        .main-content {
            width: 100%;
            position: relative;
        }

        .left-section {
            width: 70%;
            float: left;
        }

        .right-section {
            width: 28%;
            float: right;
            text-align: center;
        }

        .info-row {
            width: 100%;
            margin-bottom: 8px;
            clear: both;
        }

        .info-row .label {
            font-weight: bold;
            width: 80px;
            float: left;
        }

        .info-row .value {
            margin-left: 85px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 2px;
        }

        .section-title {
            background-color: #f5f5f5;
            padding: 5px;
            font-weight: bold;
            border: 1px solid #ccc;
            margin: 10px 0 5px 0;
            text-align: center;
        }

        .productos-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 10px;
        }

        .productos-table th,
        .productos-table td {
            border: 1px solid #333;
            padding: 4px;
            text-align: left;
        }

        .productos-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }

        .qr-section {
            border: 1px solid #333;
            padding: 10px;
            text-align: center;
        }

        .qr-code {
            width: 100px;
            height: 100px;
            margin: 0 auto 10px;
            border: 1px solid #ccc;
        }

        .qr-text {
            font-size: 9px;
            word-break: break-all;
            margin-top: 5px;
        }

        .footer {
            clear: both;
            margin-top: 20px;
            border-top: 1px solid #333;
            padding-top: 8px;
            font-size: 9px;
            text-align: center;
            color: #666;
        }

        .observaciones {
            margin-top: 10px;
            border: 1px solid #ccc;
            padding: 5px;
            min-height: 30px;
        }

        .flex-row {
            width: 100%;
            margin-bottom: 10px;
            clear: both;
        }

        .flex-col {
            width: 48%;
            float: left;
            margin-right: 4%;
        }

        .flex-col:last-child {
            margin-right: 0;
        }
    </style>
</head>

<body>
    <div class="remito-container">
        <!-- Header -->
        <div class="header">
            <h1>REMITO DE INTRODUCCIÓN</h1>
            <div class="subtitle">Sistema de Gestión Frigorífica</div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="left-section">
                <!-- Datos del Remito -->
                <div class="flex-row">
                    <div class="flex-col">
                        <div class="info-row">
                            <span class="label">N° Remito:</span>
                            <span class="value"><strong>{{ $introduccion->numero_remito }}</strong></span>
                        </div>
                        <div class="info-row">
                            <span class="label">Fecha:</span>
                            <span class="value">{{ $introduccion->fecha->format('d/m/Y') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Hora:</span>
                            <span class="value">{{ substr($introduccion->hora, 0, 5) }}</span>
                        </div>
                    </div>
                    <div class="flex-col">
                        <div class="info-row">
                            <span class="label">Vehículo:</span>
                            <span class="value">{{ $introduccion->vehiculo ?? '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Dominio:</span>
                            <span class="value">{{ $introduccion->dominio ?? '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Temperatura:</span>
                            <span
                                class="value">{{ $introduccion->temperatura ? $introduccion->temperatura . '°C' : '-' }}</span>
                        </div>
                    </div>
                </div>

                <div style="clear: both;"></div>

                <!-- Introductor -->
                <div class="section-title">INTRODUCTOR</div>
                <div class="info-row">
                    <span class="label">Razón Social:</span>
                    <span class="value">{{ $introduccion->introductor->razon_social }}</span>
                </div>
                <div class="info-row">
                    <span class="label">CUIT:</span>
                    <span class="value">{{ $introduccion->introductor->cuit_formateado }}</span>
                </div>

                @if ($introduccion->receptores)
                    <!-- Receptor -->
                    <div class="section-title">RECEPTOR</div>
                    <div class="info-row">
                        <span class="label">Receptor:</span>
                        <span class="value">{{ $introduccion->receptores }}</span>
                    </div>
                @endif

                <!-- Productos -->
                <div class="section-title">PRODUCTOS</div>
                <table class="productos-table">
                    <thead>
                        <tr>
                            <th style="width: 50%">Producto</th>
                            <th style="width: 15%">Cant. 1</th>
                            <th style="width: 15%">Cant. 2</th>
                            <th style="width: 20%">Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($introduccion->productos as $producto)
                            <tr>
                                <td>{{ $producto->producto->nombre }}</td>
                                <td style="text-align: center;">
                                    {{ $producto->cantidad_primaria ? number_format($producto->cantidad_primaria, 2) : '-' }}
                                </td>
                                <td style="text-align: center;">
                                    {{ number_format($producto->cantidad_secundaria, 2) }}
                                </td>
                                <td>{{ $producto->observaciones ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Right Section - QR Code -->
            <div class="right-section">
                <div class="qr-section">
                    <div style="font-weight: bold; margin-bottom: 10px;">CÓDIGO QR</div>
                    <div class="qr-code">
                        <!-- El QR se insertará aquí -->
                        <img src="data:image/png;base64,{{ $qrCode }}" style="width: 100%; height: 100%;"
                            alt="QR Code">
                    </div>
                    <div class="qr-text">{{ $introduccion->qr_code }}</div>
                    <div style="margin-top: 15px; font-size: 9px;">
                        <strong>Para Inspectores:</strong><br>
                        Escanee este código para acceder a la información completa
                    </div>
                </div>

                @if ($introduccion->pt_numero || $introduccion->ptr_numero)
                    <div style="margin-top: 15px; border: 1px solid #ccc; padding: 8px;">
                        <div style="font-weight: bold; font-size: 10px; margin-bottom: 5px;">PERMISOS</div>
                        @if ($introduccion->pt_numero)
                            <div style="font-size: 9px;">P.T. N°: {{ $introduccion->pt_numero }}</div>
                        @endif
                        @if ($introduccion->ptr_numero)
                            <div style="font-size: 9px;">P.T.R. N°: {{ $introduccion->ptr_numero }}</div>
                        @endif
                    </div>
                @endif
            </div>

            <div style="clear: both;"></div>
        </div>

        <!-- Observaciones -->
        @if ($introduccion->observaciones)
            <div class="section-title">OBSERVACIONES</div>
            <div class="observaciones">
                {{ $introduccion->observaciones }}
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <div>Usuario: {{ $introduccion->usuario->name ?? 'Sistema' }} |
                Generado: {{ now()->format('d/m/Y H:i') }} |
                ID: {{ $introduccion->id }}</div>
        </div>
    </div>
</body>

</html>
