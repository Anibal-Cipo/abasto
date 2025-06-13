@extends('layouts.app')

@section('title', 'Buscar Introductor')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('inspector.index') }}">Inspector</a></li>
<li class="breadcrumb-item active">Buscar</li>
@endsection

@section('header')
<h1 class="h2"><i class="bi bi-search"></i> Buscar Introductor</h1>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('inspector.buscar.resultados') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="termino" class="form-label">
                            <i class="bi bi-search"></i> Buscar por CUIT o Razón Social
                        </label>
                        <input type="text" 
                               class="form-control form-control-lg @error('termino') is-invalid @enderror" 
                               id="termino" 
                               name="termino" 
                               value="{{ old('termino') }}" 
                               placeholder="Ej: 30-12345678-9 o Frigorífico San Martín"
                               autofocus
                               required>
                        @error('termino')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Ingresa al menos 3 caracteres para realizar la búsqueda
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-search"></i> Buscar Introductor
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Ayuda -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-lightbulb"></i> Consejos de Búsqueda
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Por CUIT:</h6>
                        <ul class="list-unstyled">
                            <li><i class="bi bi-check-circle text-success"></i> Formato: XX-XXXXXXXX-X</li>
                            <li><i class="bi bi-check-circle text-success"></i> Solo números: XXXXXXXXXXX</li>
                            <li><i class="bi bi-check-circle text-success"></i> Parcial: los últimos dígitos</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Por Razón Social:</h6>
                        <ul class="list-unstyled">
                            <li><i class="bi bi-check-circle text-success"></i> Nombre completo o parcial</li>
                            <li><i class="bi bi-check-circle text-success"></i> Sin distinguir mayúsculas</li>
                            <li><i class="bi bi-check-circle text-success"></i> Palabras clave</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection