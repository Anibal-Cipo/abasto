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
        $tiposInfraccion = TipoInfraccion::where('cc', 624) // CC de Abasto
            ->orderBy('descripcion')
            ->get();

        $observacionesComunes = [
            'Falta de documentación sanitaria',
            'Transporte sin habilitación',
            'Productos sin registro RNPA/RNE',
            'Vehículo sin habilitación para transporte de alimentos',
            'Mercadería en mal estado',
            'Falta de cadena de frío',
            'Documentación vencida'
        ];

        return view('infracciones.create', compact('tiposInfraccion', 'observacionesComunes'));
    }

    /**
     * Buscar persona por DNI
     */
    public function buscarPersona(Request $request)
    {
        $request->validate([
            'dni' => 'required|numeric'
        ]);

        $persona = Persona::where('dni', $request->dni)->first();

        if ($persona) {
            return response()->json([
                'encontrada' => true,
                'persona' => $persona
            ]);
        }

        return response()->json([
            'encontrada' => false,
            'dni' => $request->dni
        ]);
    }

    /**
     * Buscar vehículo por dominio
     */
    public function buscarVehiculo(Request $request)
    {
        $request->validate([
            'dominio' => 'required|string'
        ]);

        $vehiculo = Vehiculo::with(['marca', 'modelo'])
            ->where('dominio', strtoupper($request->dominio))
            ->first();

        if ($vehiculo) {
            return response()->json([
                'encontrado' => true,
                'vehiculo' => $vehiculo
            ]);
        }

        return response()->json([
            'encontrado' => false,
            'dominio' => strtoupper($request->dominio)
        ]);
    }

    /**
     * Obtener modelos por marca
     */
    public function obtenerModelos(Request $request)
    {
        $request->validate([
            'marca_id' => 'required|exists:vehiculo_marca,id'
        ]);

        $modelos = VehiculoModelo::where('id_marca', $request->marca_id)
            ->orderBy('modelo')
            ->get();

        return response()->json($modelos);
    }

    /**
     * Obtener marcas de vehículos
     */
    public function obtenerMarcas()
    {
        $marcas = VehiculoMarca::orderBy('marca')->get();
        return response()->json($marcas);
    }

    /**
     * Guardar nueva acta
     */
    public function store(Request $request)
    {
        $request->validate([
            // Datos de la persona
            'dni' => 'required|numeric',
            'apellido' => 'required|string|max:50',
            'nombre' => 'required|string|max:50',
            'nacionalidad' => 'nullable|string|max:50',
            'fecha_nacimiento' => 'nullable|date',
            'calle' => 'nullable|string|max:100',
            'altura' => 'nullable|numeric',
            'localidad_desc' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:200',

            // Datos del vehículo
            'dominio' => 'required|string|max:50',
            'color' => 'nullable|string|max:50',
            'tipo_vehiculo' => 'nullable|string|max:100',
            'marca_id' => 'nullable|exists:vehiculo_marca,id',
            'modelo_id' => 'nullable|exists:vehiculo_modelo,id',
            'nueva_marca' => 'nullable|string|max:100',
            'nuevo_modelo' => 'nullable|string|max:100',

            // Datos del acta
            'tipo_acta' => 'required|in:A,B,C,T,TC,S',
            'numero_licencia' => 'nullable|numeric',
            'lugar_emision' => 'nullable|string|max:50',
            'motivo' => 'required|string',
            'observaciones' => 'nullable|string',
            'ubicacion' => 'required|string',
            'longitud' => 'nullable|numeric',
            'latitud' => 'nullable|numeric',
            'destino_acta' => 'required|in:Aceptada,Rechazada,Depositada en vehículo,Imposible Entregar',
            'monto' => 'nullable|numeric',
            'sam' => 'nullable|numeric',

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
                ['dni' => $request->dni],
                [
                    'apellido' => $request->apellido,
                    'nombre' => $request->nombre,
                    'nacionalidad' => $request->nacionalidad,
                    'fecha_nacimiento' => $request->fecha_nacimiento,
                    'calle' => $request->calle,
                    'altura' => $request->altura,
                    'localidad_desc' => $request->localidad_desc,
                    'telefono' => $request->telefono,
                    'email' => $request->email,
                ]
            );

            // 2. Crear marca si es nueva
            $marcaId = $request->marca_id;
            if ($request->nueva_marca && !$marcaId) {
                $marca = VehiculoMarca::firstOrCreate([
                    'marca' => $request->nueva_marca
                ]);
                $marcaId = $marca->id;
            }

            // 3. Crear modelo si es nuevo
            $modeloId = $request->modelo_id;
            if ($request->nuevo_modelo && $marcaId && !$modeloId) {
                $modelo = VehiculoModelo::firstOrCreate([
                    'modelo' => $request->nuevo_modelo,
                    'id_marca' => $marcaId
                ]);
                $modeloId = $modelo->id;
            }

            // 4. Crear o actualizar vehículo
            $vehiculo = Vehiculo::updateOrCreate(
                ['dominio' => strtoupper($request->dominio)],
                [
                    'color' => $request->color,
                    'tipo_vehiculo' => $request->tipo_vehiculo,
                    'id_marca' => $marcaId,
                    'id_modelo' => $modeloId,
                ]
            );

            // 5. Crear acta
            $acta = Acta::create([
                'numero_acta' => 0, // Como especificaste
                'tipo_acta' => $request->tipo_acta,
                'id_persona' => $persona->id,
                'id_objeto' => $vehiculo->id,
                'numero_licencia' => $request->numero_licencia,
                'lugar_emision' => $request->lugar_emision,
                'es_verbal' => $request->has('es_verbal') ? 'S' : 'N',
                'retiene_licencia' => $request->has('retiene_licencia') ? 'S' : 'N',
                'retiene_vehiculo' => $request->has('retiene_vehiculo') ? 'S' : 'N',
                'motivo' => $request->motivo,
                'observaciones' => $request->observaciones,
                'destino_acta' => $request->destino_acta,
                'monto' => $request->monto ?? 0,
                'sam' => $request->sam,
                'profesional' => $request->has('profesional') ? 'S' : 'N',
                'ubicacion' => $request->ubicacion,
                'longitud' => $request->longitud,
                'latitud' => $request->latitud,
                'usuario' => auth()->user()->name,
                'cc' => 624, // CC 624 = Dpto. Abasto como especificaste
                'fecha_hora' => now(),
                'hora_inicio' => now()->format('H:i:s'),
                'borrado' => 'N',
                'fecha_alta' => now(),
                'estado' => null // En blanco como especificaste
            ]);

            // 6. Agregar tipos de infracción (por ahora comentado hasta crear el modelo ActaTipo)
            /*
            foreach ($request->tipos_infraccion as $tipoId) {
                ActaTipo::create([
                    'id_acta' => $acta->id,
                    'id_tipo' => $tipoId,
                    'fecha_alta' => now()
                ]);
            }
            */

            DB::connection('infracciones')->commit();

            return redirect()->route('infracciones.show', $acta)
                ->with('success', 'Acta creada exitosamente.');
        } catch (\Exception $e) {
            DB::connection('infracciones')->rollback();

            return back()->withInput()
                ->with('error', 'Error al crear el acta: ' . $e->getMessage());
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
            // 'tipos.tipoInfraccion', // Comentado hasta crear ActaTipo
            // 'documentacion' // Comentado hasta crear ActaDocumentacion
        ]);

        // Verificar que el inspector solo pueda ver sus actas
        if (auth()->user()->esInspector() && $acta->usuario !== auth()->user()->name) {
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
                // 'tipos.tipoInfraccion',
                // 'documentacion'
            ])->findOrFail($actaId);

            return view('infracciones.publica', compact('acta'));
        } catch (\Exception $e) {
            abort(404, 'Acta no encontrada o token inválido.');
        }
    }
}
