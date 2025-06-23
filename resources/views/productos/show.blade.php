@extends('layouts.app')

@section('title', 'Detalle del Producto')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('productos.index') }}">Productos</a></li>
    <li class="breadcrumb-item active">{{ $producto->nombre }}</li>
@endsection

@section('header')
    <div>
        <h1 class="h2">
            <i class="bi bi-box"></i> {{ $producto->nombre }}
            @if ($producto->activo)
                <span class="badge bg-success ms-2">Activo</span>
            @else
                <span class="badge bg-secondary ms-2">Inactivo</span>
            @endif
        </h1>
        <p class="text-muted mb-0">{{ $producto->categoria }}</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('productos.edit', $producto) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Editar
            </a>
            <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver a Lista
            </a>
        </div>
        <div class="btn-group">
            <form action="{{ route('productos.destroy', $producto) }}" method="POST" class="d-inline"
                onsubmit="return confirm('¿Estás seguro de eliminar este producto? Esta acción no se puede deshacer.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger"
                    @if ($stats['total_introducciones'] > 0) disabled title="No se puede eliminar: tiene movimientos" @endif>
                    <i class="bi bi-trash"></i> Eliminar
                </button>
            </form>
        </div>
    </div>
@endsection

@section('content')
    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body text-center">
                    <i class="bi bi-truck display-4 text-primary"></i>
                    <h3 class="card-title mt-2">{{ $stats['total_introducciones'] }}</h3>
                    <p class="card-text">Introducciones</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body text-center">
                    <i class="bi bi-box-arrow-in-down display-4 text-success"></i>
                    <h3 class="card-title mt-2">{{ number_format($stats['cantidad_total_introducida'], 2) }}</h3>
                    <p class="card-text">{{ $producto->unidad_secundaria }} Introducidos</p>
                </div>
            </div>
        </div>
        <div class="
