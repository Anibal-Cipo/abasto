@extends('layouts.inspector')

@section('title', 'Buscar Introductor')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('inspector.index') }}">Inicio</a></li>
<li class="breadcrumb-item active">Buscar</li>
@endsection

@section('header')
<div class="text-center">
    <h2 class="h4 mb-1">
        <i class="bi bi-search text-success"></i> 
        Buscar Introductor
    </h2>
    <p class="text-muted mb-0">Ingresa el CUIT o razón social para buscar</p>
</div>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8">
        <div class="card-inspector">
            <div class="card-body p-4">
                <form action="{{ route('inspector.buscar.resultados') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="termino" class="form-label h6">
                            <i class="bi bi-search me-2"></i>Término de Búsqueda
                        </label>
                        <input type="text" 
                               class="form-control form-control-lg @error('termino') is-invalid @enderror" 
                               id="termino" 
                               name="termino" 
                               value="{{ old('termino') }}" 
                               placeholder="Ej: 30-12345678-9 o Frigorífico San Martín"
                               autofocus
                               required
                               style="border-radius: 15px; padding: 1rem;">
                        @error('termino')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text mt-2">
                            <i class="bi bi-info-circle"></i>
                            Ingresa al menos 3 caracteres para realizar la búsqueda
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-inspector-primary btn-lg">
                            <i class="bi bi-search me-2"></i>
                            Buscar Introductor
                        </button>
                        <a href="{{ route('inspector.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>
                            Volver al Inicio
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Ayuda optimizada para móvil -->
        <div class="card-inspector mt-4">
            <div class="card-header bg-light text-center">
                <h6 class="mb-0">
                    <i class="bi bi-lightbulb text-warning"></i> 
                    Consejos de Búsqueda
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-6 mb-3">
                        <div class="p-3 bg-light rounded">
                            <h6 class="text-success">
                                <i class="bi bi-credit-card-2-front"></i> 
                                Por CUIT
                            </h6>
                            <ul class="list-unstyled small">
                                <li><i class="bi bi-check text-success"></i> 30-12345678-9</li>
                                <li><i class="bi bi-check text-success"></i> 30123456789</li>
                                <li><i class="bi bi-check text-success"></i> Solo últimos dígitos</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="p-3 bg-light rounded">
                            <h6 class="text-info">
                                <i class="bi bi-building"></i> 
                                Por Razón Social
                            </h6>
                            <ul class="list-unstyled small">
                                <li><i class="bi bi-check text-success"></i> Nombre completo</li>
                                <li><i class="bi bi-check text-success"></i> Nombre parcial</li>
                                <li><i class="bi bi-check text-success"></i> Palabras clave</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection