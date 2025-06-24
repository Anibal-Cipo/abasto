<?php

namespace App\Http\Controllers;

use App\Models\Acta;
use App\Models\Persona;
use App\Models\Vehiculo;
use App\Models\VehiculoMarca;
use App\Models\VehiculoModelo;
use App\Models\TipoInfraccion;
use App\Models\ActaTipo;
use App\Models\ActaDocumentacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ActaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware('role:inspector,admin')->except(['vistaPublica']);
    }

    /**
     * Dashboard principal para inspectores
     */
    public function index()
    {
        $usuario = auth()->user();

        // Estadísticas del inspector
        $stats = [
            'actas_hoy' => Acta::where('usuario', $usuario->name)
                ->whereDate('fecha_hora', today())
                ->where('borrado', 'N')
                ->count(),
            'actas_mes' => Acta::where('usuario', $usuario->name)
                ->whereMonth('fecha_hora', now()->month)
                ->whereYear('fecha_hora', now()->year)
                ->where('borrado', 'N')
                ->count(),
            'actas_total' => Acta::where('usuario', $usuario->name)
                ->where('borrado', 'N')
                ->count()
        ];

        // Últimas actas del inspector
        $ultimasActas = Acta::with(['persona', 'vehiculo'])
            ->where('usuario', $usuario->name)
            ->where('borrado', 'N')
            ->orderBy('fecha_hora', 'desc')
            ->limit(10)
            ->get();

        return view('infracciones.index', compact('stats', 'ultimasActas'));
    }

    /**
     * Mostrar formulario para crear nueva acta
     */
    public function create()
    {
        // CORREGIDO: Usar conexión infracciones
        $tiposInfraccion = TipoInfraccion::on('infracciones')
            ->where('cc', 624) // CC de Abasto
            ->orderBy('codigo')
            ->get();

        $observacionesComunes = [
            'Falta de documentación sanitaria',
            'Transporte sin habilitación',
            'Productos sin registro RNPA/RNE',
            'Vehículo sin habilitación para transporte de alimentos',
            'Mercadería en mal estado',
            'Falta de cadena de frío',
            'Documentación vencida',
            'Incumplimiento de normas de higiene',
            'Comercialización en vía pública sin autorización',
            'Falta de rotulado obligatorio'
        ];

        return view('infracciones.create', compact('tiposInfraccion', 'observacionesComunes'));
    }

    /**
     * Buscar persona por DNI - MEJORADO
     */
    public function buscarPersona(Request $request)
    {
        $request->validate([
            'dni' => 'required|numeric|digits_between:7,8'
        ]);

        try {
            // CORREGIDO: Usar conexión infracciones
            $persona = Persona::on('infracciones')
                ->where('dni', $request->dni)
                ->first();

            if ($persona) {
                return response()->json([
                    'encontrada' => true,
                    'persona' => [
                        'dni' => $persona->dni,
                        'nombre' => $persona->nombre,
                        'apellido' => $persona->apellido,
                        'nacionalidad' => $persona->nacionalidad,
                        'fecha_nacimiento' => $persona->fecha_nacimiento ? $persona->fecha_nacimiento->format('Y-m-d') : null,
                        'calle' => $persona->calle,
                        'altura' => $persona->altura,
                        'localidad_desc' => $persona->localidad_desc,
                        'telefono' => $persona->telefono,
                        'email' => $persona->email,
                    ]
                ]);
            }

            return response()->json([
                'encontrada' => false,
                'dni' => $request->dni,
                'mensaje' => 'Persona no encontrada en la base de datos'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al buscar persona: ' . $e->getMessage());
            return response()->json([
                'encontrada' => false,
                'error' => 'Error al buscar en la base de datos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Buscar vehículo por dominio - CORREGIDO
     */
    public function buscarVehiculo(Request $request)
    {
        $request->validate([
            'dominio' => 'required|string|min:6|max:10'
        ]);

        try {
            // Limpiar y formatear el dominio
            $dominio = strtoupper(trim($request->dominio));
            
            // CORREGIDO: Usar conexión infracciones
            $vehiculo = Vehiculo::on('infracciones')
                ->with(['marca', 'modelo'])
                ->where('dominio', $dominio)
                ->first();

            \Log::info('Buscando vehículo con dominio: ' . $dominio);
            \Log::info('Vehículo encontrado: ' . ($vehiculo ? 'SÍ' : 'NO'));

            if ($vehiculo) {
                $response = [
                    'encontrado' => true,
                    'vehiculo' => [
                        'id' => $vehiculo->id,
                        'dominio' => $vehiculo->dominio,
                        'color' => $vehiculo->color,
                        'tipo_vehiculo' => $vehiculo->tipo_vehiculo,
                        'id_marca' => $vehiculo->id_marca,
                        'id_modelo' => $vehiculo->id_modelo,
                        'marca' => $vehiculo->marca ? $vehiculo->marca->marca : null,
                        'modelo' => $vehiculo->modelo ? $vehiculo->modelo->modelo : null,
                        'motor' => $vehiculo->motor,
                        'chasis' => $vehiculo->chasis,
                    ]
                ];
                
                \Log::info('Respuesta vehículo: ' . json_encode($response));
                return response()->json($response);
            }

            return response()->json([
                'encontrado' => false,
                'dominio' => $dominio,
                'mensaje' => 'Vehículo no encontrado en la base de datos'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al buscar vehículo: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'encontrado' => false,
                'error' => 'Error al buscar en la base de datos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener modelos por marca
     */
    public function obtenerModelos(Request $request)
    {
        $request->validate([
            'marca_id' => 'required|exists:infracciones.vehiculo_marca,id'
        ]);

        try {
            // CORREGIDO: Usar conexión infracciones
            $modelos = VehiculoModelo::on('infracciones')
                ->where('id_marca', $request->marca_id)
                ->orderBy('modelo')
                ->get(['id', 'modelo']);

            return response()->json($modelos);

        } catch (\Exception $e) {
            \Log::error('Error al cargar modelos: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al cargar modelos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener marcas de vehículos
     */
    public function obtenerMarcas()
    {
        try {
            // CORREGIDO: Usar conexión infracciones
            $marcas = VehiculoMarca::on('infracciones')
                ->orderBy('marca')
                ->get(['id', 'marca']);
            
            return response()->json($marcas);

        } catch (\Exception $e) {
            \Log::error('Error al cargar marcas: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al cargar marcas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Guardar nueva acta - ACTUALIZADO PARA NUEVOS CAMPOS
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Datos de la persona
            'dni' => 'required|numeric|digits_between:7,8',
            'apellido' => 'required|string|max:50',
            'nombre' => 'required|string|max:50',
            'nacionalidad' => 'nullable|string|max:50',
            'fecha_nacimiento' => 'nullable|date|before:today',
            'calle' => 'nullable|string|max:100',
            'altura' => 'nullable|numeric|min:1|max:99999',
            'localidad_desc' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:200',

            // Datos del vehículo
            'dominio' => 'required|string|max:10',
            'color' => 'nullable|string|max:50',
            'tipo_vehiculo' => 'nullable|string|max:100',
            'marca_id' => 'nullable|exists:vehiculo_marca,id',
            'modelo_id' => 'nullable|exists:vehiculo_modelo,id',
            'nueva_marca' => 'nullable|string|max:100',
            'nuevo_modelo' => 'nullable|string|max:100',

            // Ubicación ACTUALIZADA
            'ubicacion_calle' => 'required|string|max:255',
            'ubicacion_altura' => 'nullable|string|max:10',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
            'ubicacion' => 'required|string', // Campo completo generado automáticamente

            // Datos del acta
            'tipo_acta' => 'required|in:A,B,C,T,TC,S',
            'numero_licencia' => 'nullable|numeric',
            'lugar_emision' => 'nullable|string|max:50',
            'motivo' => 'required|string|min:10',
            'observaciones' => 'nullable|string',
            'destino_acta' => 'required|in:Aceptada,Rechazada,Depositada en vehículo,Imposible Entregar',
            'monto' => 'nullable|numeric|min:0',
            'sam' => 'nullable|numeric|min:0',

            // Tipos de infracción
            'tipos_infraccion' => 'required|array|min:1',
            'tipos_infraccion.*' => 'exists:tipo_infraccion,id',

            // Archivos
            'imagenes.*' => 'nullable|image|max:5120' // 5MB máximo
        ]);

        try {
            DB::connection('infracciones')->beginTransaction();

            // 1. Crear o actualizar persona
            $persona = Persona::updateOrCreate(
                ['dni' => $validated['dni']],
                [
                    'apellido' => $validated['apellido'],
                    'nombre' => $validated['nombre'],
                    'nacionalidad' => $validated['nacionalidad'] ?? 'Argentina',
                    'fecha_nacimiento' => $validated['fecha_nacimiento'],
                    'calle' => $validated['calle'],
                    'altura' => $validated['altura'],
                    'localidad_desc' => $validated['localidad_desc'],
                    'telefono' => $validated['telefono'],
                    'email' => $validated['email'],
                ]
            );

            // 2. Manejar marcas y modelos
            $marcaId = $validated['marca_id'];
            if ($validated['nueva_marca'] && !$marcaId) {
                $marca = VehiculoMarca::firstOrCreate([
                    'marca' => trim($validated['nueva_marca'])
                ]);
                $marcaId = $marca->id;
            }

            $modeloId = $validated['modelo_id'];
            if ($validated['nuevo_modelo'] && $marcaId && !$modeloId) {
                $modelo = VehiculoModelo::firstOrCreate([
                    'modelo' => trim($validated['nuevo_modelo']),
                    'id_marca' => $marcaId
                ]);
                $modeloId = $modelo->id;
            }

            // 3. Crear o actualizar vehículo
            $vehiculo = Vehiculo::updateOrCreate(
                ['dominio' => strtoupper($validated['dominio'])],
                [
                    'color' => $validated['color'],
                    'tipo_vehiculo' => $validated['tipo_vehiculo'],
                    'id_marca' => $marcaId,
                    'id_modelo' => $modeloId,
                ]
            );

            // 4. Generar número de acta automáticamente
            $numeroActa = $this->generarNumeroActa();

            // 5. Crear acta
            $acta = Acta::create([
                'numero_acta' => $numeroActa,
                'tipo_acta' => $validated['tipo_acta'],
                'id_persona' => $persona->id,
                'id_objeto' => $vehiculo->id,
                'numero_licencia' => $validated['numero_licencia'],
                'lugar_emision' => $validated['lugar_emision'],
                'es_verbal' => $request->has('es_verbal') ? 'S' : 'N',
                'retiene_licencia' => $request->has('retiene_licencia') ? 'S' : 'N',
                'retiene_vehiculo' => $request->has('retiene_vehiculo') ? 'S' : 'N',
                'motivo' => $validated['motivo'],
                'observaciones' => $validated['observaciones'],
                'destino_acta' => $validated['destino_acta'],
                'monto' => $validated['monto'] ?? 0,
                'sam' => $validated['sam'],
                'profesional' => $request->has('profesional') ? 'S' : 'N',
                'ubicacion' => $validated['ubicacion'],
                'longitud' => $validated['longitud'],
                'latitud' => $validated['latitud'],
                'usuario' => auth()->user()->name,
                'cc' => 624, // CC 624 = Dpto. Abasto
                'fecha_hora' => now(),
                'hora_inicio' => now()->format('H:i:s'),
                'borrado' => 'N',
                'fecha_alta' => now(),
                'estado' => null
            ]);

            // 6. Agregar tipos de infracción (crear modelo ActaTipo si no existe)
            foreach ($validated['tipos_infraccion'] as $tipoId) {
                try {
                    ActaTipo::create([
                        'id_acta' => $acta->id,
                        'id_tipo' => $tipoId,
                        'fecha_alta' => now()
                    ]);
                } catch (\Exception $e) {
                    // Si no existe la tabla acta_tipo, continuar sin error
                    \Log::warning('No se pudo crear ActaTipo: ' . $e->getMessage());
                }
            }

            // 7. Procesar imágenes si las hay
            if ($request->hasFile('imagenes')) {
                $this->procesarImagenes($request->file('imagenes'), $acta);
            }

            DB::connection('infracciones')->commit();

            return redirect()->route('infracciones.show', $acta)
                ->with('success', 'Acta #' . $numeroActa . ' creada exitosamente.');

        } catch (\Exception $e) {
            DB::connection('infracciones')->rollback();
            \Log::error('Error al crear acta: ' . $e->getMessage());

            return back()->withInput()
                ->with('error', 'Error al crear el acta: ' . $e->getMessage());
        }
    }

    /**
     * Generar número de acta secuencial
     */
    private function generarNumeroActa()
    {
        $ultimaActa = Acta::orderBy('id', 'desc')->first();
        return $ultimaActa ? $ultimaActa->id + 1 : 1;
    }

    /**
     * Procesar imágenes subidas
     */
    private function procesarImagenes($imagenes, $acta)
    {
        try {
            foreach ($imagenes as $index => $imagen) {
                if ($imagen->isValid()) {
                    $nombreArchivo = 'acta_' . $acta->id . '_' . ($index + 1) . '.' . $imagen->getClientOriginalExtension();
                    $rutaArchivo = $imagen->storeAs('actas/' . $acta->id, $nombreArchivo, 'public');

                    // Si existe modelo ActaDocumentacion, crear registro
                    try {
                        ActaDocumentacion::create([
                            'id_acta' => $acta->id,
                            'dominio' => $acta->vehiculo->dominio ?? '',
                            'path' => $rutaArchivo,
                            'descripcion' => 'Imagen ' . ($index + 1) . ' del acta',
                            'borrado' => 'N'
                        ]);
                    } catch (\Exception $e) {
                        \Log::warning('No se pudo crear ActaDocumentacion: ' . $e->getMessage());
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error al procesar imágenes: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar acta específica
     */
    public function show(Acta $acta)
    {
        $acta->load([
            'persona',
            'vehiculo.marca',
            'vehiculo.modelo',
        ]);

        // Verificar que el inspector solo pueda ver sus actas
        if (auth()->user()->hasRole('inspector') && $acta->usuario !== auth()->user()->name) {
            abort(403, 'No tienes permisos para ver esta acta.');
        }

        return view('infracciones.show', compact('acta'));
    }

    /**
     * Listar actas del inspector
     */
    public function misActas(Request $request)
    {
        $query = Acta::with(['persona', 'vehiculo'])
            ->where('usuario', auth()->user()->name)
            ->where('borrado', 'N');

        // Filtros
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_hora', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_hora', '<=', $request->fecha_hasta);
        }

        if ($request->filled('tipo_acta')) {
            $query->where('tipo_acta', $request->tipo_acta);
        }

        if ($request->filled('dni')) {
            $query->whereHas('persona', function ($q) use ($request) {
                $q->where('dni', $request->dni);
            });
        }

        if ($request->filled('dominio')) {
            $query->whereHas('vehiculo', function ($q) use ($request) {
                $q->where('dominio', 'like', '%' . strtoupper($request->dominio) . '%');
            });
        }

        $actas = $query->orderBy('fecha_hora', 'desc')
            ->paginate(15)
            ->appends($request->query());

        return view('infracciones.mis-actas', compact('actas'));
    }

    /**
     * Vista pública del acta (mediante token)
     */
    public function vistaPublica($token)
    {
        try {
            $actaId = decrypt($token);
            $acta = Acta::with([
                'persona',
                'vehiculo.marca',
                'vehiculo.modelo',
            ])->findOrFail($actaId);

            return view('infracciones.publica', compact('acta'));
        } catch (\Exception $e) {
            abort(404, 'Acta no encontrada o token inválido.');
        }
    }

    /**
     * Imprimir acta en formato térmico
     */
    public function imprimirTermica(Acta $acta)
    {
        // Verificar permisos
        if (auth()->user()->hasRole('inspector') && $acta->usuario !== auth()->user()->name) {
            abort(403, 'No tienes permisos para imprimir esta acta.');
        }

        $acta->load(['persona', 'vehiculo.marca', 'vehiculo.modelo']);
        
        return view('infracciones.imprimir-termica', compact('acta'));
    }
}