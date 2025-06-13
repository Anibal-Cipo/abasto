@extends('layouts.app')

@section('title', 'Introductores')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Introductores</li>
@endsection

@section('header')
<h1 class="h2"><i class="bi bi-people"></i> Introductores</h1>
<div class="btn-toolbar mb-2 mb-md-0">
    @if(Auth::user()->puedeEditar())
    <a href="{{ route('introductores.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nuevo Introductor
    </a>
    @endif
</div>
@endsection

@section('content')
<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('introductores.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="buscar" class="form-label">Buscar</label>
                <input type="text" class="form-control" id="buscar" name="buscar" 
                       value="{{ request('buscar') }}" placeholder="Razón social o CUIT">
            </div>
            <div class="col-md-3">
                <label for="activo" class="form-label">Estado</label>
                <select class="form-select" id="activo" name="activo">
                    <option value="">Todos</option>
                    <option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Activos</option>
                    <option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Inactivos</option>
                </select>
            </div>
            <div class="col-md-5 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-outline-primary">
                    <i class="bi bi-search"></i> Buscar
                </button>
                <a href="{{ route('introductores.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-clockwise"></i> Limpiar
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Lista de introductores -->
<div class="card">
    <div class="card-body p-0">
        @if($introductores->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="introductoresTable">
                <thead>
                    <tr>
                        <th>CUIT</th>
                        <th>Razón Social</th>
                        <th>Contacto</th>
                        <th>Habilitación</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center no-sort">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($introductores as $introductor)
                    <tr>
                        <td>
                            <code>{{ $introductor->cuit_formateado }}</code>
                        </td>
                        <td>
                            <strong>{{ $introductor->razon_social }}</strong><br>
                            <small class="text-muted">{{ Str::limit($introductor->direccion, 50) }}</small>
                        </td>
                        <td>
                            @if($introductor->telefono)
                                <i class="bi bi-telephone"></i> {{ $introductor->telefono }}<br>
                            @endif
                            @if($introductor->email)
                                <i class="bi bi-envelope"></i> {{ $introductor->email }}
                            @endif
                        </td>
                        <td>
                            @if($introductor->habilitacion_municipal)
                                <span class="badge bg-success">{{ $introductor->habilitacion_municipal }}</span>
                            @else
                                <span class="badge bg-warning">Sin habilitación</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($introductor->activo)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-secondary">Inactivo</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('introductores.show', $introductor) }}" 
                                   class="btn btn-sm btn-outline-primary" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(Auth::user()->puedeEditar())
                                <a href="{{ route('introductores.edit', $introductor) }}" 
                                   class="btn btn-sm btn-outline-warning" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-between align-items-center p-3">
            <div>
                Mostrando {{ $introductores->firstItem() }} a {{ $introductores->lastItem() }} 
                de {{ $introductores->total() }} resultados
            </div>
            {{ $introductores->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-people display-1 text-muted"></i>
            <h4 class="text-muted mt-3">No se encontraron introductores</h4>
            <p class="text-muted">Los introductores que registres aparecerán aquí.</p>
            @if(Auth::user()->puedeEditar())
            <a href="{{ route('introductores.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Crear Primer Introductor
            </a>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#introductoresTable').DataTable({
        searching: false,
        info: false,
        paging: false,
        columnDefs: [
            { targets: [4, 5], className: 'text-center' }
        ]
    });
});
</script>
@endpush