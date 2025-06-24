@extends('layouts.app')

@section('title', 'Nueva Acta de Contravención')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-file-alt me-2"></i>
                            Nueva Acta de Contravención
                        </h4>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('infracciones.store') }}" method="POST" enctype="multipart/form-data"
                            id="formActa">
                            @csrf

                            {{-- Datos de la Persona --}}
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="fas fa-user me-2"></i>
                                        Datos de la Persona
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label class="form-label">DNI *</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" name="dni" id="dni"
                                                    value="{{ old('dni') }}" required
                                                    placeholder="Ingrese DNI sin puntos">
                                                <button type="button" class="btn btn-outline-primary" id="buscarPersona">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                            <div id="estadoPersona" class="mt-2"></div>
                                            @error('dni')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div id="datosPersona">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Apellido *</label>
                                                <input type="text" class="form-control" name="apellido" id="apellido"
                                                    value="{{ old('apellido') }}" required>
                                                @error('apellido')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Nombre *</label>
                                                <input type="text" class="form-control" name="nombre" id="nombre"
                                                    value="{{ old('nombre') }}" required>
                                                @error('nombre')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Nacionalidad</label>
                                                <input type="text" class="form-control" name="nacionalidad"
                                                    id="nacionalidad" value="{{ old('nacionalidad', 'Argentina') }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Fecha de Nacimiento</label>
                                                <input type="date" class="form-control" name="fecha_nacimiento"
                                                    id="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}">
                                            </div>
                                            <div class="col-md-8 mb-3">
                                                <label class="form-label">Calle</label>
                                                <input type="text" class="form-control" name="calle" id="calle"
                                                    value="{{ old('calle') }}">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Altura</label>
                                                <input type="number" class="form-control" name="altura" id="altura"
                                                    value="{{ old('altura') }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Localidad</label>
                                                <input type="text" class="form-control" name="localidad_desc"
                                                    id="localidad_desc" value="{{ old('localidad_desc') }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Teléfono</label>
                                                <input type="text" class="form-control" name="telefono" id="telefono"
                                                    value="{{ old('telefono') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Datos del Vehículo --}}
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="fas fa-car me-2"></i>
                                        Datos del Vehículo
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Dominio *</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control text-uppercase" name="dominio"
                                                    id="dominio" value="{{ old('dominio') }}" required
                                                    placeholder="ABC123 o AB123CD">
                                                <button type="button" class="btn btn-outline-primary"
                                                    id="buscarVehiculo">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                            <div id="estadoVehiculo" class="mt-2"></div>
                                            @error('dominio')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div id="datosVehiculo">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Marca</label>
                                                <select class="form-select" name="marca_id" id="marca_id">
                                                    <option value="">Seleccionar marca...</option>
                                                </select>
                                                <input type="text" class="form-control mt-2" name="nueva_marca"
                                                    id="nueva_marca" placeholder="O escribir nueva marca..."
                                                    style="display: none;">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Modelo</label>
                                                <select class="form-select" name="modelo_id" id="modelo_id">
                                                    <option value="">Seleccionar modelo...</option>
                                                </select>
                                                <input type="text" class="form-control mt-2" name="nuevo_modelo"
                                                    id="nuevo_modelo" placeholder="O escribir nuevo modelo..."
                                                    style="display: none;">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Color</label>
                                                <input type="text" class="form-control" name="color" id="color"
                                                    value="{{ old('color') }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Tipo de Vehículo</label>
                                                <select class="form-select" name="tipo_vehiculo" id="tipo_vehiculo">
                                                    <option value="">Seleccionar...</option>
                                                    <option value="Moto">Moto</option>
                                                    <option value="Automóvil">Automóvil</option>
                                                    <option value="Camioneta">Camioneta</option>
                                                    <option value="Camión">Camión</option>
                                                    <option value="Furgón">Furgón</option>
                                                    <option value="Acoplado">Acoplado</option>
                                                    <option value="Taxi">Taxi</option>
                                                    <option value="Remis">Remis</option>
                                                    <option value="Colectivo">Colectivo</option>
                                                    <option value="Transporte escolar">Transporte escolar</option>
                                                    <option value="Ambulancia">Ambulancia</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Ubicación del Acta --}}
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="fas fa-map-marker-alt me-2"></i>
                                        Ubicación de la Infracción
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8 mb-3">
                                            <label class="form-label">Nombre de la Calle *</label>
                                            <input type="text" class="form-control" name="ubicacion_calle"
                                                id="ubicacion_calle" value="{{ old('ubicacion_calle') }}" required
                                                placeholder="Ej: Av. Argentina">
                                            @error('ubicacion_calle')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Altura</label>
                                            <input type="text" class="form-control" name="ubicacion_altura"
                                                id="ubicacion_altura" value="{{ old('ubicacion_altura') }}"
                                                placeholder="Ej: 1234">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Latitud</label>
                                            <input type="number" step="any" class="form-control" name="latitud"
                                                id="latitud" value="{{ old('latitud') }}" placeholder="-38.9336">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Longitud</label>
                                            <input type="number" step="any" class="form-control" name="longitud"
                                                id="longitud" value="{{ old('longitud') }}" placeholder="-68.0000">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <button type="button" class="btn btn-outline-success" id="obtenerUbicacion">
                                            <i class="fas fa-crosshairs me-2"></i>
                                            Obtener Ubicación GPS
                                        </button>
                                        <small class="text-muted d-block mt-1">
                                            Haga clic para capturar automáticamente las coordenadas GPS
                                        </small>
                                    </div>

                                    <!-- Campo oculto para la ubicación completa -->
                                    <input type="hidden" name="ubicacion" id="ubicacion"
                                        value="{{ old('ubicacion') }}">
                                </div>
                            </div>

                            {{-- Datos del Acta --}}
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="fas fa-clipboard me-2"></i>
                                        Datos del Acta
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Tipo de Acta *</label>
                                            <select class="form-select" name="tipo_acta" required>
                                                <option value="">Seleccionar...</option>
                                                <option value="A" {{ old('tipo_acta') == 'A' ? 'selected' : '' }}>
                                                    Abasto</option>
                                                <option value="B" {{ old('tipo_acta') == 'B' ? 'selected' : '' }}>
                                                    Bromatología</option>
                                                <option value="C" {{ old('tipo_acta') == 'C' ? 'selected' : '' }}>
                                                    Comercio</option>
                                                <option value="T" {{ old('tipo_acta') == 'T' ? 'selected' : '' }}>
                                                    Tránsito</option>
                                                <option value="S" {{ old('tipo_acta') == 'S' ? 'selected' : '' }}>
                                                    Sanidad e Higiene</option>
                                            </select>
                                            @error('tipo_acta')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Destino del Acta *</label>
                                            <select class="form-select" name="destino_acta" required>
                                                <option value="">Seleccionar...</option>
                                                <option value="Aceptada"
                                                    {{ old('destino_acta') == 'Aceptada' ? 'selected' : '' }}>Aceptada
                                                </option>
                                                <option value="Rechazada"
                                                    {{ old('destino_acta') == 'Rechazada' ? 'selected' : '' }}>Rechazada
                                                </option>
                                                <option value="Depositada en vehículo"
                                                    {{ old('destino_acta') == 'Depositada en vehículo' ? 'selected' : '' }}>
                                                    Depositada en vehículo</option>
                                                <option value="Imposible Entregar"
                                                    {{ old('destino_acta') == 'Imposible Entregar' ? 'selected' : '' }}>
                                                    Imposible Entregar</option>
                                            </select>
                                            @error('destino_acta')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Tipos de Infracción *</label>
                                        <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                            @foreach ($tiposInfraccion as $tipo)
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="tipos_infraccion[]" value="{{ $tipo->id }}"
                                                        id="tipo_{{ $tipo->id }}"
                                                        {{ in_array($tipo->id, old('tipos_infraccion', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label w-100" for="tipo_{{ $tipo->id }}">
                                                        <div class="d-flex justify-content-between align-items-start">
                                                            <div class="flex-grow-1">
                                                                <strong class="text-danger">{{ $tipo->codigo }}</strong>
                                                                <div class="small text-muted">{{ $tipo->descripcion }}
                                                                </div>
                                                            </div>
                                                            @if ($tipo->sam > 0)
                                                                <span class="badge bg-warning text-dark ms-2">
                                                                    ${{ number_format($tipo->sam, 2) }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                        <small class="text-muted">
                                            Seleccione una o más infracciones tocando las casillas
                                        </small>
                                        @error('tipos_infraccion')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Motivo *</label>
                                        <textarea class="form-control" name="motivo" rows="3" required>{{ old('motivo') }}</textarea>
                                        @error('motivo')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Observaciones Comunes</label>
                                        <div class="row">
                                            @foreach ($observacionesComunes as $obs)
                                                <div class="col-12 mb-1">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="{{ $obs }}" id="obs_{{ $loop->index }}"
                                                            onchange="agregarObservacion(this)">
                                                        <label class="form-check-label small"
                                                            for="obs_{{ $loop->index }}">
                                                            {{ $obs }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Observaciones Adicionales</label>
                                        <textarea class="form-control" name="observaciones" id="observaciones" rows="3">{{ old('observaciones') }}</textarea>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="es_verbal"
                                                    id="es_verbal" {{ old('es_verbal') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="es_verbal">
                                                    Es Verbal
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="retiene_licencia"
                                                    id="retiene_licencia" {{ old('retiene_licencia') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="retiene_licencia">
                                                    Retiene Licencia
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="retiene_vehiculo"
                                                    id="retiene_vehiculo" {{ old('retiene_vehiculo') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="retiene_vehiculo">
                                                    Retiene Vehículo
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Documentación --}}
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="fas fa-camera me-2"></i>
                                        Documentación Fotográfica
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Tomar/Subir Fotos</label>
                                        <input type="file" class="form-control" name="imagenes[]" accept="image/*"
                                            multiple capture="camera">
                                        <div class="form-text">
                                            Máximo 5 MB por imagen. Puede seleccionar múltiples imágenes.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Botones --}}
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-danger btn-lg">
                                    <i class="fas fa-save me-2"></i>
                                    Crear Acta
                                </button>
                                <a href="{{ route('infracciones.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Volver
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cargar marcas al inicio
            cargarMarcas();

            // BÚSQUEDA AUTOMÁTICA DE PERSONA AL SALIR DEL CAMPO DNI (onBlur)
            document.getElementById('dni').addEventListener('blur', function() {
                const dni = this.value.trim();
                if (dni && dni.length >= 7) {
                    buscarPersonaAutomatico(dni);
                }
            });

            // BÚSQUEDA AUTOMÁTICA DE VEHÍCULO AL HACER FOCO EN DOMINIO (onFocus)
            document.getElementById('dominio').addEventListener('focus', function() {
                const dominio = this.value.trim();
                if (dominio && dominio.length >= 6) {
                    buscarVehiculoAutomatico(dominio);
                }
            });

            // Búsqueda manual de persona
            document.getElementById('buscarPersona').addEventListener('click', function() {
                const dni = document.getElementById('dni').value;
                if (!dni) {
                    mostrarAlerta('Ingrese un DNI', 'warning');
                    return;
                }
                buscarPersonaAutomatico(dni);
            });

            // Búsqueda manual de vehículo
            document.getElementById('buscarVehiculo').addEventListener('click', function() {
                const dominio = document.getElementById('dominio').value;
                if (!dominio) {
                    mostrarAlerta('Ingrese un dominio', 'warning');
                    return;
                }
                buscarVehiculoAutomatico(dominio);
            });

            // Manejar cambio de marca
            document.getElementById('marca_id').addEventListener('change', function() {
                const marcaId = this.value;
                if (marcaId) {
                    cargarModelos(marcaId);
                    document.getElementById('nueva_marca').style.display = 'none';
                } else {
                    document.getElementById('nueva_marca').style.display = 'block';
                    document.getElementById('modelo_id').innerHTML =
                        '<option value="">Seleccionar modelo...</option>';
                    document.getElementById('nuevo_modelo').style.display = 'block';
                }
            });

            // Obtener ubicación GPS
            document.getElementById('obtenerUbicacion').addEventListener('click', function() {
                obtenerUbicacionGPS();
            });

            // Convertir dominio a mayúsculas
            document.getElementById('dominio').addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });

            // Actualizar campo ubicación cuando cambian calle o altura
            document.getElementById('ubicacion_calle').addEventListener('input', actualizarUbicacionCompleta);
            document.getElementById('ubicacion_altura').addEventListener('input', actualizarUbicacionCompleta);
        });

        // FUNCIÓN MEJORADA DE BÚSQUEDA AUTOMÁTICA DE PERSONA
        function buscarPersonaAutomatico(dni) {
            const estadoDiv = document.getElementById('estadoPersona');
            estadoDiv.innerHTML =
                '<div class="spinner-border spinner-border-sm me-2" role="status"></div>Buscando persona...';

            fetch(`{{ route('infracciones.buscar-persona') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        dni: dni
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.encontrada) {
                        const persona = data.persona;

                        // Cargar datos encontrados
                        document.getElementById('apellido').value = persona.apellido || '';
                        document.getElementById('nombre').value = persona.nombre || '';
                        document.getElementById('nacionalidad').value = persona.nacionalidad || 'Argentina';
                        document.getElementById('fecha_nacimiento').value = persona.fecha_nacimiento || '';
                        document.getElementById('calle').value = persona.calle || '';
                        document.getElementById('altura').value = persona.altura || '';
                        document.getElementById('localidad_desc').value = persona.localidad_desc || '';
                        document.getElementById('telefono').value = persona.telefono || '';

                        estadoDiv.innerHTML =
                            '<div class="alert alert-success alert-sm py-2"><i class="fas fa-check me-2"></i>Persona encontrada y datos cargados</div>';
                    } else {
                        // LIMPIAR TODOS LOS CAMPOS SI NO ENCUENTRA LA PERSONA
                        document.getElementById('apellido').value = '';
                        document.getElementById('nombre').value = '';
                        document.getElementById('nacionalidad').value = 'Argentina';
                        document.getElementById('fecha_nacimiento').value = '';
                        document.getElementById('calle').value = '';
                        document.getElementById('altura').value = '';
                        document.getElementById('localidad_desc').value = '';
                        document.getElementById('telefono').value = '';

                        estadoDiv.innerHTML =
                            '<div class="alert alert-info alert-sm py-2"><i class="fas fa-info me-2"></i>Persona no encontrada. Complete los datos manualmente.</div>';

                        // Enfocar el primer campo para carga manual
                        document.getElementById('apellido').focus();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    estadoDiv.innerHTML =
                        '<div class="alert alert-danger alert-sm py-2"><i class="fas fa-exclamation-triangle me-2"></i>Error al buscar persona</div>';
                });
        }

        // FUNCIÓN MEJORADA DE BÚSQUEDA AUTOMÁTICA DE VEHÍCULO - CORREGIDA
        function buscarVehiculoAutomatico(dominio) {
            const estadoDiv = document.getElementById('estadoVehiculo');
            estadoDiv.innerHTML =
                '<div class="spinner-border spinner-border-sm me-2" role="status"></div>Buscando vehículo...';

            fetch(`{{ route('infracciones.buscar-vehiculo') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        dominio: dominio
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Respuesta del servidor:', data); // Para debug

                    if (data.encontrado) {
                        const vehiculo = data.vehiculo;

                        // CARGAR DATOS ENCONTRADOS CORRECTAMENTE
                        document.getElementById('color').value = vehiculo.color || '';
                        document.getElementById('tipo_vehiculo').value = vehiculo.tipo_vehiculo || '';

                        // Cargar marca y modelo si existen
                        if (vehiculo.id_marca) {
                            // Primero asegurar que las marcas estén cargadas
                            cargarMarcas().then(() => {
                                document.getElementById('marca_id').value = vehiculo.id_marca;

                                // Cargar modelos de la marca y seleccionar el modelo específico
                                if (vehiculo.id_modelo) {
                                    cargarModelos(vehiculo.id_marca, vehiculo.id_modelo);
                                } else {
                                    cargarModelos(vehiculo.id_marca);
                                }
                            });
                        } else {
                            // Si no hay marca, limpiar campos
                            document.getElementById('marca_id').value = '';
                            document.getElementById('modelo_id').innerHTML =
                                '<option value="">Seleccionar modelo...</option>';
                        }

                        estadoDiv.innerHTML =
                            '<div class="alert alert-success alert-sm py-2"><i class="fas fa-check me-2"></i>Vehículo encontrado y datos cargados</div>';
                    } else {
                        // LIMPIAR TODOS LOS CAMPOS SI NO ENCUENTRA EL VEHÍCULO
                        document.getElementById('color').value = '';
                        document.getElementById('tipo_vehiculo').value = '';
                        document.getElementById('marca_id').value = '';
                        document.getElementById('modelo_id').innerHTML =
                            '<option value="">Seleccionar modelo...</option>';

                        estadoDiv.innerHTML =
                            '<div class="alert alert-info alert-sm py-2"><i class="fas fa-info me-2"></i>Vehículo no encontrado. Complete los datos manualmente.</div>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    estadoDiv.innerHTML =
                        '<div class="alert alert-danger alert-sm py-2"><i class="fas fa-exclamation-triangle me-2"></i>Error al buscar vehículo</div>';
                });
        }

        function cargarMarcas() {
            return fetch(`{{ route('infracciones.obtener-marcas') }}`)
                .then(response => response.json())
                .then(marcas => {
                    const select = document.getElementById('marca_id');
                    select.innerHTML = '<option value="">Seleccionar marca...</option>';
                    marcas.forEach(marca => {
                        select.innerHTML += `<option value="${marca.id}">${marca.marca}</option>`;
                    });
                    return marcas; // Retornar para poder usar .then()
                })
                .catch(error => {
                    console.error('Error al cargar marcas:', error);
                });
        }

        function cargarModelos(marcaId, modeloSeleccionado = null) {
            fetch(`{{ route('infracciones.obtener-modelos') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        marca_id: marcaId
                    })
                })
                .then(response => response.json())
                .then(modelos => {
                    const select = document.getElementById('modelo_id');
                    select.innerHTML = '<option value="">Seleccionar modelo...</option>';
                    modelos.forEach(modelo => {
                        const selected = modeloSeleccionado && modelo.id == modeloSeleccionado ? 'selected' :
                            '';
                        select.innerHTML +=
                            `<option value="${modelo.id}" ${selected}>${modelo.modelo}</option>`;
                    });
                    document.getElementById('nuevo_modelo').style.display = 'none';
                })
                .catch(error => {
                    console.error('Error al cargar modelos:', error);
                });
        }

        function obtenerUbicacionGPS() {
            const btn = document.getElementById('obtenerUbicacion');
            const originalText = btn.innerHTML;

            btn.innerHTML =
                '<div class="spinner-border spinner-border-sm me-2" role="status"></div>Obteniendo ubicación...';
            btn.disabled = true;

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude.toFixed(8);
                        const lng = position.coords.longitude.toFixed(8);

                        document.getElementById('latitud').value = lat;
                        document.getElementById('longitud').value = lng;

                        // Intentar obtener la dirección usando geocodificación inversa
                        btn.innerHTML =
                            '<div class="spinner-border spinner-border-sm me-2" role="status"></div>Obteniendo dirección...';

                        obtenerDireccionPorCoordenadas(lat, lng)
                            .then(direccion => {
                                if (direccion.calle) {
                                    document.getElementById('ubicacion_calle').value = direccion.calle;
                                }
                                if (direccion.altura) {
                                    document.getElementById('ubicacion_altura').value = direccion.altura;
                                }

                                // Actualizar ubicación completa
                                actualizarUbicacionCompleta();

                                btn.innerHTML = '<i class="fas fa-check me-2"></i>Ubicación y dirección obtenidas';
                                btn.disabled = false;
                                btn.classList.replace('btn-outline-success', 'btn-success');

                                mostrarAlerta('Ubicación GPS y dirección obtenidas correctamente', 'success');
                            })
                            .catch(error => {
                                console.log('No se pudo obtener la dirección:', error);
                                // Aun así, actualizar ubicación completa con coordenadas
                                actualizarUbicacionCompleta();

                                btn.innerHTML = '<i class="fas fa-check me-2"></i>Ubicación obtenida';
                                btn.disabled = false;
                                btn.classList.replace('btn-outline-success', 'btn-success');

                                mostrarAlerta('Ubicación GPS obtenida. Complete la dirección manualmente.',
                                    'warning');
                            });

                        // Volver al estado original después de 3 segundos
                        setTimeout(() => {
                            btn.innerHTML = originalText;
                            btn.classList.replace('btn-success', 'btn-outline-success');
                        }, 3000);
                    },
                    function(error) {
                        btn.innerHTML = originalText;
                        btn.disabled = false;

                        let errorMsg = 'Error al obtener ubicación: ';
                        switch (error.code) {
                            case error.PERMISSION_DENIED:
                                errorMsg += 'Permiso denegado por el usuario';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMsg += 'Ubicación no disponible';
                                break;
                            case error.TIMEOUT:
                                errorMsg += 'Tiempo de espera agotado';
                                break;
                            default:
                                errorMsg += 'Error desconocido';
                                break;
                        }
                        mostrarAlerta(errorMsg, 'danger');
                    }, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            } else {
                btn.innerHTML = originalText;
                btn.disabled = false;
                mostrarAlerta('Geolocalización no soportada por este navegador', 'warning');
            }
        }

        // Nueva función para obtener dirección por coordenadas
        function obtenerDireccionPorCoordenadas(lat, lng) {
            return new Promise((resolve, reject) => {
                // Usar Nominatim de OpenStreetMap para geocodificación inversa (gratuito)
                const url =
                    `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`;

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Respuesta de geocodificación:', data);

                        if (data && data.address) {
                            const address = data.address;
                            let calle = '';
                            let altura = '';

                            // Intentar extraer calle y altura de diferentes campos
                            if (address.road) {
                                calle = address.road;
                            } else if (address.pedestrian) {
                                calle = address.pedestrian;
                            } else if (address.path) {
                                calle = address.path;
                            }

                            // Buscar número de casa
                            if (address.house_number) {
                                altura = address.house_number;
                            }

                            resolve({
                                calle: calle,
                                altura: altura,
                                direccionCompleta: data.display_name
                            });
                        } else {
                            reject('No se encontró dirección');
                        }
                    })
                    .catch(error => {
                        console.error('Error en geocodificación:', error);
                        reject(error);
                    });
            });
        }

        function actualizarUbicacionCompleta() {
            const calle = document.getElementById('ubicacion_calle').value.trim();
            const altura = document.getElementById('ubicacion_altura').value.trim();
            const latitud = document.getElementById('latitud').value;
            const longitud = document.getElementById('longitud').value;

            let ubicacionCompleta = '';

            // Construir dirección
            if (calle) {
                ubicacionCompleta = calle;
                if (altura) {
                    ubicacionCompleta += ' ' + altura;
                }
            }

            // Agregar coordenadas si están disponibles
            if (latitud && longitud) {
                if (ubicacionCompleta) {
                    ubicacionCompleta += ` (${latitud}, ${longitud})`;
                } else {
                    ubicacionCompleta = `Lat: ${latitud}, Lng: ${longitud}`;
                }
            }

            document.getElementById('ubicacion').value = ubicacionCompleta;
        }

        function agregarObservacion(checkbox) {
            const observaciones = document.getElementById('observaciones');
            const texto = observaciones.value.trim();
            const nuevaObs = checkbox.value;

            if (checkbox.checked) {
                if (texto) {
                    observaciones.value = texto + '. ' + nuevaObs;
                } else {
                    observaciones.value = nuevaObs;
                }
            } else {
                // Remover observación si se desmarca
                const nuevoTexto = texto.replace(nuevaObs, '')
                    .replace(/\.\s+\./g, '.')
                    .replace(/^\.\s*/, '')
                    .replace(/\.\s*$/, '')
                    .trim();
                observaciones.value = nuevoTexto;
            }
        }

        function mostrarAlerta(mensaje, tipo) {
            // Crear elemento de alerta
            const alerta = document.createElement('div');
            alerta.className = `alert alert-${tipo} alert-dismissible fade show position-fixed`;
            alerta.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 400px;';
            alerta.innerHTML = `
                ${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            // Agregar al body
            document.body.appendChild(alerta);

            // Remover automáticamente después de 5 segundos
            setTimeout(() => {
                if (alerta.parentNode) {
                    alerta.remove();
                }
            }, 5000);
        }

        // Validación del formulario antes de enviar
        document.getElementById('formActa').addEventListener('submit', function(e) {
            // Validar que se haya completado la ubicación
            const ubicacionCalle = document.getElementById('ubicacion_calle').value.trim();

            if (!ubicacionCalle) {
                e.preventDefault();
                mostrarAlerta('Debe completar el nombre de la calle', 'danger');
                document.getElementById('ubicacion_calle').focus();
                return false;
            }

            // Actualizar ubicación completa antes de enviar
            actualizarUbicacionCompleta();

            // Validar que se hayan seleccionado tipos de infracción (ACTUALIZADO PARA CHECKBOXES)
            const tiposSeleccionados = document.querySelectorAll('input[name="tipos_infraccion[]"]:checked');
            if (tiposSeleccionados.length === 0) {
                e.preventDefault();
                mostrarAlerta('Debe seleccionar al menos un tipo de infracción', 'danger');
                // Hacer scroll hasta los tipos de infracción
                document.querySelector('input[name="tipos_infraccion[]"]').scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                return false;
            }

            // Mostrar mensaje de envío
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML =
                '<div class="spinner-border spinner-border-sm me-2" role="status"></div>Creando acta...';
            submitBtn.disabled = true;
        });
    </script>

    <style>
        .alert-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }

        /* Estilos para los checkboxes de infracciones (mejor en móviles) */
        .form-check-label {
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.375rem;
            transition: background-color 0.15s ease-in-out;
        }

        .form-check-label:hover {
            background-color: #f8f9fa;
        }

        .form-check-input:checked+.form-check-label {
            background-color: #fff2f2;
            border-left: 4px solid #dc3545;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        .card-header {
            position: sticky;
            top: 0;
            z-index: 10;
        }

        /* Estilos para móviles */
        @media (max-width: 768px) {
            .card-header h6 {
                font-size: 0.9rem;
            }

            .btn-lg {
                padding: 0.75rem 1rem;
                font-size: 1rem;
            }

            .input-group .btn {
                padding: 0.375rem 0.5rem;
            }

            /* Mejorar checkboxes en móviles */
            .form-check {
                margin-bottom: 0.75rem !important;
            }

            .form-check-input {
                transform: scale(1.2);
                margin-top: 0.25rem;
            }

            .form-check-label {
                padding: 0.75rem 0.5rem;
                margin-left: 0.5rem;
                font-size: 0.9rem;
            }

            /* Hacer más fácil tocar en móviles */
            .form-check-label {
                min-height: 44px;
                display: flex;
                align-items: center;
            }
        }

        /* Animaciones para el feedback */
        .alert {
            animation: slideInRight 0.3s ease-out;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100%);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Estilos para los estados de búsqueda */
        .spinner-border {
            color: #dc3545;
        }

        .alert-sm {
            border-radius: 0.375rem;
            margin-bottom: 0;
        }

        /* Mejorar el contenedor de tipos de infracción */
        .border {
            border: 2px solid #dee2e6 !important;
            background-color: #f8f9fa;
        }

        /* Estilos para el scroll del contenedor de infracciones */
        .border::-webkit-scrollbar {
            width: 8px;
        }

        .border::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .border::-webkit-scrollbar-thumb {
            background: #dc3545;
            border-radius: 4px;
        }

        .border::-webkit-scrollbar-thumb:hover {
            background: #c82333;
        }

        /* Destacar infracciones seleccionadas */
        .form-check-input:checked+.form-check-label .badge {
            background-color: #dc3545 !important;
            color: white !important;
        }

        /* Mejorar accesibilidad táctil */
        @media (pointer: coarse) {
            .btn {
                min-height: 44px;
            }

            .form-control,
            .form-select {
                min-height: 44px;
                font-size: 16px;
                /* Evita zoom en iOS */
            }

            .form-check-input {
                min-width: 20px;
                min-height: 20px;
            }
        }

        /* Efecto visual para botón de ubicación */
        .btn-success {
            animation: pulse 0.5s ease-in-out;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }
    </style>
    @endsection@extends('layouts.app')

@section('title', 'Nueva Acta de Contravención')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-file-alt me-2"></i>
                            Nueva Acta de Contravención
                        </h4>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('infracciones.store') }}" method="POST" enctype="multipart/form-data"
                            id="formActa">
                            @csrf

                            {{-- Datos de la Persona --}}
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="fas fa-user me-2"></i>
                                        Datos de la Persona
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label class="form-label">DNI *</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" name="dni" id="dni"
                                                    value="{{ old('dni') }}" required
                                                    placeholder="Ingrese DNI sin puntos">
                                                <button type="button" class="btn btn-outline-primary"
                                                    id="buscarPersona">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                            <div id="estadoPersona" class="mt-2"></div>
                                            @error('dni')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div id="datosPersona">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Apellido *</label>
                                                <input type="text" class="form-control" name="apellido"
                                                    id="apellido" value="{{ old('apellido') }}" required>
                                                @error('apellido')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Nombre *</label>
                                                <input type="text" class="form-control" name="nombre" id="nombre"
                                                    value="{{ old('nombre') }}" required>
                                                @error('nombre')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Nacionalidad</label>
                                                <input type="text" class="form-control" name="nacionalidad"
                                                    id="nacionalidad" value="{{ old('nacionalidad', 'Argentina') }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Fecha de Nacimiento</label>
                                                <input type="date" class="form-control" name="fecha_nacimiento"
                                                    id="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}">
                                            </div>
                                            <div class="col-md-8 mb-3">
                                                <label class="form-label">Calle</label>
                                                <input type="text" class="form-control" name="calle" id="calle"
                                                    value="{{ old('calle') }}">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Altura</label>
                                                <input type="number" class="form-control" name="altura" id="altura"
                                                    value="{{ old('altura') }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Localidad</label>
                                                <input type="text" class="form-control" name="localidad_desc"
                                                    id="localidad_desc" value="{{ old('localidad_desc') }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Teléfono</label>
                                                <input type="text" class="form-control" name="telefono"
                                                    id="telefono" value="{{ old('telefono') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Datos del Vehículo --}}
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="fas fa-car me-2"></i>
                                        Datos del Vehículo
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Dominio *</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control text-uppercase" name="dominio"
                                                    id="dominio" value="{{ old('dominio') }}" required
                                                    placeholder="ABC123 o AB123CD">
                                                <button type="button" class="btn btn-outline-primary"
                                                    id="buscarVehiculo">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                            <div id="estadoVehiculo" class="mt-2"></div>
                                            @error('dominio')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div id="datosVehiculo">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Marca</label>
                                                <select class="form-select" name="marca_id" id="marca_id">
                                                    <option value="">Seleccionar marca...</option>
                                                </select>
                                                <input type="text" class="form-control mt-2" name="nueva_marca"
                                                    id="nueva_marca" placeholder="O escribir nueva marca..."
                                                    style="display: none;">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Modelo</label>
                                                <select class="form-select" name="modelo_id" id="modelo_id">
                                                    <option value="">Seleccionar modelo...</option>
                                                </select>
                                                <input type="text" class="form-control mt-2" name="nuevo_modelo"
                                                    id="nuevo_modelo" placeholder="O escribir nuevo modelo..."
                                                    style="display: none;">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Color</label>
                                                <input type="text" class="form-control" name="color" id="color"
                                                    value="{{ old('color') }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Tipo de Vehículo</label>
                                                <select class="form-select" name="tipo_vehiculo" id="tipo_vehiculo">
                                                    <option value="">Seleccionar...</option>
                                                    <option value="Moto">Moto</option>
                                                    <option value="Automóvil">Automóvil</option>
                                                    <option value="Camioneta">Camioneta</option>
                                                    <option value="Camión">Camión</option>
                                                    <option value="Furgón">Furgón</option>
                                                    <option value="Acoplado">Acoplado</option>
                                                    <option value="Taxi">Taxi</option>
                                                    <option value="Remis">Remis</option>
                                                    <option value="Colectivo">Colectivo</option>
                                                    <option value="Transporte escolar">Transporte escolar</option>
                                                    <option value="Ambulancia">Ambulancia</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Ubicación del Acta --}}
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="fas fa-map-marker-alt me-2"></i>
                                        Ubicación de la Infracción
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8 mb-3">
                                            <label class="form-label">Nombre de la Calle *</label>
                                            <input type="text" class="form-control" name="ubicacion_calle"
                                                id="ubicacion_calle" value="{{ old('ubicacion_calle') }}" required
                                                placeholder="Ej: Av. Argentina">
                                            @error('ubicacion_calle')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Altura</label>
                                            <input type="text" class="form-control" name="ubicacion_altura"
                                                id="ubicacion_altura" value="{{ old('ubicacion_altura') }}"
                                                placeholder="Ej: 1234">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Latitud</label>
                                            <input type="number" step="any" class="form-control" name="latitud"
                                                id="latitud" value="{{ old('latitud') }}" placeholder="-38.9336">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Longitud</label>
                                            <input type="number" step="any" class="form-control" name="longitud"
                                                id="longitud" value="{{ old('longitud') }}" placeholder="-68.0000">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <button type="button" class="btn btn-outline-success" id="obtenerUbicacion">
                                            <i class="fas fa-crosshairs me-2"></i>
                                            Obtener Ubicación GPS
                                        </button>
                                        <small class="text-muted d-block mt-1">
                                            Haga clic para capturar automáticamente las coordenadas GPS
                                        </small>
                                    </div>

                                    <!-- Campo oculto para la ubicación completa -->
                                    <input type="hidden" name="ubicacion" id="ubicacion"
                                        value="{{ old('ubicacion') }}">
                                </div>
                            </div>

                            {{-- Datos del Acta --}}
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="fas fa-clipboard me-2"></i>
                                        Datos del Acta
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Tipo de Acta *</label>
                                            <select class="form-select" name="tipo_acta" required>
                                                <option value="">Seleccionar...</option>
                                                <option value="A" {{ old('tipo_acta') == 'A' ? 'selected' : '' }}>
                                                    Abasto</option>
                                                <option value="B" {{ old('tipo_acta') == 'B' ? 'selected' : '' }}>
                                                    Bromatología</option>
                                                <option value="C" {{ old('tipo_acta') == 'C' ? 'selected' : '' }}>
                                                    Comercio</option>
                                                <option value="T" {{ old('tipo_acta') == 'T' ? 'selected' : '' }}>
                                                    Tránsito</option>
                                                <option value="S" {{ old('tipo_acta') == 'S' ? 'selected' : '' }}>
                                                    Sanidad e Higiene</option>
                                            </select>
                                            @error('tipo_acta')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Destino del Acta *</label>
                                            <select class="form-select" name="destino_acta" required>
                                                <option value="">Seleccionar...</option>
                                                <option value="Aceptada"
                                                    {{ old('destino_acta') == 'Aceptada' ? 'selected' : '' }}>Aceptada
                                                </option>
                                                <option value="Rechazada"
                                                    {{ old('destino_acta') == 'Rechazada' ? 'selected' : '' }}>Rechazada
                                                </option>
                                                <option value="Depositada en vehículo"
                                                    {{ old('destino_acta') == 'Depositada en vehículo' ? 'selected' : '' }}>
                                                    Depositada en vehículo</option>
                                                <option value="Imposible Entregar"
                                                    {{ old('destino_acta') == 'Imposible Entregar' ? 'selected' : '' }}>
                                                    Imposible Entregar</option>
                                            </select>
                                            @error('destino_acta')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Tipos de Infracción *</label>
                                        <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                            @foreach ($tiposInfraccion as $tipo)
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="tipos_infraccion[]" value="{{ $tipo->id }}"
                                                        id="tipo_{{ $tipo->id }}"
                                                        {{ in_array($tipo->id, old('tipos_infraccion', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label w-100" for="tipo_{{ $tipo->id }}">
                                                        <div class="d-flex justify-content-between align-items-start">
                                                            <div class="flex-grow-1">
                                                                <strong class="text-danger">{{ $tipo->codigo }}</strong>
                                                                <div class="small text-muted">{{ $tipo->descripcion }}
                                                                </div>
                                                            </div>
                                                            @if ($tipo->sam > 0)
                                                                <span class="badge bg-warning text-dark ms-2">
                                                                    ${{ number_format($tipo->sam, 2) }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                        <small class="text-muted">
                                            Seleccione una o más infracciones tocando las casillas
                                        </small>
                                        @error('tipos_infraccion')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Motivo *</label>
                                        <textarea class="form-control" name="motivo" rows="3" required>{{ old('motivo') }}</textarea>
                                        @error('motivo')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Observaciones Comunes</label>
                                        <div class="row">
                                            @foreach ($observacionesComunes as $obs)
                                                <div class="col-12 mb-1">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            value="{{ $obs }}" id="obs_{{ $loop->index }}"
                                                            onchange="agregarObservacion(this)">
                                                        <label class="form-check-label small"
                                                            for="obs_{{ $loop->index }}">
                                                            {{ $obs }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Observaciones Adicionales</label>
                                        <textarea class="form-control" name="observaciones" id="observaciones" rows="3">{{ old('observaciones') }}</textarea>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="es_verbal"
                                                    id="es_verbal" {{ old('es_verbal') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="es_verbal">
                                                    Es Verbal
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="retiene_licencia"
                                                    id="retiene_licencia" {{ old('retiene_licencia') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="retiene_licencia">
                                                    Retiene Licencia
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="retiene_vehiculo"
                                                    id="retiene_vehiculo" {{ old('retiene_vehiculo') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="retiene_vehiculo">
                                                    Retiene Vehículo
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Documentación --}}
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="fas fa-camera me-2"></i>
                                        Documentación Fotográfica
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Tomar/Subir Fotos</label>
                                        <input type="file" class="form-control" name="imagenes[]" accept="image/*"
                                            multiple capture="camera">
                                        <div class="form-text">
                                            Máximo 5 MB por imagen. Puede seleccionar múltiples imágenes.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Botones --}}
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-danger btn-lg">
                                    <i class="fas fa-save me-2"></i>
                                    Crear Acta
                                </button>
                                <a href="{{ route('infracciones.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Volver
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cargar marcas al inicio
            cargarMarcas();

            // BÚSQUEDA AUTOMÁTICA DE PERSONA AL SALIR DEL CAMPO DNI (onBlur)
            document.getElementById('dni').addEventListener('blur', function() {
                const dni = this.value.trim();
                if (dni && dni.length >= 7) {
                    buscarPersonaAutomatico(dni);
                }
            });

            // BÚSQUEDA AUTOMÁTICA DE VEHÍCULO AL SALIR DEL CAMPO DOMINIO (onBlur) - CORREGIDO
            document.getElementById('dominio').addEventListener('blur', function() {
                const dominio = this.value.trim();
                if (dominio && dominio.length >= 6) {
                    buscarVehiculoAutomatico(dominio);
                }
            });

            // Búsqueda manual de persona
            document.getElementById('buscarPersona').addEventListener('click', function() {
                const dni = document.getElementById('dni').value;
                if (!dni) {
                    mostrarAlerta('Ingrese un DNI', 'warning');
                    return;
                }
                buscarPersonaAutomatico(dni);
            });

            // Búsqueda manual de vehículo
            document.getElementById('buscarVehiculo').addEventListener('click', function() {
                const dominio = document.getElementById('dominio').value;
                if (!dominio) {
                    mostrarAlerta('Ingrese un dominio', 'warning');
                    return;
                }
                buscarVehiculoAutomatico(dominio);
            });

            // Manejar cambio de marca
            document.getElementById('marca_id').addEventListener('change', function() {
                const marcaId = this.value;
                if (marcaId) {
                    cargarModelos(marcaId);
                    document.getElementById('nueva_marca').style.display = 'none';
                } else {
                    document.getElementById('nueva_marca').style.display = 'block';
                    document.getElementById('modelo_id').innerHTML =
                        '<option value="">Seleccionar modelo...</option>';
                    document.getElementById('nuevo_modelo').style.display = 'block';
                }
            });

            // Obtener ubicación GPS
            document.getElementById('obtenerUbicacion').addEventListener('click', function() {
                obtenerUbicacionGPS();
            });

            // Convertir dominio a mayúsculas
            document.getElementById('dominio').addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });

            // Actualizar campo ubicación cuando cambian calle o altura
            document.getElementById('ubicacion_calle').addEventListener('input', actualizarUbicacionCompleta);
            document.getElementById('ubicacion_altura').addEventListener('input', actualizarUbicacionCompleta);
        });

        // FUNCIÓN MEJORADA DE BÚSQUEDA AUTOMÁTICA DE PERSONA
        function buscarPersonaAutomatico(dni) {
            const estadoDiv = document.getElementById('estadoPersona');
            estadoDiv.innerHTML =
                '<div class="spinner-border spinner-border-sm me-2" role="status"></div>Buscando persona...';

            fetch(`{{ route('infracciones.buscar-persona') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        dni: dni
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.encontrada) {
                        const persona = data.persona;

                        // Cargar datos encontrados
                        document.getElementById('apellido').value = persona.apellido || '';
                        document.getElementById('nombre').value = persona.nombre || '';
                        document.getElementById('nacionalidad').value = persona.nacionalidad || 'Argentina';
                        document.getElementById('fecha_nacimiento').value = persona.fecha_nacimiento || '';
                        document.getElementById('calle').value = persona.calle || '';
                        document.getElementById('altura').value = persona.altura || '';
                        document.getElementById('localidad_desc').value = persona.localidad_desc || '';
                        document.getElementById('telefono').value = persona.telefono || '';

                        estadoDiv.innerHTML =
                            '<div class="alert alert-success alert-sm py-2"><i class="fas fa-check me-2"></i>Persona encontrada y datos cargados</div>';
                    } else {
                        // LIMPIAR TODOS LOS CAMPOS SI NO ENCUENTRA LA PERSONA
                        document.getElementById('apellido').value = '';
                        document.getElementById('nombre').value = '';
                        document.getElementById('nacionalidad').value = 'Argentina';
                        document.getElementById('fecha_nacimiento').value = '';
                        document.getElementById('calle').value = '';
                        document.getElementById('altura').value = '';
                        document.getElementById('localidad_desc').value = '';
                        document.getElementById('telefono').value = '';

                        estadoDiv.innerHTML =
                            '<div class="alert alert-info alert-sm py-2"><i class="fas fa-info me-2"></i>Persona no encontrada. Complete los datos manualmente.</div>';

                        // Enfocar el primer campo para carga manual
                        document.getElementById('apellido').focus();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    estadoDiv.innerHTML =
                        '<div class="alert alert-danger alert-sm py-2"><i class="fas fa-exclamation-triangle me-2"></i>Error al buscar persona</div>';
                });
        }

        // FUNCIÓN MEJORADA DE BÚSQUEDA AUTOMÁTICA DE VEHÍCULO - CON MÁS DEBUG
        function buscarVehiculoAutomatico(dominio) {
            const estadoDiv = document.getElementById('estadoVehiculo');
            estadoDiv.innerHTML =
                '<div class="spinner-border spinner-border-sm me-2" role="status"></div>Buscando vehículo...';

            console.log('Iniciando búsqueda de vehículo para dominio:', dominio);

            fetch(`{{ route('infracciones.buscar-vehiculo') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        dominio: dominio
                    })
                })
                .then(response => {
                    console.log('Status de respuesta:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Respuesta completa del servidor:', data);

                    if (data.encontrado) {
                        const vehiculo = data.vehiculo;
                        console.log('Datos del vehículo a cargar:', vehiculo);

                        // CARGAR DATOS ENCONTRADOS CORRECTAMENTE
                        document.getElementById('color').value = vehiculo.color || '';
                        document.getElementById('tipo_vehiculo').value = vehiculo.tipo_vehiculo || '';

                        console.log('Color cargado:', vehiculo.color);
                        console.log('Tipo vehículo cargado:', vehiculo.tipo_vehiculo);

                        // Cargar marca y modelo si existen
                        if (vehiculo.id_marca) {
                            console.log('Cargando marca ID:', vehiculo.id_marca);
                            // Primero asegurar que las marcas estén cargadas
                            cargarMarcas().then(() => {
                                console.log('Marcas cargadas, estableciendo valor...');
                                document.getElementById('marca_id').value = vehiculo.id_marca;

                                // Cargar modelos de la marca y seleccionar el modelo específico
                                if (vehiculo.id_modelo) {
                                    console.log('Cargando modelos para marca y seleccionando modelo:', vehiculo
                                        .id_modelo);
                                    cargarModelos(vehiculo.id_marca, vehiculo.id_modelo);
                                } else {
                                    console.log('Solo cargando modelos para marca');
                                    cargarModelos(vehiculo.id_marca);
                                }
                            });
                        } else {
                            console.log('No hay marca para cargar');
                            // Si no hay marca, limpiar campos
                            document.getElementById('marca_id').value = '';
                            document.getElementById('modelo_id').innerHTML =
                                '<option value="">Seleccionar modelo...</option>';
                        }

                        estadoDiv.innerHTML =
                            '<div class="alert alert-success alert-sm py-2"><i class="fas fa-check me-2"></i>Vehículo encontrado y datos cargados</div>';
                    } else {
                        console.log('Vehículo no encontrado, limpiando campos');
                        // LIMPIAR TODOS LOS CAMPOS SI NO ENCUENTRA EL VEHÍCULO
                        document.getElementById('color').value = '';
                        document.getElementById('tipo_vehiculo').value = '';
                        document.getElementById('marca_id').value = '';
                        document.getElementById('modelo_id').innerHTML =
                            '<option value="">Seleccionar modelo...</option>';

                        estadoDiv.innerHTML =
                            '<div class="alert alert-info alert-sm py-2"><i class="fas fa-info me-2"></i>Vehículo no encontrado. Complete los datos manualmente.</div>';
                    }
                })
                .catch(error => {
                    console.error('Error completo:', error);
                    estadoDiv.innerHTML =
                        '<div class="alert alert-danger alert-sm py-2"><i class="fas fa-exclamation-triangle me-2"></i>Error al buscar vehículo: ' +
                        error.message + '</div>';
                });
        }

        function cargarMarcas() {
            console.log('Iniciando carga de marcas...');
            return fetch(`{{ route('infracciones.obtener-marcas') }}`)
                .then(response => {
                    console.log('Status response marcas:', response.status);
                    return response.json();
                })
                .then(marcas => {
                    console.log('Marcas recibidas:', marcas);
                    const select = document.getElementById('marca_id');
                    select.innerHTML = '<option value="">Seleccionar marca...</option>';

                    if (Array.isArray(marcas)) {
                        marcas.forEach(marca => {
                            select.innerHTML += `<option value="${marca.id}">${marca.marca}</option>`;
                        });
                        console.log('Marcas cargadas exitosamente:', marcas.length);
                    } else {
                        console.error('Las marcas no son un array:', marcas);
                    }

                    return marcas; // Retornar para poder usar .then()
                })
                .catch(error => {
                    console.error('Error al cargar marcas:', error);
                    throw error;
                });
        }

        function cargarModelos(marcaId, modeloSeleccionado = null) {
            console.log('Cargando modelos para marca ID:', marcaId, 'modelo seleccionado:', modeloSeleccionado);

            return fetch(`{{ route('infracciones.obtener-modelos') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        marca_id: marcaId
                    })
                })
                .then(response => {
                    console.log('Status response modelos:', response.status);
                    return response.json();
                })
                .then(modelos => {
                    console.log('Modelos recibidos:', modelos);
                    const select = document.getElementById('modelo_id');
                    select.innerHTML = '<option value="">Seleccionar modelo...</option>';

                    if (Array.isArray(modelos)) {
                        modelos.forEach(modelo => {
                            const selected = modeloSeleccionado && modelo.id == modeloSeleccionado ?
                                'selected' : '';
                            select.innerHTML +=
                                `<option value="${modelo.id}" ${selected}>${modelo.modelo}</option>`;
                        });
                        console.log('Modelos cargados exitosamente:', modelos.length);

                        if (modeloSeleccionado) {
                            console.log('Modelo seleccionado establecido:', modeloSeleccionado);
                        }
                    } else {
                        console.error('Los modelos no son un array:', modelos);
                    }

                    document.getElementById('nuevo_modelo').style.display = 'none';
                    return modelos;
                })
                .catch(error => {
                    console.error('Error al cargar modelos:', error);
                    throw error;
                });
        }

        function obtenerUbicacionGPS() {
            const btn = document.getElementById('obtenerUbicacion');
            const originalText = btn.innerHTML;

            btn.innerHTML =
            '<div class="spinner-border spinner-border-sm me-2" role="status"></div>Obteniendo ubicación...';
            btn.disabled = true;

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude.toFixed(8);
                        const lng = position.coords.longitude.toFixed(8);

                        document.getElementById('latitud').value = lat;
                        document.getElementById('longitud').value = lng;

                        // Intentar obtener la dirección usando geocodificación inversa
                        btn.innerHTML =
                            '<div class="spinner-border spinner-border-sm me-2" role="status"></div>Obteniendo dirección...';

                        obtenerDireccionPorCoordenadas(lat, lng)
                            .then(direccion => {
                                if (direccion.calle) {
                                    document.getElementById('ubicacion_calle').value = direccion.calle;
                                }
                                if (direccion.altura) {
                                    document.getElementById('ubicacion_altura').value = direccion.altura;
                                }

                                // Actualizar ubicación completa
                                actualizarUbicacionCompleta();

                                btn.innerHTML = '<i class="fas fa-check me-2"></i>Ubicación y dirección obtenidas';
                                btn.disabled = false;
                                btn.classList.replace('btn-outline-success', 'btn-success');

                                mostrarAlerta('Ubicación GPS y dirección obtenidas correctamente', 'success');
                            })
                            .catch(error => {
                                console.log('No se pudo obtener la dirección:', error);
                                // Aun así, actualizar ubicación completa con coordenadas
                                actualizarUbicacionCompleta();

                                btn.innerHTML = '<i class="fas fa-check me-2"></i>Ubicación obtenida';
                                btn.disabled = false;
                                btn.classList.replace('btn-outline-success', 'btn-success');

                                mostrarAlerta('Ubicación GPS obtenida. Complete la dirección manualmente.',
                                    'warning');
                            });

                        // Volver al estado original después de 3 segundos
                        setTimeout(() => {
                            btn.innerHTML = originalText;
                            btn.classList.replace('btn-success', 'btn-outline-success');
                        }, 3000);
                    },
                    function(error) {
                        btn.innerHTML = originalText;
                        btn.disabled = false;

                        let errorMsg = 'Error al obtener ubicación: ';
                        switch (error.code) {
                            case error.PERMISSION_DENIED:
                                errorMsg += 'Permiso denegado por el usuario';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMsg += 'Ubicación no disponible';
                                break;
                            case error.TIMEOUT:
                                errorMsg += 'Tiempo de espera agotado';
                                break;
                            default:
                                errorMsg += 'Error desconocido';
                                break;
                        }
                        mostrarAlerta(errorMsg, 'danger');
                    }, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            } else {
                btn.innerHTML = originalText;
                btn.disabled = false;
                mostrarAlerta('Geolocalización no soportada por este navegador', 'warning');
            }
        }

        // Nueva función para obtener dirección por coordenadas
        function obtenerDireccionPorCoordenadas(lat, lng) {
            return new Promise((resolve, reject) => {
                // Usar Nominatim de OpenStreetMap para geocodificación inversa (gratuito)
                const url =
                    `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`;

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Respuesta de geocodificación:', data);

                        if (data && data.address) {
                            const address = data.address;
                            let calle = '';
                            let altura = '';

                            // Intentar extraer calle y altura de diferentes campos
                            if (address.road) {
                                calle = address.road;
                            } else if (address.pedestrian) {
                                calle = address.pedestrian;
                            } else if (address.path) {
                                calle = address.path;
                            }

                            // Buscar número de casa
                            if (address.house_number) {
                                altura = address.house_number;
                            }

                            resolve({
                                calle: calle,
                                altura: altura,
                                direccionCompleta: data.display_name
                            });
                        } else {
                            reject('No se encontró dirección');
                        }
                    })
                    .catch(error => {
                        console.error('Error en geocodificación:', error);
                        reject(error);
                    });
            });
        }

        function actualizarUbicacionCompleta() {
            const calle = document.getElementById('ubicacion_calle').value.trim();
            const altura = document.getElementById('ubicacion_altura').value.trim();
            const latitud = document.getElementById('latitud').value;
            const longitud = document.getElementById('longitud').value;

            let ubicacionCompleta = '';

            // Construir dirección
            if (calle) {
                ubicacionCompleta = calle;
                if (altura) {
                    ubicacionCompleta += ' ' + altura;
                }
            }

            // Agregar coordenadas si están disponibles
            if (latitud && longitud) {
                if (ubicacionCompleta) {
                    ubicacionCompleta += ` (${latitud}, ${longitud})`;
                } else {
                    ubicacionCompleta = `Lat: ${latitud}, Lng: ${longitud}`;
                }
            }

            document.getElementById('ubicacion').value = ubicacionCompleta;
        }

        function agregarObservacion(checkbox) {
            const observaciones = document.getElementById('observaciones');
            const texto = observaciones.value.trim();
            const nuevaObs = checkbox.value;

            if (checkbox.checked) {
                if (texto) {
                    observaciones.value = texto + '. ' + nuevaObs;
                } else {
                    observaciones.value = nuevaObs;
                }
            } else {
                // Remover observación si se desmarca
                const nuevoTexto = texto.replace(nuevaObs, '')
                    .replace(/\.\s+\./g, '.')
                    .replace(/^\.\s*/, '')
                    .replace(/\.\s*$/, '')
                    .trim();
                observaciones.value = nuevoTexto;
            }
        }

        function mostrarAlerta(mensaje, tipo) {
            // Crear elemento de alerta
            const alerta = document.createElement('div');
            alerta.className = `alert alert-${tipo} alert-dismissible fade show position-fixed`;
            alerta.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 400px;';
            alerta.innerHTML = `
                ${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            // Agregar al body
            document.body.appendChild(alerta);

            // Remover automáticamente después de 5 segundos
            setTimeout(() => {
                if (alerta.parentNode) {
                    alerta.remove();
                }
            }, 5000);
        }

        // Validación del formulario antes de enviar
        document.getElementById('formActa').addEventListener('submit', function(e) {
            // Validar que se haya completado la ubicación
            const ubicacionCalle = document.getElementById('ubicacion_calle').value.trim();

            if (!ubicacionCalle) {
                e.preventDefault();
                mostrarAlerta('Debe completar el nombre de la calle', 'danger');
                document.getElementById('ubicacion_calle').focus();
                return false;
            }

            // Actualizar ubicación completa antes de enviar
            actualizarUbicacionCompleta();

            // Validar que se hayan seleccionado tipos de infracción (ACTUALIZADO PARA CHECKBOXES)
            const tiposSeleccionados = document.querySelectorAll('input[name="tipos_infraccion[]"]:checked');
            if (tiposSeleccionados.length === 0) {
                e.preventDefault();
                mostrarAlerta('Debe seleccionar al menos un tipo de infracción', 'danger');
                // Hacer scroll hasta los tipos de infracción
                document.querySelector('input[name="tipos_infraccion[]"]').scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                return false;
            }

            // Mostrar mensaje de envío
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML =
                '<div class="spinner-border spinner-border-sm me-2" role="status"></div>Creando acta...';
            submitBtn.disabled = true;
        });
    </script>

    <style>
        .alert-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }

        #tipos_infraccion {
            min-height: 200px;
        }

        #tipos_infraccion option {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }

        #tipos_infraccion option:hover {
            background-color: #f8f9fa;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        .card-header {
            position: sticky;
            top: 0;
            z-index: 10;
        }

        /* Estilos para móviles */
        @media (max-width: 768px) {
            .card-header h6 {
                font-size: 0.9rem;
            }

            .btn-lg {
                padding: 0.75rem 1rem;
                font-size: 1rem;
            }

            #tipos_infraccion {
                min-height: 150px;
            }

            .input-group .btn {
                padding: 0.375rem 0.5rem;
            }
        }

        /* Mejorar la visualización del select múltiple */
        #tipos_infraccion option:checked {
            background-color: #dc3545 !important;
            color: white !important;
        }

        /* Animaciones para el feedback */
        .alert {
            animation: slideInRight 0.3s ease-out;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100%);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Estilos para los estados de búsqueda */
        .spinner-border {
            color: #dc3545;
        }

        .alert-sm {
            border-radius: 0.375rem;
            margin-bottom: 0;
        }
    </style>
@endsection
