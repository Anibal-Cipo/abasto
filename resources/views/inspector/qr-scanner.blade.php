@extends('layouts.inspector')

@section('title', 'Scanner QR')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('inspector.index') }}">Inicio</a></li>
<li class="breadcrumb-item active">Scanner QR</li>
@endsection

@section('header')
<div class="text-center">
    <h2 class="h4 mb-1">
        <i class="bi bi-qr-code-scan text-info"></i> 
        Scanner de Código QR
    </h2>
    <p class="text-muted mb-0">Apunta la cámara hacia el código QR</p>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12 col-lg-6 mb-4">
        <div class="card-inspector">
            <div class="card-header bg-info text-white text-center">
                <h6 class="mb-0">
                    <i class="bi bi-camera"></i> Cámara
                </h6>
            </div>
            <div class="card-body p-2">
                <div id="reader" style="width: 100%; border-radius: 10px; overflow: hidden;"></div>
                <div id="camera-status" class="mt-3 text-center">
                    <div class="spinner-border text-info" role="status">
                        <span class="visually-hidden">Cargando cámara...</span>
                    </div>
                    <p class="mt-2 text-muted small">Iniciando cámara...</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-lg-6">
        <div class="card-inspector">
            <div class="card-header bg-success text-white text-center">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle"></i> Resultado
                </h6>
            </div>
            <div class="card-body">
                <div id="scan-result" class="text-center py-4">
                    <i class="bi bi-qr-code-scan display-1 text-muted"></i>
                    <h5 class="text-muted mt-3">Esperando escaneo...</h5>
                    <p class="text-muted">Apunta la cámara hacia un código QR de introducción</p>
                </div>
                
                <div id="loading-result" style="display: none;" class="text-center py-4">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Buscando...</span>
                    </div>
                    <p class="mt-2 text-muted">Buscando información...</p>
                </div>
                
                <div id="result-content" style="display: none;"></div>
                
                <div id="error-result" style="display: none;" class="text-center py-4">
                    <i class="bi bi-exclamation-triangle display-1 text-danger"></i>
                    <h5 class="text-danger mt-3">Error</h5>
                    <p class="text-muted" id="error-message"></p>
                    <button type="button" class="btn btn-inspector-primary" onclick="resetScanner()">
                        <i class="bi bi-arrow-clockwise"></i> Intentar de nuevo
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Instrucciones optimizadas para móvil -->
        <div class="card-inspector mt-3">
            <div class="card-header bg-light text-center">
                <h6 class="mb-0">
                    <i class="bi bi-lightbulb text-warning"></i> Instrucciones
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-2">
                        <i class="bi bi-brightness-high text-warning mb-1" style="font-size: 1.5rem;"></i>
                        <p class="small mb-0">Buena iluminación</p>
                    </div>
                    <div class="col-6 mb-2">
                        <i class="bi bi-bullseye text-info mb-1" style="font-size: 1.5rem;"></i>
                        <p class="small mb-0">QR centrado</p>
                    </div>
                    <div class="col-6">
                        <i class="bi bi-camera text-success mb-1" style="font-size: 1.5rem;"></i>
                        <p class="small mb-0">Esperar enfoque</p>
                    </div>
                    <div class="col-6">
                        <i class="bi bi-magic text-primary mb-1" style="font-size: 1.5rem;"></i>
                        <p class="small mb-0">Escaneo automático</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón volver -->
        <div class="d-grid mt-3">
            <a href="{{ route('inspector.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>
                Volver al Inicio
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
let html5QrcodeScanner;
let isScanning = false;

function initializeScanner() {
    const config = {
        fps: 10,
        qrbox: { width: 250, height: 250 },
        aspectRatio: 1.0
    };

    html5QrcodeScanner = new Html5QrcodeScanner("reader", config, false);
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    
    // Ocultar estado de carga después de inicializar
    setTimeout(() => {
        document.getElementById('camera-status').style.display = 'none';
    }, 2000);
}

