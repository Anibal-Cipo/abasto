@extends('layouts.app')

@section('title', 'Editar Introducción')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('introducciones.index') }}">Introducciones</a></li>
    <li class="breadcrumb-item"><a href="/introducciones/{{ $introduccion->id }}">{{ $introduccion->numero_remito }}</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('header')
    <h1 class="h2"><i class="bi bi-pencil-square"></i> Editar Introducción: {{ $introduccion->numero_remito }}</h1>
@endsection

@section('content')
    <form action="{{ route('introducciones.update', $introduccion->id) }}" method="POST" enctype="multipart/form-data"
        id="introduccionForm">
        @csrf
        @method('PUT')

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
                                            {{ old('introductor_id', $introduccion->introductor_id) == $introductor->id ? 'selected' : '' }}>
                                            {{ $introductor->razon_social }} ({{ $introductor->cuit_formateado }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('introductor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="numero_remito" class="form-label">Número de Remito *</label>
                                <input type="text" class="form-control @error('numero_remito') is-invalid @enderror"
                                    id="numero_remito" name="numero_remito"
                                    value="{{ old('numero_remito', $introduccion->numero_remito) }}" required>
                                @error('numero_remito')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="envia" class="form-label">Envía</label>
                                <input type="text" class="form-control @error('envia') is-invalid @enderror"
                                    id="envia" name="envia" value="{{ old('envia', $introduccion->envia) }}">
                                @error('envia')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="procedencia" class="form-label">Procedencia</label>
                                <input type="text" class="form-control @error('procedencia') is-invalid @enderror"
                                    id="procedencia" name="procedencia"
                                    value="{{ old('procedencia', $introduccion->procedencia) }}">
                                @error('procedencia')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="vigente" class="form-label">Vigente</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" id="vigente" name="vigente"
                                        value="1" {{ old('vigente', $introduccion->vigente) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="vigente">
                                        <span
                                            id="vigente-text">{{ old('vigente', $introduccion->vigente) ? 'Sí' : 'No' }}</span>
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
                                    id="fecha" name="fecha"
                                    value="{{ old('fecha', $introduccion->fecha ? $introduccion->fecha->format('Y-m-d') : '') }}"
                                    required>
                                @error('fecha')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="hora" class="form-label">Hora *</label>
                                <input type="time" class="form-control @error('hora') is-invalid @enderror"
                                    id="hora" name="hora"
                                    value="{{ old('hora', $introduccion->hora ? substr($introduccion->hora, 0, 5) : '') }}"
                                    required>
                                @error('hora')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="vehiculo" class="form-label">Vehículo</label>
                                <input type="text" class="form-control @error('vehiculo') is-invalid @enderror"
                                    id="vehiculo" name="vehiculo" value="{{ old('vehiculo', $introduccion->vehiculo) }}">
                                @error('vehiculo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="dominio" class="form-label">Dominio</label>
                                <input type="text" class="form-control @error('dominio') is-invalid @enderror"
                                    id="dominio" name="dominio" value="{{ old('dominio', $introduccion->dominio) }}"
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
                                    value="{{ old('habilitacion_vehiculo', $introduccion->habilitacion_vehiculo) }}">
                                @error('habilitacion_vehiculo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="temperatura" class="form-label">Temperatura (°C)</label>
                                <input type="number" class="form-control @error('temperatura') is-invalid @enderror"
                                    id="temperatura" name="temperatura"
                                    value="{{ old('temperatura', $introduccion->temperatura) }}" step="0.1"
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
                                    id="precintos_origen" name="precintos_origen"
                                    value="{{ old('precintos_origen', $introduccion->precintos_origen) }}">
                                @error('precintos_origen')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="reprecintado" class="form-label">Reprecintado</label>
                                <input type="text" class="form-control @error('reprecintado') is-invalid @enderror"
                                    id="reprecintado" name="reprecintado"
                                    value="{{ old('reprecintado', $introduccion->reprecintado) }}">
                                @error('reprecintado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="ganaderia_numero" class="form-label">Ganadería N°</label>
                                <input type="text"
                                    class="form-control @error('ganaderia_numero') is-invalid @enderror"
                                    id="ganaderia_numero" name="ganaderia_numero"
                                    value="{{ old('ganaderia_numero', $introduccion->ganaderia_numero) }}">
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
                                    @php
                                        // Buscar si el receptor actual coincide con el nombre guardado
                                        $selected = false;
                                        if (
                                            $introduccion->receptores &&
                                            $receptor->razon_social === $introduccion->receptores
                                        ) {
                                            $selected = true;
                                        }
                                        // También verificar con old() por si hay errores de validación
                                        if (old('receptor_id') == $receptor->id) {
                                            $selected = true;
                                        }
                                    @endphp
                                    <option value="{{ $receptor->id }}" {{ $selected ? 'selected' : '' }}>
                                        {{ $receptor->razon_social }} ({{ $receptor->cuit_formateado }})
                                    </option>
                                @endforeach
                            </select>
                            @error('receptor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                @if ($introduccion->receptores)
                                    <small class="text-info">Actual: {{ $introduccion->receptores }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="pt_numero" class="form-label">P.T. N° (Permiso Tránsito)</label>
                                <input type="text" class="form-control @error('pt_numero') is-invalid @enderror"
                                    id="pt_numero" name="pt_numero"
                                    value="{{ old('pt_numero', $introduccion->pt_numero) }}">
                                @error('pt_numero')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="ptr_numero" class="form-label">P.T.R. N° (Permiso Tránsito
                                    Restringido)</label>
                                <input type="text" class="form-control @error('ptr_numero') is-invalid @enderror"
                                    id="ptr_numero" name="ptr_numero"
                                    value="{{ old('ptr_numero', $introduccion->ptr_numero) }}">
                                @error('ptr_numero')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="mb-3">
                            <label for="observaciones" class="form-label">Observaciones</label>
                            <textarea class="form-control @error('observaciones') is-invalid @enderror" id="observaciones" name="observaciones"
                                rows="3">{{ old('observaciones', $introduccion->observaciones) }}</textarea>
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
                                    <!-- Los productos existentes se cargarán dinámicamente -->
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
                <!-- Archivos existentes -->
                @if ($introduccion->archivos && $introduccion->archivos->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-paperclip"></i> Archivos Actuales
                            </h5>
                        </div>
                        <div class="card-body">
                            @foreach ($introduccion->archivos as $archivo)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <strong>{{ $archivo->tipo_archivo }}</strong><br>
                                        <small class="text-muted">{{ $archivo->nombre_original }}</small>
                                    </div>
                                    <a href="{{ $archivo->url }}" target="_blank"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-download"></i>
                                    </a>
                                </div>
                                @if (!$loop->last)
                                    <hr>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Subir nuevos archivos -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-cloud-upload"></i> Subir Nuevos Archivos
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="remito_imagen" class="form-label">Nueva Imagen del Remito</label>
                            <input type="file" class="form-control @error('remito_imagen') is-invalid @enderror"
                                id="remito_imagen" name="remito_imagen" accept="image/*,.pdf">
                            <div class="form-text">Formatos: JPG, PNG, PDF (máx. 5MB)</div>
                            @error('remito_imagen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="pt" class="form-label">Nuevo Certificado P.T.</label>
                            <input type="file" class="form-control @error('pt') is-invalid @enderror" id="pt"
                                name="pt" accept="image/*,.pdf">
                            <div class="form-text">Permiso de Tránsito</div>
                            @error('pt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="ptr" class="form-label">Nuevo Certificado P.T.R.</label>
                            <input type="file" class="form-control @error('ptr') is-invalid @enderror" id="ptr"
                                name="ptr" accept="image/*,.pdf">
                            <div class="form-text">Permiso de Tránsito Restringido</div>
                            @error('ptr')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="certificado_sanitario" class="form-label">Nuevo Certificado Sanitario</label>
                            <input type="file"
                                class="form-control @error('certificado_sanitario') is-invalid @enderror"
                                id="certificado_sanitario" name="certificado_sanitario" accept="image/*,.pdf">
                            <div class="form-text">Certificado Sanitario</div>
                            @error('certificado_sanitario')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle"></i> Actualizar Introducción
                            </button>
                            <a href="/introducciones/{{ $introduccion->id }}" class="btn btn-secondary">
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

        // Productos existentes cargados del servidor
        const productosExistentes = [];

        @foreach ($introduccion->productos as $producto)
            productosExistentes.push({
                producto_id: {{ $producto->producto_id ?? 0 }},
                cantidad_primaria: {{ $producto->cantidad_primaria ?? 0 }},
                cantidad_secundaria: {{ $producto->cantidad_secundaria ?? 0 }},
                observaciones: {!! json_encode($producto->observaciones ?? '') !!},
                producto_tipo: {!! json_encode($producto->producto ? $producto->producto->tipo_medicion : 'PESO') !!},
                unidad_primaria: {!! json_encode($producto->producto ? $producto->producto->unidad_primaria : '') !!},
                unidad_secundaria: {!! json_encode($producto->producto ? $producto->producto->unidad_secundaria : 'kg') !!}
            });
        @endforeach

        document.addEventListener('DOMContentLoaded', function() {
            // DEBUG: Verificar productos existentes
            console.log('Productos existentes:', productosExistentes);
            console.log('Cantidad de productos:', productosExistentes.length);

            // Cargar productos existentes
            productosExistentes.forEach(function(producto, index) {
                console.log('Cargando producto', index, producto);
                agregarProductoExistente(producto);
            });

            // Si no hay productos existentes, agregar uno vacío
            if (productosExistentes.length === 0) {
                console.log('No hay productos existentes, agregando uno vacío');
                agregarProducto();
            }
        });

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

        function agregarProductoExistente(producto) {
            const template = document.getElementById('productoRowTemplate');
            const tbody = document.getElementById('productosBody');

            // Clonar template
            const clone = template.content.cloneNode(true);

            // Reemplazar INDEX con el índice actual
            const html = clone.querySelector('tr').outerHTML.replace(/INDEX/g, productoIndex);

            // Agregar al tbody
            tbody.insertAdjacentHTML('beforeend', html);

            // Configurar la fila con los datos existentes
            const nuevaFila = tbody.lastElementChild;
            const select = nuevaFila.querySelector('.producto-select');
            const cantidadPrimaria = nuevaFila.querySelector('.cantidad-primaria');
            const cantidadSecundaria = nuevaFila.querySelector('.cantidad-secundaria');
            const observaciones = nuevaFila.querySelector('input[name*="observaciones"]');

            // Establecer valores
            select.value = producto.producto_id;
            cantidadPrimaria.value = producto.cantidad_primaria || '';
            cantidadSecundaria.value = producto.cantidad_secundaria;
            observaciones.value = producto.observaciones || '';

            // Configurar eventos y unidades
            select.addEventListener('change', function() {
                actualizarUnidades(this);
            });

            // Actualizar unidades inmediatamente
            actualizarUnidades(select);

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

            if (!option || !option.value) return;

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

        // Validación del formulario
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
