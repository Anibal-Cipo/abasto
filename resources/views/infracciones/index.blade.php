@extends('layouts.app')

@section('title', 'Dashboard Infracciones')

@section('content')
    <div class="container-fluid">
        {{-- Header del Inspector --}}
        <div class="row">
            <div class="col-12">
                <div class="card bg-danger text-white mb-3">
                    <div class="card-body text-center">
                        <h4 class="mb-1">
                            <i class="fas fa-file-alt"></i> Infracciones
                        </h4>
                        <p class="mb-0">Inspector: {{ auth()->user()->name }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Estadísticas --}}
        <div class="row mb-4">
            <div class="col-4">
                <div class="card text-center bg-primary text-white">
                    <div class="card-body">
                        <i class="fas fa-calendar-day fa-2x mb-2"></i>
                        <h2 class="mb-0">{{ $stats['actas_hoy'] }}</h2>
                        <small>Hoy</small>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card text-center bg-success text-white">
                    <div class="card-body">
                        <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                        <h2 class="mb-0">{{ $stats['actas_mes'] }}</h2>
                        <small>Este Mes</small>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card text-center bg-info text-white">
                    <div class="card-body">
                        <i class="fas fa-chart-line fa-2x mb-2"></i>
                        <h2 class="mb-0">{{ $stats['actas_total'] }}</h2>
                        <small>Total</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Botones de Acción --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-grid gap-2">
                    <a href="{{ route('infracciones.create') }}" class="btn btn-danger btn-lg">
                        <i class="fas fa-plus me-2"></i>
                        Nueva Acta de Contravención
                    </a>

                    <a href="{{ route('infracciones.mis-actas') }}" class="btn btn-info btn-lg">
                        <i class="fas fa-list me-2"></i>
                        Mis Actas ({{ $stats['actas_total'] }})
                    </a>

                    <a href="{{ route('inspector.index') }}" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-arrow-left me-2"></i>
                        Volver al Dashboard Principal
                    </a>
                </div>
            </div>
        </div>

        {{-- Últimas Actas --}}
        @if ($ultimasActas && $ultimasActas->count() > 0)
            <div class="row">
                <div class="col-12">
                    <h5>Últimas Actas Creadas</h5>
                    <div class="list-group">
                        @foreach ($ultimasActas->take(5) as $acta)
                            <a href="{{ route('infracciones.show', $acta) }}"
                                class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold">
                                            Acta #{{ $acta->id }}
                                            @if ($acta->persona)
                                                - {{ $acta->persona->nombre_completo }}
                                            @endif
                                        </div>
                                        <small class="text-muted">
                                            @if ($acta->vehiculo)
                                                {{ $acta->vehiculo->dominio }} -
                                            @endif
                                            {{ $acta->fecha_hora->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                    <span
                                        class="badge bg-{{ $acta->tipo_acta == 'A' ? 'success' : 'primary' }} rounded-pill">
                                        {{ $acta->tipo_acta }}
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-info">
                        <h5><i class="fas fa-info-circle me-2"></i>¡Bienvenido al Sistema de Infracciones!</h5>
                        <p class="mb-0">Aún no has creado ninguna acta. Haz clic en "Nueva Acta de Contravención" para
                            comenzar.</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Información del Sistema --}}
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-success">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0">Estado del Sistema</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-success mb-1">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <strong>Conexión:</strong> Activa
                                </p>
                                <p class="text-success mb-1">
                                    <i class="fas fa-database me-2"></i>
                                    <strong>Base de Datos:</strong> {{ number_format(\App\Models\Acta::count()) }} actas
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-success mb-1">
                                    <i class="fas fa-users me-2"></i>
                                    <strong>Personas:</strong> {{ number_format(\App\Models\Persona::count()) }}
                                </p>
                                <p class="text-success mb-1">
                                    <i class="fas fa-car me-2"></i>
                                    <strong>Vehículos:</strong> {{ number_format(\App\Models\Vehiculo::count()) }}
                                </p>
                            </div>
                        </div>
                        <p class="text-muted mb-0 mt-2">
                            <small>Centro de Costo: 624 - DPTO. ABASTO E INTRODUCCION</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
