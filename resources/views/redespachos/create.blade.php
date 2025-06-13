@extends('layouts.app')

@section('title', 'Nuevo Redespacho')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('introducciones.index') }}">Introducciones</a></li>
<li class="breadcrumb-item"><a href="{{ route('introducciones.show', $introduccion) }}">{{ $introduccion->numero_remito }}</a></li>
<li class="breadcrumb-item active">Nuevo Redespacho</li>
@endsection

@section('header')
<h1 class="h2"><i class="bi bi-arrow-repeat"></i> Nuevo Redespacho</h1>
<p class="text-muted">Basado en: <strong>{{ $introduccion->numero_remito }}</strong> - {{ $introduccion->introductor->razon_social }}</p>
@endsection

@section('content')
<form action="{{ route('redespachos.store', $introduccion) }}" method="POST" id="redespachoForm">
    @csrf
    
    <div class="row">
        <!-- Datos principales -->
        <div class="col-md-8">
            <!-- Información de la introducción base -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-info-circle"></i> Introducción Base
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Remito:</strong> {{ $introduccion->numero_remito }}<br>
                            <strong>Introductor:</strong> {{ $introduccion->introductor->razon_social }}<br>
                            <strong>Fecha:</strong> {{ $introduccion->fecha->format('d/m/Y') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Total Productos:</strong> {{ $stockDisponible->count() }}<br>
                            <strong>Con Stock:</strong> {{ $stockDisponible->where('stock_disponible', '>', 0)->count() }}<br>
                            @if($introduccion->temperatura)
                                <strong>Temperatura:</strong> {{ $introduccion->temperatura }}°C
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Datos del redespacho -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-truck"></i> Datos del Redespacho
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="numero_redespacho" class="form-label">Número de Redespacho *</label>
                            <input type="text" class="form-control @error('numero_redespacho') is-invalid @enderror" 
                                   id="numero_redespacho" name="numero_redespacho" 
                                   value="{{ old('numero_redespacho', \App\Models\Redespacho::generarNumeroRedespacho()) }}" required>
                            @error('numero_redespacho')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="destino" class="form-label">Destino *</label>
                            <input type="text" class="form-control @error('destino') is-invalid @enderror" 
                                   id="destino" name="destino" value="{{ old('destino') }}" required>
                            @error('destino')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="fecha" class="form-label">Fecha *</label>
                            <input type="date" class="form-control @error('fecha') is-invalid @enderror" 
                                   id="fecha" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required>
                            @error('fecha')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="hora" class="form-label">Hora *</label>
                            <input type="time" class="form-control @error('hora') is-invalid @enderror" 
                                   id="hora" name="hora" value="{{ old('hora', date('H:i')) }}" required>
                            @error('hora')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="dominio" class="form-label">Dominio del Vehículo</label>
                            <input type="text" class="form-control @error('dominio') is-invalid @enderror" 
                                   id="dominio" name="dominio" value="{{ old('dominio') }}"
                                   style="text-transform: uppercase;">
                            @error('dominio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="habilitacion_destino" class="form-label">Habilitación Destino</label>
                            <input type="text" class="form-control @error('habilitacion_destino') is-invalid @enderror" 
                                   id="habilitacion_destino" name="habilitacion_destino" 
                                   value="{{ old('habilitacion_destino') }}">
                            @error('habilitacion_destino')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="certificado_sanitario" 
                                       name="certificado_sanitario" value="1" {{ old('certificado_sanitario') ? 'checked' : '' }}>
                                <label class="form-check-label" for="certificado_sanitario">
                                    Cuenta con Certificado Sanitario
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control @error('observaciones') is-invalid @enderror" 
                                  id="observaciones" name="observaciones" rows="3">{{ old('observaciones') }}</textarea>
                        @error('observaciones')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Productos a redespachar -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-box"></i> Productos a Redespachar
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">
                                        <input type="checkbox" id="selectAll" title="Seleccionar todos">
                                    </th>
                                    <th width="25%">Producto</th>
                                    <th width="15%">Introducido</th>
                                    <th width="15%">Disponible</th>
                                    <th width="25%">Cantidad a Redespachar</th>
                                    <th width="15%">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stockDisponible as $index => $item)
                                <tr class="{{ $item->stock_disponible <= 0 ? 'table-secondary' : '' }}">
                                    <td class="text-center">
                                        @if($item->stock_disponible > 0)
                                            <input type="checkbox" class="product-checkbox" 
                                                   name="productos[{{ $index }}][activo]" value="1"
                                                   data-index="{{ $index }}">
                                        @else
                                            <i class="bi bi-dash-circle text-muted" title="Sin stock disponible"></i>
                                        @endif
                                        <input type="hidden" name="productos[{{ $index }}][producto_id]" 
                                               value="{{ $item->producto_id }}">
                                    </td>
                                    <td>
                                        <strong>{{ $item->producto->nombre }}</strong><br>
                                        <small class="text-muted">{{ $item->producto->categoria }}</small>
                                        @if($item->stock_disponible <= 0)
                                            <br><span class="badge bg-warning">Sin stock</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($item->producto->tipo_medicion === 'MIXTO' && $item->cantidad_primaria)
                                            <span class="badge bg-info mb-1 d-block">
                                                {{ number_format($item->cantidad_primaria, 0) }} 
                                                {{ $item->producto->unidad_primaria }}
                                            </span>
                                        @endif
                                        <span class="badge bg-primary">
                                            {{ number_format($item->cantidad_secundaria, 2) }} 
                                            {{ $item->producto->unidad_secundaria }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($item->stock_disponible > 0)
                                            @if($item->producto->tipo_medicion === 'MIXTO' && $item->cantidad_primaria)
                                                <span class="badge bg-success mb-1 d-block">
                                                    {{ number_format($item->cantidad_primaria, 0) }} 
                                                    {{ $item->producto->unidad_primaria }}
                                                </span>
                                            @endif
                                            <span class="badge bg-success">
                                                {{ number_format($item->stock_disponible, 2) }} 
                                                {{ $item->producto->unidad_secundaria }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">Agotado</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->stock_disponible > 0)
                                            @if($item->producto->tipo_medicion === 'MIXTO' && $item->cantidad_primaria)
                                                <!-- Producto MIXTO: Dos campos relacionados -->
                                                <div class="row g-1">
                                                    <div class="col-6">
                                                        <label class="form-label form-label-sm">{{ $item->producto->unidad_primaria }}</label>
                                                        <input type="number" class="form-control form-control-sm cantidad-primaria" 
                                                               name="productos[{{ $index }}][cantidad_primaria]" 
                                                               step="1" min="0" max="{{ $item->cantidad_primaria }}"
                                                               placeholder="Ej: 3" disabled 
                                                               data-max="{{ $item->cantidad_primaria }}">
                                                        <small class="form-text">Máx: {{ number_format($item->cantidad_primaria, 0) }}</small>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label form-label-sm">{{ $item->producto->unidad_secundaria }}</label>
                                                        <input type="number" class="form-control form-control-sm cantidad-secundaria" 
                                                               name="productos[{{ $index }}][cantidad_secundaria]" 
                                                               step="0.01" min="0.01" max="{{ $item->stock_disponible }}"
                                                               placeholder="Ej: 325" disabled 
                                                               data-max="{{ $item->stock_disponible }}" required>
                                                        <small class="form-text">Máx: {{ number_format($item->stock_disponible, 2) }}</small>
                                                    </div>
                                                </div>
                                            @else
                                                <!-- Producto simple: Solo un campo -->
                                                <input type="number" class="form-control cantidad-secundaria" 
                                                       name="productos[{{ $index }}][cantidad_secundaria]" 
                                                       step="0.01" min="0.01" max="{{ $item->stock_disponible }}"
                                                       placeholder="Cantidad" disabled 
                                                       data-max="{{ $item->stock_disponible }}" required>
                                                <small class="form-text">{{ $item->producto->unidad_secundaria }} (Máx: {{ number_format($item->stock_disponible, 2) }})</small>
                                            @endif
                                        @else
                                            <input type="number" class="form-control" disabled placeholder="Sin stock">
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->stock_disponible > 0)
                                            <input type="text" class="form-control observaciones-redespacho form-control-sm" 
                                                   name="productos[{{ $index }}][observaciones]" 
                                                   placeholder="Observaciones..." disabled>
                                        @else
                                            <input type="text" class="form-control" disabled placeholder="-">
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                
                                @if($stockDisponible->where('stock_disponible', '>', 0)->count() == 0)
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-exclamation-triangle display-4"></i>
                                            <h5 class="mt-2">No hay productos disponibles para redespachar</h5>
                                            <p>Todos los productos de esta introducción ya han sido redespachados.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <i class="bi bi-info-circle"></i> 
                                    Selecciona los productos que deseas redespachar y especifica las cantidades.
                                </small>
                            </div>
                            <div class="col-md-6 text-end">
                                <small class="text-muted">
                                    <strong>Productos disponibles:</strong> 
                                    <span class="badge bg-success">{{ $stockDisponible->where('stock_disponible', '>', 0)->count() }}</span>
                                    de {{ $stockDisponible->count() }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-arrow-repeat"></i> Registrar Redespacho
                        </button>
                        <a href="{{ route('introducciones.show', $introduccion) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.product-checkbox');
    const cantidadesPrimarias = document.querySelectorAll('.cantidad-primaria');
    const cantidadesSecundarias = document.querySelectorAll('.cantidad-secundaria');
    const observaciones = document.querySelectorAll('.observaciones-redespacho');

    // Ocultar "Seleccionar todos" si no hay productos disponibles
    if (checkboxes.length === 0) {
        selectAll.style.display = 'none';
    }

    // Seleccionar todos (solo productos disponibles)
    selectAll.addEventListener('change', function() {
        checkboxes.forEach((checkbox) => {
            checkbox.checked = this.checked;
            toggleProductInputs(checkbox);
        });
    });

    // Manejar checkboxes individuales
    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener('change', function() {
            toggleProductInputs(this);
            updateSelectAll();
        });
    });

    // Función para habilitar/deshabilitar inputs
    function toggleProductInputs(checkbox) {
        const row = checkbox.closest('tr');
        const cantidadPrimaria = row.querySelector('.cantidad-primaria');
        const cantidadSecundaria = row.querySelector('.cantidad-secundaria');
        const observacionInput = row.querySelector('.observaciones-redespacho');
        
        // Habilitar/deshabilitar cantidad primaria (si existe)
        if (cantidadPrimaria) {
            cantidadPrimaria.disabled = !checkbox.checked;
            if (!checkbox.checked) {
                cantidadPrimaria.value = '';
            }
        }
        
        // Habilitar/deshabilitar cantidad secundaria (siempre presente)
        if (cantidadSecundaria) {
            cantidadSecundaria.disabled = !checkbox.checked;
            if (!checkbox.checked) {
                cantidadSecundaria.value = '';
            }
        }
        
        // Habilitar/deshabilitar observaciones
        if (observacionInput) {
            observacionInput.disabled = !checkbox.checked;
            if (!checkbox.checked) {
                observacionInput.value = '';
            }
        }
    }

    // Actualizar estado del "Seleccionar todos"
    function updateSelectAll() {
        const checkedCount = document.querySelectorAll('.product-checkbox:checked').length;
        const totalCount = checkboxes.length;
        
        if (totalCount === 0) return;
        
        selectAll.checked = checkedCount > 0 && checkedCount === totalCount;
        selectAll.indeterminate = checkedCount > 0 && checkedCount < totalCount;
    }

    // Formatear dominio
    const dominioInput = document.getElementById('dominio');
    if (dominioInput) {
        dominioInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    }

    // Validación del formulario
    document.getElementById('redespachoForm').addEventListener('submit', function(e) {
        const productosSeleccionados = document.querySelectorAll('.product-checkbox:checked');
        
        if (productosSeleccionados.length === 0) {
            e.preventDefault();
            alert('Debe seleccionar al menos un producto para redespachar');
            return false;
        }

        // Validar cantidades
        let cantidadesValidas = true;
        let mensajesError = [];
        
        productosSeleccionados.forEach(checkbox => {
            const row = checkbox.closest('tr');
            const cantidadSecundaria = row.querySelector('.cantidad-secundaria');
            const cantidadPrimaria = row.querySelector('.cantidad-primaria');
            const productoNombre = row.querySelector('strong').textContent;
            
            // Validar cantidad secundaria (obligatoria)
            if (!cantidadSecundaria.value || parseFloat(cantidadSecundaria.value) <= 0) {
                cantidadesValidas = false;
                mensajesError.push(`${productoNombre}: debe especificar una cantidad válida`);
            } else {
                const valorSec = parseFloat(cantidadSecundaria.value);
                const maxSec = parseFloat(cantidadSecundaria.getAttribute('data-max'));
                if (valorSec > maxSec) {
                    cantidadesValidas = false;
                    mensajesError.push(`${productoNombre}: cantidad excede el stock disponible (${maxSec})`);
                }
            }

            // Para productos mixtos, validar coherencia entre cantidades
            if (cantidadPrimaria && cantidadPrimaria.value) {
                const valorPrim = parseFloat(cantidadPrimaria.value);
                const maxPrim = parseFloat(cantidadPrimaria.getAttribute('data-max'));
                
                // Validar límites de cantidad primaria
                if (valorPrim > maxPrim) {
                    cantidadesValidas = false;
                    mensajesError.push(`${productoNombre}: cantidad de unidades excede el stock disponible (${maxPrim})`);
                }
                
                // Validar que tenga cantidad secundaria si especifica primaria
                if (valorPrim > 0 && (!cantidadSecundaria.value || parseFloat(cantidadSecundaria.value) <= 0)) {
                    cantidadesValidas = false;
                    mensajesError.push(`${productoNombre}: debe especificar el peso total si indica cantidad de unidades`);
                }
            }
        });

        if (!cantidadesValidas) {
            e.preventDefault();
            alert('Errores en las cantidades:\n\n' + mensajesError.join('\n'));
            return false;
        }
    });
});
</script>
@endpush