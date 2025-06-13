@extends('layouts.app')

@section('title', 'Inspector - Dashboard')

@section('header')
<h1 class="h2"><i class="bi bi-search"></i> Inspector - Dashboard</h1>
<div class="btn-toolbar mb-2 mb-md-0">
    <div class="btn-group me-2">
        <a href="{{ route('inspector.buscar') }}" class="btn btn-primary">
            <i class="bi bi-search"></i> Buscar Introductor
        </a>
        <a href="{{ route('inspector.qr.scanner') }}" class="btn btn-info">
            <i class="bi bi-qr-code-scan"></i> Escanear QR
        </a>
    </div>
</div>
@endsection

@section('content')
<!-- Estadísticas para inspector -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="bi bi-truck display-4"></i>
                <h3 class="card-title mt-2">{{ $stats['introducciones_hoy'] }}</h3>
                <p class="card-text">Introducciones Hoy</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="bi bi-boxes display-4"></i>
                <h3 class="card-title mt-2">{{ $stats['introducciones_con_stock'] }}</h3>
                <p class="card-text">Con Stock Disponible</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stats-card">
            <div class="card-body text-center">
                <i class="bi bi-people display-4"></i>
                <h3 class="card-title mt-2">{{ $stats['introductores_activos'] }}</h3>
                <p class="card-text">Introductores Activos</p>
            </div>
        </div>
    </div>
</div>

<!-- Herramientas de inspector -->
<div class="row">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-search display-1 text-primary mb-3"></i>
                <h4 class="card-title">Buscar Introductor</h4>
                <p class="card-text text-muted">
                    Busca un introductor por razón social o CUIT para ver sus últimas 5 introducciones y estado de stock.
                </p>
                <a href="{{ route('inspector.buscar') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-search"></i> Iniciar Búsqueda
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="bi bi-qr-code-scan display-1 text-info mb-3"></i>
                <h4 class="card-title">Escanear Código QR</h4>
                <p class="card-text text-muted">
                    Escanea el código QR de una introducción para ver todos los detalles y stock disponible al instante.
                </p>
                <a href="{{ route('inspector.qr.scanner') }}" class="btn btn-info btn-lg">
                    <i class="bi bi-qr-code-scan"></i> Abrir Scanner
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Instrucciones -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-info-circle"></i> Instrucciones de Uso
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="bi bi-1-circle text-primary"></i> Búsqueda por Introductor</h6>
                        <ul class="list-unstyled ms-3">
                            <li><i class="bi bi-check-circle text-success"></i> Ingresa el CUIT o razón social</li>
                            <li><i class="bi bi-check-circle text-success"></i> Ve las últimas 5 introducciones</li>
                            <li><i class="bi bi-check-circle text-success"></i> Verifica stock disponible</li>
                            <li><i class="bi bi-check-circle text-success"></i> Accede a detalles completos</li>
                        </ul>
                    </div>
                    
                    <div class="col-md-6">
                        <h6><i class="bi bi-2-circle text-info"></i> Escaneo QR</h6>
                        <ul class="list-unstyled ms-3">
                            <li><i class="bi bi-check-circle text-success"></i> Apunta la cámara al código QR</li>
                            <li><i class="bi bi-check-circle text-success"></i> Obtén información instantánea</li>
                            <li><i class="bi bi-check-circle text-success"></i> Ve productos y cantidades</li>
                            <li><i class="bi bi-check-circle text-success"></i> Controla redespachos realizados</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection