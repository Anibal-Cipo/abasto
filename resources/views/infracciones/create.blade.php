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
                        <!-- Cambiar esta línea en create.blade.php -->
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
                                                    value="{{ old('dni') }}" required>
                                                <button type="button" class="btn btn-outline-primary" id="buscarPersona">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
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
                                                    id="dominio" value="{{ old('dominio') }}" required>
                                                <button type="button" class="btn btn-outline-primary"
                                                    id="buscarVehiculo">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
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
                                        <label class="form-label">Ubicación *</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="ubicacion" id="ubicacion"
                                                value="{{ old('ubicacion') }}" required>
                                            <button type="button" class="btn btn-outline-success" id="obtenerUbicacion">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </button>
                                        </div>
                                        @error('ubicacion')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <input type="hidden" name="longitud" id="longitud" value="{{ old('longitud') }}">
                                    <input type="hidden" name="latitud" id="latitud" value="{{ old('latitud') }}">

                                    <div class="mb-3">
                                        <label class="form-label">Tipos de Infracción *</label>
                                        <div class="row">
                                            @foreach ($tiposInfraccion as $tipo)
                                                <div class="col-12 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="tipos_infraccion[]" value="{{ $tipo->id }}"
                                                            id="tipo_{{ $tipo->id }}"
                                                            {{ in_array($tipo->id, old('tipos_infraccion', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="tipo_{{ $tipo->id }}">
                                                            <strong>{{ $tipo->codigo }}</strong> -
                                                            {{ $tipo->descripcion }}
                                                            @if ($tipo->sam > 0)
                                                                <span
                                                                    class="badge bg-warning text-dark">${{ number_format($tipo->sam, 2) }}</span>
                                                            @endif
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
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

            // Buscar persona por DNI
            document.getElementById('buscarPersona').addEventListener('click', function() {
                const dni = document.getElementById('dni').value;
                if (!dni) {
                    alert('Ingrese un DNI');
                    return;
                }

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
                            document.getElementById('apellido').value = persona.apellido || '';
                            document.getElementById('nombre').value = persona.nombre || '';
                            document.getElementById('nacionalidad').value = persona.nacionalidad || '';
                            document.getElementById('fecha_nacimiento').value = persona
                                .fecha_nacimiento || '';
                            document.getElementById('calle').value = persona.calle || '';
                            document.getElementById('altura').value = persona.altura || '';
                            document.getElementById('localidad_desc').value = persona.localidad_desc ||
                                '';
                            document.getElementById('telefono').value = persona.telefono || '';

                            alert('Persona encontrada y datos cargados');
                        } else {
                            alert('Persona no encontrada. Complete los datos manualmente.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al buscar persona');
                    });
            });

            // Buscar vehículo por dominio
            document.getElementById('buscarVehiculo').addEventListener('click', function() {
                const dominio = document.getElementById('dominio').value;
                if (!dominio) {
                    alert('Ingrese un dominio');
                    return;
                }

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
                        if (data.encontrado) {
                            const vehiculo = data.vehiculo;
                            document.getElementById('color').value = vehiculo.color || '';
                            document.getElementById('tipo_vehiculo').value = vehiculo.tipo_vehiculo ||
                                '';

                            if (vehiculo.marca) {
                                document.getElementById('marca_id').value = vehiculo.id_marca;
                                cargarModelos(vehiculo.id_marca, vehiculo.id_modelo);
                            }

                            alert('Vehículo encontrado y datos cargados');
                        } else {
                            alert('Vehículo no encontrado. Complete los datos manualmente.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al buscar vehículo');
                    });
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

            // Obtener ubicación
            document.getElementById('obtenerUbicacion').addEventListener('click', function() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        document.getElementById('latitud').value = position.coords.latitude;
                        document.getElementById('longitud').value = position.coords.longitude;

                        // Obtener dirección aproximada usando geocoding reverso
                        // (Aquí podrías usar una API como Google Maps o OpenStreetMap)
                        document.getElementById('ubicacion').value =
                            `Lat: ${position.coords.latitude.toFixed(6)}, Lng: ${position.coords.longitude.toFixed(6)}`;

                        alert('Ubicación obtenida correctamente');
                    }, function(error) {
                        alert('Error al obtener ubicación: ' + error.message);
                    });
                } else {
                    alert('Geolocalización no soportada por este navegador');
                }
            });

            // Convertir dominio a mayúsculas
            document.getElementById('dominio').addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });
        });

        function cargarMarcas() {
            fetch(`{{ route('infracciones.obtener-marcas') }}`)
                .then(response => response.json())
                .then(marcas => {
                    const select = document.getElementById('marca_id');
                    select.innerHTML = '<option value="">Seleccionar marca...</option>';
                    marcas.forEach(marca => {
                        select.innerHTML += `<option value="${marca.id}">${marca.marca}</option>`;
                    });
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

        function agregarObservacion(checkbox) {
            const observaciones = document.getElementById('observaciones');
            if (checkbox.checked) {
                const texto = observaciones.value;
                const nuevaObs = checkbox.value;
                if (texto) {
                    observaciones.value = texto + '. ' + nuevaObs;
                } else {
                    observaciones.value = nuevaObs;
                }
            } else {
                // Remover observación si se desmarca
                const texto = observaciones.value;
                const nuevaObs = checkbox.value;
                observaciones.value = texto.replace(nuevaObs, '').replace(/\.\s+\./g, '.').replace(/^\.\s*/, '').trim();
            }
        }
    </script>
@endsection