function onScanSuccess(decodedText, decodedResult) {
    if (isScanning) return; // Evitar múltiples escaneos
    
    isScanning = true;
    console.log('QR Code detectado:', decodedText);
    
    // Mostrar loading
    showLoading();
    
    // Buscar información de la introducción
    buscarIntroduccion(decodedText);
}

function onScanFailure(error) {
    // Ignorar errores de escaneo (muy frecuentes)
}

function showLoading() {
    document.getElementById('scan-result').style.display = 'none';
    document.getElementById('result-content').style.display = 'none';
    document.getElementById('error-result').style.display = 'none';
    document.getElementById('loading-result').style.display = 'block';
}

function buscarIntroduccion(qrCode) {
    fetch(`/introducciones/qr/${encodeURIComponent(qrCode)}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                showError(data.error);
            } else {
                showResult(data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Error de conexión. Verifica tu internet.');
        })
        .finally(() => {
            setTimeout(() => {
                isScanning = false; // Permitir nuevo escaneo después de 3 segundos
            }, 3000);
        });
}

function showResult(data) {
    const introduccion = data.introduccion;
    const stockDisponible = data.stock_disponible;
    
    let html = `
        <div class="border rounded p-3 bg-light">
            <h6 class="text-center mb-3">
                <i class="bi bi-truck text-success"></i> 
                Remito ${introduccion.numero_remito}
            </h6>
            <div class="row mb-2">
                <div class="col-12">
                    <strong class="text-success">${introduccion.introductor.razon_social}</strong><br>
                    <small class="text-muted">${introduccion.introductor.cuit_formateado}</small>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-6">
                    <small><i class="bi bi-calendar"></i> ${new Date(introduccion.fecha).toLocaleDateString('es-AR')}</small>
                </div>
                <div class="col-6 text-end">
                    <small><i class="bi bi-clock"></i> ${introduccion.hora}</small>
                </div>
            </div>
        </div>
        
        <div class="mt-3">
            <h6 class="text-center mb-3">
                <i class="bi bi-box text-info"></i> 
                Stock Disponible
            </h6>
            <div class="table-responsive">
                <table class="table table-sm table-striped">
                    <thead class="table-light">
                        <tr>
                            <th class="small">Producto</th>
                            <th class="text-center small">Disponible</th>
                        </tr>
                    </thead>
                    <tbody>
    `;
    
    stockDisponible.forEach(item => {
        const badgeClass = item.stock_disponible > 0 ? 'bg-success' : 'bg-secondary';
        const stockText = item.stock_disponible > 0 ? 
            `${item.stock_disponible} ${item.producto.unidad_secundaria}` : 'Agotado';
        
        html += `
            <tr>
                <td><small>${item.producto.nombre}</small></td>
                <td class="text-center">
                    <span class="badge ${badgeClass} small">${stockText}</span>
                </td>
            </tr>
        `;
    });
    
    html += `
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="mt-3 d-grid gap-2">
            <a href="/introducciones/${introduccion.id}" class="btn btn-inspector-primary">
                <i class="bi bi-eye"></i> Ver Detalle Completo
            </a>
            <button type="button" class="btn btn-outline-secondary" onclick="resetScanner()">
                <i class="bi bi-arrow-clockwise"></i> Escanear Otro QR
            </button>
        </div>
    `;
    
    document.getElementById('loading-result').style.display = 'none';
    document.getElementById('result-content').innerHTML = html;
    document.getElementById('result-content').style.display = 'block';
}

function showError(message) {
    document.getElementById('loading-result').style.display = 'none';
    document.getElementById('error-message').textContent = message;
    document.getElementById('error-result').style.display = 'block';
}

function resetScanner() {
    document.getElementById('result-content').style.display = 'none';
    document.getElementById('error-result').style.display = 'none';
    document.getElementById('loading-result').style.display = 'none';
    document.getElementById('scan-result').style.display = 'block';
    isScanning = false;
}

// Inicializar scanner cuando la página cargue
document.addEventListener('DOMContentLoaded', function() {
    initializeScanner();
});

// Limpiar recursos cuando se salga de la página
window.addEventListener('beforeunload', function() {
    if (html5QrcodeScanner) {
        html5QrcodeScanner.clear();
    }
});
</script>
@endpush