{{-- resources/views/infracciones/mis-actas.blade.php --}}
@extends('layouts.app')

@section('title', 'Mis Actas de Contravención')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                {{-- Header --}}
                <div class="card mb-3">
                    <div class="card-header bg-info text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-list me-2"></i>
                            Mis Actas de Contravención
                        </h4>
                    </div>
                </div>

                {{-- Filtros --}}
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fas fa-filter me-2"></i>
                            Filtros de Búsqueda
                        </h6>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('infracciones.mis-actas') }}">
                            <div class="row">
                                <div class="col-md-3 mb-2">
                                    <label class="form-label">Fecha Desde</label>
                                    <input type="date" class="form-control" name="fecha_desde"
                                        value="{{ request('fecha_desde') }}">
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label">Fecha Hasta</label>
                                    <input type="date" class="form-control" name="fecha_hasta"
                                        value="{{ request('fecha_hasta') }}">
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label">Tipo de Acta</label>
                                    <select class="form-select" name="tipo_acta">
                                        <option value="">Todos</option>
                                        <option value="A" {{ request('tipo_acta') == 'A' ? 'selected' : '' }}>Abasto
                                        </option>
                                        <option value="B" {{ request('tipo_acta') == 'B' ? 'selected' : '' }}>
                                            Bromatología</option>
                                        <option value="C" {{ request('tipo_acta') == 'C' ? 'selected' : '' }}>Comercio
                                        </option>
                                        <option value="T" {{ request('tipo_acta') == 'T' ? 'selected' : '' }}>Tránsito
                                        </option>
                                        <option value="S" {{ request('tipo_acta') == 'S' ? 'selected' : '' }}>Sanidad
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label">DNI</label>
                                    <input type="number" class="form-control" name="dni" value="{{ request('dni') }}"
                                        placeholder="DNI">
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label">Dominio</label>
                                    <input type="text" class="form-control text-uppercase" name="dominio"
                                        value="{{ request('dominio') }}" placeholder="ABC123">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-1"></i>
                                        Buscar
                                    </button>
                                    <a href="{{ route('infracciones.mis-actas') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-1"></i>
                                        Limpiar
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Lista de Actas --}}
                <div class="card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            Resultados ({{ $actas->total() }} actas encontradas)
                        </h6>
                        <a href="{{ route('infracciones.create') }}" class="btn btn-danger btn-sm">
                            <i class="fas fa-plus me-1"></i>
                            Nueva Acta
                        </a>
                    </div>
                    <div class="card-body p-0">
                        @if ($actas->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Fecha/Hora</th>
                                            <th>Tipo</th>
                                            <th>Imputado</th>
                                            <th>Vehículo</th>
                                            <th>Ubicación</th>
                                            <th>Estado</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($actas as $acta)
                                            <tr>
                                                <td>
                                                    <strong>#{{ $acta->id }}</strong>
                                                </td>
                                                <td>
                                                    <div>{{ $acta->fecha_hora->format('d/m/Y') }}</div>
                                                    <small
                                                        class="text-muted">{{ $acta->fecha_hora->format('H:i') }}</small>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $acta->tipo_acta == 'A' ? 'success' : ($acta->tipo_acta == 'T' ? 'primary' : 'secondary') }}">
                                                        {{ $acta->tipo_acta_descripcion }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($acta->persona)
                                                        <div>{{ $acta->persona->nombre_completo }}</div>
                                                        <small class="text-muted">DNI:
                                                            {{ number_format($acta->persona->dni, 0, '', '.') }}</small>
                                                    @else
                                                        <span class="text-muted">Sin datos</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($acta->vehiculo)
                                                        <div><strong>{{ $acta->vehiculo->dominio }}</strong></div>
                                                        <small
                                                            class="text-muted">{{ $acta->vehiculo->marca_modelo }}</small>
                                                    @else
                                                        <span class="text-muted">Sin vehículo</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <small>{{ \Str::limit($acta->ubicacion, 30) }}</small>
                                                </td>
                                                <td>
                                                    @php
                                                        $estadoClase = match ($acta->estado) {
                                                            'Finalizada' => 'success',
                                                            'En Proceso' => 'warning',
                                                            'Rechazada' => 'danger',
                                                            default => 'secondary',
                                                        };
                                                    @endphp
                                                    <span class="badge bg-{{ $estadoClase }}">
                                                        {{ $acta->estado ?? 'Pendiente' }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group-vertical btn-group-sm" role="group">
                                                        <a href="{{ route('infracciones.show', $acta) }}"
                                                            class="btn btn-outline-primary btn-sm">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('infracciones.imprimir-termica', $acta) }}"
                                                            class="btn btn-outline-success btn-sm" target="_blank">
                                                            <i class="fas fa-print"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Paginación --}}
                            @if ($actas->hasPages())
                                <div class="card-footer">
                                    {{ $actas->links() }}
                                </div>
                            @endif
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No se encontraron actas</h5>
                                <p class="text-muted">No hay actas que coincidan con los filtros seleccionados.</p>
                                <a href="{{ route('infracciones.create') }}" class="btn btn-danger">
                                    <i class="fas fa-plus me-2"></i>
                                    Crear Primera Acta
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Botón Volver --}}
                <div class="mt-3">
                    <a href="{{ route('infracciones.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Volver al Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- resources/views/inspector/index.blade.php --}}
{{-- Actualizar el dashboard del inspector para incluir las nuevas opciones --}}
@extends('layouts.app')

