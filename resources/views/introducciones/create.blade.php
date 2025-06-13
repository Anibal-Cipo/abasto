@extends('layouts.app')

@section('title', 'Nueva Introducción')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('introducciones.index') }}">Introducciones</a></li>
    <li class="breadcrumb-item active">Nueva</li>
@endsection

@section('header')
    <h1 class="h2"><i class="bi bi-plus-circle"></i> Nueva Introducción</h1>
@endsection

@section('content')
    <form action="{{ route('introducciones.store') }}" method="POST" enctype="multipart/form-data" id="introduccionForm">
        @csrf

        <div class="row">
            <!-- Datos principales -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-info-circle"></i> Datos del Remito
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="introductor_id" class="form-label">Introductor *</label>
                                <select class="form-select @error('introductor_id') is-invalid @enderror"
                                    id="introductor_id" name="introductor_id" required>
                                    <option value="">Seleccionar introductor...</option>
                                    @foreach ($introductores as $introductor)
                                        <option value="{{ $introductor->id }}"
                                            {{ old('introductor_id', request('introductor_id')) == $introductor->id ? 'selected' : '' }}>
                                            {{ $introductor->razon_social }} ({{ $introductor->cuit_formateado }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('introductor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <label for="numero_remito" class="form-label me-3">Número de Remito *</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="remito_papel"
                                            name="remito_papel" value="1" {{ old('remito_papel') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remito_papel">
                                            <small>Remito en Papel</small>
                                        </label>
                                    </div>
                                </div>

                                <div id="numero_remito_container">
                                    <input type="text" class="form-control @error('numero_remito') is-invalid @enderror"
                                        id="numero_remito" name="numero_remito"
                                        value="{{ old('numero_remito', $numeroRemitoSugerido ?? '') }}" required>
                                    @error('numero_remito')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div id="numero_remito_papel_container" style="display: none;">
                                    <input type="text"
                                        class="form-control @error('numero_remito_papel') is-invalid @enderror"
                                        id="numero_remito_papel" name="numero_remito_papel"
                                        value="{{ old('numero_remito_papel') }}" placeholder="Número del remito en papel">
                                    @error('numero_remito_papel')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="envia" class="form-label">Envía</label>
                                <input type="text" class="form-control @error('envia') is-invalid @enderror"
                                    id="envia" name="envia" value="{{ old('envia') }}">
                                @error('envia')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="procedencia" class="form-label">Procedencia</label>
                                <input type="text" class="form-control @error('procedencia') is-invalid @enderror"
                                    id="procedencia" name="procedencia" value="{{ old('procedencia') }}">
                                @error('procedencia')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="vigente" class="form-label">Vigente</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" id="vigente" name="vigente"
                                        value="1" {{ old('vigente', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="vigente">
                                        <span id="vigente-text">{{ old('vigente', true) ? 'Sí' : 'No' }}</span>
                                    </label>
                                </div>
                                @error('vigente')
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
                                <label for="vehiculo" class="form-label">Vehículo</label>
                                <input type="text" class="form-control @error('vehiculo') is-invalid @enderror"
                                    id="vehiculo" name="vehiculo" value="{{ old('vehiculo') }}">
                                @error('vehiculo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="dominio" class="form-label">Dominio</label>
                                <input type="text" class="form-control @error('dominio') is-invalid @enderror"
                                    id="dominio" name="dominio" value="{{ old('dominio') }}"
                                    style="text-transform: uppercase;">
                                @error('dominio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="habilitacion_vehiculo" class="form-label">Habilitación Vehículo</label>
                                <input type="text"
                                    class="form-control @error('habilitacion_vehiculo') is-invalid @enderror"
                                    id="habilitacion_vehiculo" name="habilitacion_vehiculo"
                                    value="{{ old('habilitacion_vehiculo') }}">
                                @error('habilitacion_vehiculo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="temperatura" class="form-label">Temperatura (°C)</label>
                                <input type="number" class="form-control @error('temperatura') is-invalid @enderror"
                                    id="temperatura" name="temperatura" value="{{ old('temperatura') }}" step="0.1"
                                    min="-50" max="50">
                                @error('temperatura')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- NUEVOS CAMPOS -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="precintos_origen" class="form-label">Precintos de Origen</label>
                                <input type="text"
                                    class="form-control @error('precintos_origen') is-invalid @enderror"
                                    id="precintos_origen" name="precintos_origen" value="{{ old('precintos_origen') }}">
                                @error('precintos_origen')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="reprecintado" class="form-label">Reprecintado</label>
                                <input type="text" class="form-control @error('reprecintado') is-invalid @enderror"
                                    id="reprecintado" name="reprecintado" value="{{ old('reprecintado') }}">
                                @error('reprecintado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="ganaderia_numero" class="form-label">Ganadería N°</label>
                                <input type="text"
                                    class="form-control @error('ganaderia_numero') is-invalid @enderror"
                                    id="ganaderia_numero" name="ganaderia_numero" value="{{ old('ganaderia_numero') }}">
                                @error('ganaderia_numero')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- RECEPTOR -->
                        <div class="mb-3">
                            <label for="receptor_id" class="form-label">Receptor</label>
                            <select class="form-select @error('receptor_id') is-invalid @enderror" id="receptor_id"
                                name="receptor_id">
                                <option value="">Seleccionar receptor...</option>
                                @foreach ($receptores as $receptor)
                                    <option value="{{ $receptor->id }}"
                                        {{ old('receptor_id') == $receptor->id ? 'selected' : '' }}>
                                        {{ $receptor->razon_social }} ({{ $receptor->cuit_formateado }})
                                    </option>
                                @endforeach
                            </select>
                            @error('receptor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="pt_numero" class="form-label">P.T. N° (Permiso Tránsito)</label>
                                <input type="text" class="form-control @error('pt_numero') is-invalid @enderror"
                                    id="pt_numero" name="pt_numero" value="{{ old('pt_numero') }}">
                                @error('pt_numero')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="ptr_numero" class="form-label">P.T.R. N° (Permiso Tránsito
                                    Restringido)</label>
                                <input type="text" class="form-control @error('ptr_numero') is-invalid @enderror"
                                    id="ptr_numero" name="ptr_numero" value="{{ old('ptr_numero') }}">
                                @error('ptr_numero')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                           
                        </div>

                        <div class="mb-3">
                            <label for="observaciones" class="form-label">Observaciones</label>
                            <textarea class="form-control @error('observaciones') is-invalid @enderror" id="observaciones" name="observaciones"
                                rows="3">{{ old('observaciones') }}</textarea>
                            @error('observaciones')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Productos -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-box"></i> Productos
                        </h5>
                        <button type="button" class="btn btn-sm btn-primary" onclick="agregarProducto()">
                            <i class="bi bi-plus"></i> Agregar Producto
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0" id="productosTable">
                                <thead class="table-light">
                                    <tr>
                                        <th width="35%">Producto</th>
                                        <th width="15%">Cantidad 1</th>
                                        <th width="15%">Cantidad 2</th>
                                        <th width="25%">Observaciones</th>
                                        <th width="10%" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="productosBody">
                                    <!-- Los productos se agregan dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                        <div class="p-3 text-muted">
                            <small>
                                <i class="bi bi-info-circle"></i>
                                Para productos MIXTOS (ej: carnes), usar ambas cantidades. Para productos de PESO o
                                CANTIDAD, usar solo Cantidad 2.
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Archivos y acciones -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-paperclip"></i> Archivos
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="remito_imagen" class="form-label">Imagen del Remito</label>
                            <input type="file" class="form-control @error('remito_imagen') is-invalid @enderror"
                                id="remito_imagen" name="remito_imagen" accept="image/*,.pdf">
                            <div class="form-text">Formatos: JPG, PNG, PDF (máx. 5MB)</div>
                            @error('remito_imagen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="pt" class="form-label">Certificado P.T.</label>
                            <input type="file" class="form-control @error('pt') is-invalid @enderror" id="pt"
                                name="pt" accept="image/*,.pdf">
                            <div class="form-text">Permiso de Tránsito</div>
                            @error('pt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="ptr" class="form-label">Certificado P.T.R.</label>
                            <input type="file" class="form-control @error('ptr') is-invalid @enderror" id="ptr"
                                name="ptr" accept="image/*,.pdf">
                            <div class="form-text">Permiso de Tránsito Restringido</div>
                            @error('ptr')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                         <div class="mb-3">
                                <label for="certificado_sanitario" class="form-label">Certificado Sanitario</label>
                                <input type="file"
                                    class="form-control @error('certificado_sanitario') is-invalid @enderror"
                                    id="certificado_sanitario" name="certificado_sanitario" accept="image/*,.pdf">
                                <div class="form-text">Certificado Sanitario (máx. 5MB)</div>
                                @error('certificado_sanitario')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle"></i> Registrar Introducción
                            </button>
                            <a href="{{ route('introducciones.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancelar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Template para fila de producto -->
    <template id="productoRowTemplate">
        <tr>
            <td>
                <select class="form-select producto-select" name="productos[INDEX][producto_id]" required>
                    <option value="">Seleccionar producto...</option>
                    @foreach ($productos->groupBy('categoria') as $categoria => $productosCategoria)
                        <optgroup label="{{ $categoria }}">
                            @foreach ($productosCategoria as $producto)
                                <option value="{{ $producto->id }}" data-tipo="{{ $producto->tipo_medicion }}"
                                    data-unidad-primaria="{{ $producto->unidad_primaria }}"
                                    data-unidad-secundaria="{{ $producto->unidad_secundaria }}">
                                    {{ $producto->nombre }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" class="form-control cantidad-primaria" name="productos[INDEX][cantidad_primaria]"
                    step="0.01" min="0" placeholder="0">
                <small class="form-text unidad-primaria-text"></small>
            </td>
            <td>
                <input type="number" class="form-control cantidad-secundaria"
                    name="productos[INDEX][cantidad_secundaria]" step="0.01" min="0.01" required placeholder="0">
                <small class="form-text unidad-secundaria-text"></small>
            </td>
            <td>
                <input type="text" class="form-control" name="productos[INDEX][observaciones]"
                    placeholder="Observaciones...">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-danger" onclick="eliminarProducto(this)">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>
    </template>
@endsection

@push('scripts')
    <script>
        let productoIndex = 0;

        // Lógica para remito en papel
        document.addEventListener('DOMContentLoaded', function() {
            const remitoPapel = document.getElementById('remito_papel');
            const numeroRemito = document.getElementById('numero_remito');
            const numeroRemitoContainer = document.getElementById('numero_remito_container');
            const numeroRemitoPapelContainer = document.getElementById('numero_remito_papel_container');

            function toggleRemitoPapel() {
                if (remitoPapel.checked) {
                    numeroRemito.readOnly = true;
                    numeroRemito.classList.add('bg-light');
                    numeroRemitoPapelContainer.style.display = 'block';
                    document.getElementById('numero_remito_papel').required = true;
                } else {
                    numeroRemito.readOnly = false;
                    numeroRemito.classList.remove('bg-light');
                    numeroRemitoPapelContainer.style.display = 'none';
                    document.getElementById('numero_remito_papel').required = false;
                }
            }

            remitoPapel.addEventListener('change', toggleRemitoPapel);
            toggleRemitoPapel(); // Ejecutar al cargar

            // Inicializar búsqueda de receptores
            //initReceptorSearch();

            // Agregar primer producto al cargar
            agregarProducto();
        });

        // === FUNCIONES DE PRODUCTOS ===
        function agregarProducto() {
            const template = document.getElementById('productoRowTemplate');
            const tbody = document.getElementById('productosBody');

            // Clonar template
            const clone = template.content.cloneNode(true);

            // Reemplazar INDEX con el índice actual
            const html = clone.querySelector('tr').outerHTML.replace(/INDEX/g, productoIndex);

            // Agregar al tbody
            tbody.insertAdjacentHTML('beforeend', html);

            // Configurar eventos para la nueva fila
            const nuevaFila = tbody.lastElementChild;
            const select = nuevaFila.querySelector('.producto-select');

            select.addEventListener('change', function() {
                actualizarUnidades(this);
            });

            productoIndex++;
        }

        function eliminarProducto(button) {
            if (document.querySelectorAll('#productosBody tr').length > 1) {
                button.closest('tr').remove();
            } else {
                alert('Debe mantener al menos un producto');
            }
        }

        function actualizarUnidades(select) {
            const fila = select.closest('tr');
            const option = select.selectedOptions[0];
            const tipo = option.dataset.tipo;
            const unidadPrimaria = option.dataset.unidadPrimaria;
            const unidadSecundaria = option.dataset.unidadSecundaria;

            const cantidadPrimaria = fila.querySelector('.cantidad-primaria');
            const cantidadSecundaria = fila.querySelector('.cantidad-secundaria');
            const textoPrimaria = fila.querySelector('.unidad-primaria-text');
            const textoSecundaria = fila.querySelector('.unidad-secundaria-text');

            if (tipo === 'MIXTO') {
                cantidadPrimaria.disabled = false;
                cantidadPrimaria.required = true;
                textoPrimaria.textContent = unidadPrimaria || '';
                textoSecundaria.textContent = unidadSecundaria || '';
            } else {
                cantidadPrimaria.disabled = true;
                cantidadPrimaria.required = false;
                cantidadPrimaria.value = '';
                textoPrimaria.textContent = '';
                textoSecundaria.textContent = unidadSecundaria || '';
            }
        }

        // === VALIDACIÓN DEL FORMULARIO ===
        document.getElementById('introduccionForm').addEventListener('submit', function(e) {
            const productos = document.querySelectorAll('#productosBody tr');
            if (productos.length === 0) {
                e.preventDefault();
                alert('Debe agregar al menos un producto');
                return false;
            }

            // Validar que todos los productos tengan selección
            let productosValidos = true;
            productos.forEach(function(fila) {
                const select = fila.querySelector('.producto-select');
                if (!select.value) {
                    productosValidos = false;
                }
            });

            if (!productosValidos) {
                e.preventDefault();
                alert('Todos los productos deben tener una selección válida');
                return false;
            }
        });

        // Formatear dominio en mayúsculas
        document.getElementById('dominio').addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
        // Switch para vigente
        document.getElementById('vigente').addEventListener('change', function() {
            const texto = document.getElementById('vigente-text');
            if (this.checked) {
                texto.textContent = 'Sí';
            } else {
                texto.textContent = 'No';
            }
        });
    </script>
@endpush
