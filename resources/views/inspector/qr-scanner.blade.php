@extends('layouts.app')

@section('title', 'Scanner QR')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('inspector.index') }}">Inspector</a></li>
<li class="breadcrumb-item active">Scanner QR</li>
@endsection

@section('header')
<h1 class="h2"><i class="bi bi-qr-code-scan"></i> Scanner de Código QR</h1>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-camera"></i> Cámara
                </h5>
            </div>
            <div class="card-body">
                <div id="reader" style="width: 100%;"></div>
                <div id="camera-status" class="mt-3">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando cámara...</span>
                        </div>
                        <p class="mt-2 text-muted">Iniciando cámara...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-info-circle"></i> Resultado
                </h5>
            </div>
            <div class="card-body">
                <div id="scan-result" class="text-center py-4">
                    <i class="bi bi-qr-code-scan display-1 text-muted"></i>
                    <h5 class="text-muted mt-3">Esperando escaneo...</h5>
                    <p class="text-muted">Apunta la cámara hacia un código QR de introducción</p>
                </div>
                
                <div id="loading-result" style="display: none;" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Buscando...</span>
                    </div>
                    <p class="mt-2 text-muted">Buscando información...</p>
                </div>
                
                <div id="result-content" style="display: none;"></div>
                
                <div id="error-result" style="display: none;" class="text-center py-4">
                    <i class="bi bi-exclamation-triangle display-1 text-danger"></i>
                    <h5 class="text-danger mt-3">Error</h5>
                    <p class="text-muted" id="error-message"></p>
                    <button type="button" class="btn btn-primary" onclick="resetScanner()">
                        <i class="bi bi-arrow-clockwise"></i> Intentar de nuevo
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Instrucciones -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-lightbulb"></i> Instrucciones
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li><i class="bi bi-check-circle text-success"></i> Asegúrate de tener buena iluminación</li>
                    <li><i class="bi bi-check-circle text-success"></i> Mantén el código QR centrado</li>
                    <li><i class="bi bi-check-circle text-success"></i> Espera a que la cámara enfoque</li>
                    <li><i class="bi bi-check-circle text-success"></i> El escaneo es automático</li>
                </ul>
            </div>
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
            <h6><i class="bi bi-truck"></i> Remito ${introduccion.numero_remito}</h6>
            <p class="mb-2">
                <strong>${introduccion.introductor.razon_social}</strong><br>
                <small class="text-muted">${introduccion.introductor.cuit_formateado}</small>
            </p>
            <p class="mb-2">
                <i class="bi bi-calendar"></i> ${new Date(introduccion.fecha).toLocaleDateString('es-AR')} 
                ${introduccion.hora}
            </p>
        </div>
        
        <div class="mt-3">
            <h6><i class="bi bi-box"></i> Stock Disponible</h6>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th class="text-center">Disponible</th>
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
                    <span class="badge ${badgeClass}">${stockText}</span>
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
            <a href="/introducciones/${introduccion.id}" class="btn btn-primary">
                <i class="bi bi-eye"></i> Ver Detalle Completo
            </a>
            <button type="button" class="btn btn-outline-primary" onclick="resetScanner()">
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