@section('title', 'Dashboard Inspector')

@section('content')
    <div class="container-fluid">
        {{-- Header del Inspector --}}
        <div class="row">
            <div class="col-12">
                <div class="card bg-success text-white mb-3">
                    <div class="card-body text-center">
                        <h4 class="mb-1">
                            <i class="fas fa-user-shield"></i> Inspector
                        </h4>
                        <p class="mb-0">{{ auth()->user()->name }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Botones de Acción Principales --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-grid gap-2">
                    {{-- Buscar Introductor --}}
                    <a href="{{ route('inspector.buscar-introductor') }}" class="btn btn-outline-success btn-lg">
                        <i class="fas fa-search me-2"></i>
                        Buscar Introductor
                    </a>

                    {{-- Escanear QR --}}
                    <a href="{{ route('inspector.escanear-qr') }}" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-qrcode me-2"></i>
                        Escanear QR
                    </a>

                    {{-- Nueva Acta de Contravención --}}
                    <a href="{{ route('infracciones.create') }}" class="btn btn-danger btn-lg">
                        <i class="fas fa-file-alt me-2"></i>
                        Acta de Contravención
                    </a>

                    {{-- Mis Actas --}}
                    <a href="{{ route('infracciones.mis-actas') }}" class="btn btn-info btn-lg">
                        <i class="fas fa-list me-2"></i>
                        Mis Actas
                    </a>
                </div>
            </div>
        </div>

        {{-- Estadísticas --}}
        <div class="row mb-4">
            <div class="col-12">
                <h5 class="text-center mb-3">Dashboard del Inspector</h5>
                <p class="text-muted text-center">Estadísticas del sistema en tiempo real</p>
            </div>
        </div>

        {{-- Estadísticas de Abasto --}}
        <div class="row mb-3">
            <div class="col-12">
                <h6 class="text-muted">Sistema de Abasto</h6>
            </div>
        </div>
        <div class="row mb-4">
            {{-- Introducciones de Hoy --}}
            <div class="col-4">
                <div class="card text-center bg-primary text-white">
                    <div class="card-body">
                        <i class="fas fa-truck fa-2x mb-2"></i>
                        <h2 class="mb-0">{{ $stats['introducciones_hoy'] ?? 0 }}</h2>
                        <small>Hoy</small>
                    </div>
                </div>
            </div>

            {{-- Con Stock --}}
            <div class="col-4">
                <div class="card text-center bg-success text-white">
                    <div class="card-body">
                        <i class="fas fa-boxes fa-2x mb-2"></i>
                        <h2 class="mb-0">{{ $stats['con_stock'] ?? 0 }}</h2>
                        <small>Con Stock</small>
                    </div>
                </div>
            </div>

            {{-- Introductores Activos --}}
            <div class="col-4">
                <div class="card text-center bg-info text-white">
                    <div class="card-body">
                        <i class="fas fa-users fa-2x mb-2"></i>
                        <h2 class="mb-0">{{ $stats['introductores_activos'] ?? 0 }}</h2>
                        <small>Activos</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Estadísticas de Infracciones --}}
        <div class="row mb-3">
            <div class="col-12">
                <h6 class="text-muted">Infracciones y Contravenciones</h6>
            </div>
        </div>
        <div class="row mb-4">
            {{-- Actas de Hoy --}}
            <div class="col-4">
                <div class="card text-center bg-warning text-dark">
                    <div class="card-body">
                        <i class="fas fa-file-alt fa-2x mb-2"></i>
                        <h2 class="mb-0">{{ $stats['actas_hoy'] ?? 0 }}</h2>
                        <small>Actas Hoy</small>
                    </div>
                </div>
            </div>

            {{-- Actas del Mes --}}
            <div class="col-4">
                <div class="card text-center bg-danger text-white">
                    <div class="card-body">
                        <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                        <h2 class="mb-0">{{ $stats['actas_mes'] ?? 0 }}</h2>
                        <small>Este Mes</small>
                    </div>
                </div>
            </div>

            {{-- Total de Actas --}}
            <div class="col-4">
                <div class="card text-center bg-dark text-white">
                    <div class="card-body">
                        <i class="fas fa-chart-bar fa-2x mb-2"></i>
                        <h2 class="mb-0">{{ $stats['actas_total'] ?? 0 }}</h2>
                        <small>Total</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Accesos Rápidos --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fas fa-bolt me-2"></i>
                            Accesos Rápidos
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 mb-2">
                                <a href="{{ route('infracciones.mis-actas') }}?fecha_desde={{ now()->format('Y-m-d') }}"
                                    class="btn btn-outline-info btn-sm w-100">
                                    <i class="fas fa-calendar-day me-1"></i>
                                    Actas de Hoy
                                </a>
                            </div>
                            <div class="col-6 mb-2">
                                <a href="{{ route('introducciones.index') }}"
                                    class="btn btn-outline-success btn-sm w-100">
                                    <i class="fas fa-truck me-1"></i>
                                    Ver Introducciones
                                </a>
                            </div>
                            <div class="col-6 mb-2">
                                <a href="{{ route('infracciones.create') }}" class="btn btn-outline-danger btn-sm w-100">
                                    <i class="fas fa-plus me-1"></i>
                                    Nueva Acta
                                </a>
                            </div>
                            <div class="col-6 mb-2">
                                <a href="{{ route('inspector.buscar-introductor') }}"
                                    class="btn btn-outline-primary btn-sm w-100">
                                    <i class="fas fa-search me-1"></i>
                                    Buscar por CUIT
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Últimas Actividades --}}
        @if (isset($ultimasActas) && $ultimasActas->count() > 0)
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="fas fa-clock me-2"></i>
                                Últimas Actas Creadas
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                @foreach ($ultimasActas->take(5) as $acta)
                                    <a href="{{ route('infracciones.show', $acta) }}"
                                        class="list-group-item list-group-item-action">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="ms-2 me-auto">
                                                <div class="fw-bold">
                                                    Acta #{{ $acta->id }} -
                                                    {{ $acta->persona->nombre_completo ?? 'Sin persona' }}
                                                </div>
                                                <small class="text-muted">
                                                    {{ $acta->vehiculo->dominio ?? 'Sin vehículo' }} -
                                                    {{ $acta->fecha_hora->format('d/m/Y H:i') }}
                                                </small>
                                            </div>
                                            <span
                                                class="badge bg-{{ $acta->tipo_acta == 'A' ? 'success' : 'primary' }} rounded-pill">
                                                {{ $acta->tipo_acta_descripcion }}
                                            </span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                            @if ($ultimasActas->count() > 5)
                                <div class="text-center mt-3">
                                    <a href="{{ route('infracciones.mis-actas') }}"
                                        class="btn btn-outline-primary btn-sm">
                                        Ver todas las actas
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
