@extends('layouts.app')

@section('title', 'Productos')

@section('header')
    <div>
        <h1 class="h2"><i class="bi bi-box"></i> Gestión de Productos</h1>
        <p class="text-muted mb-0">Administra los productos del sistema</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('productos.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nuevo Producto
            </a>
        </div>
    </div>
@endsection

@section('content')
    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('productos.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="categoria" class="form-label">Categoría</label>
                    <select class="form-select" id="categoria" name="categoria">
                        <option value="">Todas las categorías</option>
                        @foreach ($categorias as $key => $categoria)
                            <option value="{{ $key }}" {{ request('categoria') == $key ? 'selected' : '' }}>
                                {{ $categoria }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="activo" class="form-label">Estado</label>
                    <select class="form-select" id="activo" name="activo">
                        <option value="">Todos</option>
                        <option value="1" {{ request('activo') == '1' ? 'selected' : '' }}>Activos</option>
                        <option value="0" {{ request('activo') == '0' ? 'selected' : '' }}>Inactivos</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="buscar" class="form-label">Buscar producto</label>
                    <input type="text" class="form-control" id="buscar" name="buscar" value="{{ request('buscar') }}"
                        placeholder="Nombre del producto...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="bi bi-search"></i> Filtrar
                        </button>
                        <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Limpiar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Productos -->
    <div class="card">
        <div class="card-body">
            @if ($productos->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th>Tipo Medición</th>
                                <th class="text-center">Unidades</th>
                                <th class="text-center">Vencimiento</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($productos as $producto)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $producto->nombre }}</div>
                                        @if ($producto->requiere_temperatura)
                                            <small class="text-info">
                                                <i class="bi bi-thermometer"></i> Requiere refrigeración
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $producto->categoria }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge 
                                @if ($producto->tipo_medicion == 'PESO') bg-success
                                @elseif($producto->tipo_medicion == 'CANTIDAD') bg-primary
                                @else bg-warning @endif">
                                            {{ $producto->tipo_medicion }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if ($producto->es_mixto)
                                            <div class="small">
                                                <strong>{{ $producto->unidad_primaria }}</strong> /
                                                <strong>{{ $producto->unidad_secundaria }}</strong>
                                            </div>
                                        @else
                                            <strong>{{ $producto->unidad_secundaria }}</strong>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($producto->dias_vencimiento)
                                            <span class="badge bg-warning">{{ $producto->dias_vencimiento }} días</span>
                                        @else
                                            <span class="text-muted">Sin vencimiento</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($producto->activo)
                                            <span class="badge bg-success">Activo</span>
                                        @else
                                            <span class="badge bg-secondary">Inactivo</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('productos.show', $producto) }}"
                                                class="btn btn-sm btn-outline-primary" title="Ver">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('productos.edit', $producto) }}"
                                                class="btn btn-sm btn-outline-warning" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('productos.destroy', $producto) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('¿Estás seguro de eliminar este producto?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $productos->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-box display-1 text-muted"></i>
                    <h4 class="text-muted mt-3">No hay productos registrados</h4>
                    @if (request()->hasAny(['categoria', 'activo', 'buscar']))
                        <p class="text-muted">No se encontraron productos con los filtros aplicados.</p>
                        <a href="{{ route('productos.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left"></i> Ver todos los productos
                        </a>
                    @else
                        <p class="text-muted">Comienza creando tu primer producto.</p>
                        <a href="{{ route('productos.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Crear Primer Producto
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Información adicional -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-box-seam display-4 text-primary"></i>
                    <h5 class="card-title mt-2">Total Productos</h5>
                    <h3 class="text-primary">{{ $productos->total() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle display-4 text-success"></i>
                    <h5 class="card-title mt-2">Productos Activos</h5>
                    <h3 class="text-success">{{ $productos->where('activo', true)->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-collection display-4 text-info"></i>
                    <h5 class="card-title mt-2">Categorías</h5>
                    <h3 class="text-info">{{ count($categorias) }}</h3>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // DataTable básico si hay resultados
            @if ($productos->count() > 0)
                $('table').DataTable({
                    searching: false,
                    info: false,
                    paging: false,
                    order: [
                        [0, 'asc']
                    ], // Ordenar por nombre
                    columnDefs: [{
                            targets: [6],
                            orderable: false
                        } // Columna acciones no ordenable
                    ]
                });
            @endif
        });
    </script>
@endpush
