@extends('layouts.app')

@section('title', 'Crear Producto')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('productos.index') }}">Productos</a></li>
    <li class="breadcrumb-item active">Crear</li>
@endsection

@section('header')
    <div>
        <h1 class="h2"><i class="bi bi-plus-circle"></i> Crear Nuevo Producto</h1>
        <p class="text-muted mb-0">Registra un nuevo producto en el sistema</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver a Productos
        </a>
    </div>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('productos.store') }}" method="POST">
                        @csrf

                        <!-- Información Básica -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">
                                    <i class="bi bi-info-circle"></i> Información Básica
                                </h5>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">Nombre del Producto *</label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                    id="nombre" name="nombre" value="{{ old('nombre') }}" required maxlength="100"
                                    placeholder="Ej: Carne de res">
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="categoria" class="form-label">Categoría *</label>
                                <select class="form-select @error('categoria') is-invalid @enderror" id="categoria"
                                    name="categoria" required>
                                    <option value="">Seleccionar categoría</option>
                                    @foreach ($categorias as $key => $categoria)
                                        <option value="{{ $key }}"
                                            {{ old('categoria') == $key ? 'selected' : '' }}>
                                            {{ $categoria }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('categoria')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Medición -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">
                                    <i class="bi bi-rulers"></i> Sistema de Medición
                                </h5>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="tipo_medicion" class="form-label">Tipo de Medición *</label>
                                <select class="form-select @error('tipo_medicion') is-invalid @enderror" id="tipo_medicion"
                                    name="tipo_medicion" required onchange="toggleUnidadPrimaria()">
                                    <option value="">Seleccionar tipo</option>
                                    <option value="PESO" {{ old('tipo_medicion') == 'PESO' ? 'selected' : '' }}>
                                        PESO (solo kilogramos)
                                    </option>
                                    <option value="CANTIDAD" {{ old('tipo_medicion') == 'CANTIDAD' ? 'selected' : '' }}>
                                        CANTIDAD (solo unidades)
                                    </option>
                                    <option value="MIXTO" {{ old('tipo_medicion') == 'MIXTO' ? 'selected' : '' }}>
                                        MIXTO (unidades y peso)
                                    </option>
                                </select>
                                @error('tipo_medicion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3" id="unidad-primaria-group" style="display: none;">
                                <label for="unidad_primaria" class="form-label">Unidad Primaria</label>
                                <input type="text" class="form-control @error('unidad_primaria') is-invalid @enderror"
                                    id="unidad_primaria" name="unidad_primaria" value="{{ old('unidad_primaria') }}"
                                    maxlength="20" placeholder="Ej: unidades, cajas">
                                @error('unidad_primaria')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Solo para productos MIXTO</div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="unidad_secundaria" class="form-label">Unidad Secundaria *</label>
                                <input type="text" class="form-control @error('unidad_secundaria') is-invalid @enderror"
                                    id="unidad_secundaria" name="unidad_secundaria" value="{{ old('unidad_secundaria') }}"
                                    required maxlength="20" placeholder="Ej: kg, litros, unidades">
                                @error('unidad_secundaria')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Unidad principal de medición</div>
                            </div>
                        </div>

                        <!-- Propiedades -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">
                                    <i class="bi bi-gear"></i> Propiedades del Producto
                                </h5>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="dias_vencimiento" class="form-label">Días de Vencimiento *</label>
                                <input type="number" class="form-control @error('dias_vencimiento') is-invalid @enderror"
                                    id="dias_vencimiento" name="dias_vencimiento" value="{{ old('dias_vencimiento', 30) }}"
                                    required min="1" max="3650">
                                @error('dias_vencimiento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Días antes del vencimiento del producto</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input @error('requiere_temperatura') is-invalid @enderror"
                                        type="checkbox" id="requiere_temperatura" name="requiere_temperatura"
                                        value="1" {{ old('requiere_temperatura') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="requiere_temperatura">
                                        <i class="bi bi-thermometer"></i> Requiere Control de Temperatura
                                    </label>
                                    @error('requiere_temperatura')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Marcar si el producto necesita refrigeración</div>
                                </div>
                            </div>
                        </div>

                        <!-- Estado -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">
                                    <i class="bi bi-toggle-on"></i> Estado del Producto
                                </h5>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input @error('activo') is-invalid @enderror" type="checkbox"
                                        id="activo" name="activo" value="1"
                                        {{ old('activo', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="activo">
                                        <span id="activo-text">{{ old('activo', true) ? 'Activo' : 'Inactivo' }}</span>
                                    </label>
                                    @error('activo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Solo productos activos aparecen en las listas</div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="row">
                            <div class="col-12">
                                <hr>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle"></i> Crear Producto
                                    </button>
                                    <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-circle"></i> Cancelar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Ayuda -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-lightbulb"></i> Información sobre Tipos de Medición
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h6 class="text-success">PESO</h6>
                            <p class="small text-muted">
                                Para productos que se miden solo por peso (kg, gramos, etc.).
                                <br><strong>Ejemplo:</strong> Carne molida, verduras.
                            </p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-primary">CANTIDAD</h6>
                            <p class="small text-muted">
                                Para productos que se cuentan por unidades.
                                <br><strong>Ejemplo:</strong> Huevos, botellas, latas.
                            </p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="text-warning">MIXTO</h6>
                            <p class="small text-muted">
                                Para productos con doble medición: unidades y peso.
                                <br><strong>Ejemplo:</strong> 12 unidades / 1.5 kg.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function toggleUnidadPrimaria() {
            const tipoMedicion = document.getElementById('tipo_medicion').value;
            const unidadPrimariaGroup = document.getElementById('unidad-primaria-group');
            const unidadPrimariaInput = document.getElementById('unidad_primaria');

            if (tipoMedicion === 'MIXTO') {
                unidadPrimariaGroup.style.display = 'block';
                unidadPrimariaInput.required = true;
            } else {
                unidadPrimariaGroup.style.display = 'none';
                unidadPrimariaInput.required = false;
                unidadPrimariaInput.value = '';
            }
        }

        // Actualizar texto del switch activo
        document.getElementById('activo').addEventListener('change', function() {
            const text = document.getElementById('activo-text');
            text.textContent = this.checked ? 'Activo' : 'Inactivo';
        });

        // Inicializar al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            toggleUnidadPrimaria();
        });
    </script>
@endpush
