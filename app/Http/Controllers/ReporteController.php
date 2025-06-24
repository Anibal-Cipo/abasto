<?php

namespace App\Http\Controllers;

use App\Models\Introduccion;
use App\Models\Introductor;
use App\Models\Redespacho;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReporteController extends Controller
{
    public function index()
    {
        return redirect()->route('reportes.dashboard');
    }

    public function dashboard(Request $request)
    {
        $periodo = $request->get('periodo', 'mes');

        // Calcular fechas según el período
        $fechas = $this->calcularFechasPeriodo($periodo);

        // Estadísticas principales
        $stats = $this->calcularEstadisticas($fechas);

        // Datos para gráficos
        $datosTendencias = $this->obtenerDatosTendencias($fechas);
        $datosCategorias = $this->obtenerDatosCategorias($fechas);
        $datosIntroductores = $this->obtenerDatosIntroductores($fechas);
        $datosStock = $this->obtenerDatosStock();

        // Productos próximos a vencer
        $proximosVencer = $this->obtenerProximosVencer();

        // Actividad reciente
        $actividadReciente = $this->obtenerActividadReciente();

        return view('reportes.dashboard', compact(
            'stats',
            'datosTendencias',
            'datosCategorias',
            'datosIntroductores',
            'datosStock',
            'proximosVencer',
            'actividadReciente'
        ));
    }

    public function dashboardData(Request $request)
    {
        $periodo = $request->get('periodo', 'mes');
        $fechas = $this->calcularFechasPeriodo($periodo);

        $data = [
            'stats' => $this->calcularEstadisticas($fechas),
            'tendencias' => $this->obtenerDatosTendencias($fechas),
            'categorias' => $this->obtenerDatosCategorias($fechas),
            'introductores' => $this->obtenerDatosIntroductores($fechas),
            'stock' => $this->obtenerDatosStock()
        ];

        return response()->json($data);
    }

    private function calcularFechasPeriodo($periodo)
    {
        $hoy = Carbon::now();

        switch ($periodo) {
            case 'hoy':
                return [
                    'inicio' => $hoy->copy()->startOfDay(),
                    'fin' => $hoy->copy()->endOfDay()
                ];
            case 'semana':
                return [
                    'inicio' => $hoy->copy()->startOfWeek(),
                    'fin' => $hoy->copy()->endOfWeek()
                ];
            case 'mes':
                return [
                    'inicio' => $hoy->copy()->startOfMonth(),
                    'fin' => $hoy->copy()->endOfMonth()
                ];
            case 'trimestre':
                return [
                    'inicio' => $hoy->copy()->startOfQuarter(),
                    'fin' => $hoy->copy()->endOfQuarter()
                ];
            case 'año':
                return [
                    'inicio' => $hoy->copy()->startOfYear(),
                    'fin' => $hoy->copy()->endOfYear()
                ];
            default:
                return [
                    'inicio' => $hoy->copy()->startOfMonth(),
                    'fin' => $hoy->copy()->endOfMonth()
                ];
        }
    }

    private function calcularEstadisticas($fechas)
    {
        // Introducciones en el período
        $introducciones = Introduccion::whereBetween('fecha', [$fechas['inicio'], $fechas['fin']]);
        $totalIntroducciones = $introducciones->count();

        // Total ingresado
        $totalIngresado = DB::table('introducciones')
            ->join('introduccion_productos', 'introducciones.id', '=', 'introduccion_productos.introduccion_id')
            ->whereBetween('introducciones.fecha', [$fechas['inicio'], $fechas['fin']])
            ->sum('introduccion_productos.cantidad_secundaria');

        // Total redespachado
        $totalRedespachado = DB::table('redespachos')
            ->join('redespacho_productos', 'redespachos.id', '=', 'redespacho_productos.redespacho_id')
            ->whereBetween('redespachos.fecha', [$fechas['inicio'], $fechas['fin']])
            ->sum('redespacho_productos.cantidad_secundaria');

        // Consumo de la ciudad (lo que se queda en Cipolletti)
        $consumoCiudad = $totalIngresado - $totalRedespachado;

        // Estadísticas del día
        $hoy = Carbon::today();
        $introduccionesHoy = Introduccion::whereDate('fecha', $hoy)->count();
        $redespachosHoy = Redespacho::whereDate('fecha', $hoy)->count();
        $introductoresActivosHoy = Introduccion::whereDate('fecha', $hoy)
            ->distinct('introductor_id')
            ->count('introductor_id');

        $totalIngresadoHoy = DB::table('introducciones')
            ->join('introduccion_productos', 'introducciones.id', '=', 'introduccion_productos.introduccion_id')
            ->whereDate('introducciones.fecha', $hoy)
            ->sum('introduccion_productos.cantidad_secundaria');

        $totalRedespachadoHoy = DB::table('redespachos')
            ->join('redespacho_productos', 'redespachos.id', '=', 'redespacho_productos.redespacho_id')
            ->whereDate('redespachos.fecha', $hoy)
            ->sum('redespacho_productos.cantidad_secundaria');

        $consumoCiudadHoy = $totalIngresadoHoy - $totalRedespachadoHoy;

        return [
            'total_introducciones' => $totalIntroducciones,
            'total_ingresado' => $totalIngresado,
            'total_redespachado' => $totalRedespachado,
            'consumo_ciudad' => $consumoCiudad,
            'introducciones_hoy' => $introduccionesHoy,
            'redespachos_hoy' => $redespachosHoy,
            'introductores_activos_hoy' => $introductoresActivosHoy,
            'total_ingresado_hoy' => $totalIngresadoHoy,
            'total_redespachado_hoy' => $totalRedespachadoHoy,
            'consumo_ciudad_hoy' => $consumoCiudadHoy
        ];
    }

    private function obtenerDatosTendencias($fechas)
    {
        $inicio = $fechas['inicio'];
        $fin = $fechas['fin'];

        // Determinar el intervalo según el período
        $dias = $inicio->diffInDays($fin);

        if ($dias <= 1) {
            // Por horas
            $intervalo = 'hour';
            $formato = 'H:00';
        } elseif ($dias <= 7) {
            // Por días
            $intervalo = 'day';
            $formato = 'd/m';
        } elseif ($dias <= 31) {
            // Por días
            $intervalo = 'day';
            $formato = 'd/m';
        } else {
            // Por semanas o meses
            $intervalo = 'week';
            $formato = 'W/Y';
        }

        $labels = [];
        $introducciones = [];
        $redespachos = [];
        $consumo = [];

        // Generar períodos
        $current = $inicio->copy();
        while ($current <= $fin) {
            if ($intervalo === 'hour') {
                $labels[] = $current->format($formato);
                $nextPeriod = $current->copy()->addHour();
            } elseif ($intervalo === 'day') {
                $labels[] = $current->format($formato);
                $nextPeriod = $current->copy()->addDay();
            } else {
                $labels[] = 'Sem ' . $current->format('W');
                $nextPeriod = $current->copy()->addWeek();
            }

            // Introducciones en este período
            $intros = DB::table('introducciones')
                ->join('introduccion_productos', 'introducciones.id', '=', 'introduccion_productos.introduccion_id')
                ->whereBetween('introducciones.fecha', [$current, $nextPeriod->copy()->subSecond()])
                ->sum('introduccion_productos.cantidad_secundaria');

            // Redespachos en este período  
            $redes = DB::table('redespachos')
                ->join('redespacho_productos', 'redespachos.id', '=', 'redespacho_productos.redespacho_id')
                ->whereBetween('redespachos.fecha', [$current, $nextPeriod->copy()->subSecond()])
                ->sum('redespacho_productos.cantidad_secundaria');

            $introducciones[] = $intros ?: 0;
            $redespachos[] = $redes ?: 0;
            $consumo[] = ($intros ?: 0) - ($redes ?: 0);

            $current = $nextPeriod;
        }

        return [
            'labels' => $labels,
            'introducciones' => $introducciones,
            'redespachos' => $redespachos,
            'consumo' => $consumo
        ];
    }

    private function obtenerDatosCategorias($fechas)
    {
        $datos = DB::table('introducciones')
            ->join('introduccion_productos', 'introducciones.id', '=', 'introduccion_productos.introduccion_id')
            ->join('productos', 'introduccion_productos.producto_id', '=', 'productos.id')
            ->whereBetween('introducciones.fecha', [$fechas['inicio'], $fechas['fin']])
            ->select(
                'productos.categoria',
                DB::raw('SUM(introduccion_productos.cantidad_secundaria) as total')
            )
            ->groupBy('productos.categoria')
            ->orderBy('total', 'desc')
            ->get();

        return [
            'labels' => $datos->pluck('categoria')->toArray(),
            'valores' => $datos->pluck('total')->map(function ($val) {
                return (float) $val;
            })->toArray()
        ];
    }

    private function obtenerDatosIntroductores($fechas)
    {
        $datos = DB::table('introducciones')
            ->join('introduccion_productos', 'introducciones.id', '=', 'introduccion_productos.introduccion_id')
            ->join('introductores', 'introducciones.introductor_id', '=', 'introductores.id')
            ->whereBetween('introducciones.fecha', [$fechas['inicio'], $fechas['fin']])
            ->select(
                'introductores.razon_social',
                DB::raw('SUM(introduccion_productos.cantidad_secundaria) as total')
            )
            ->groupBy('introductores.id', 'introductores.razon_social')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        return [
            'labels' => $datos->pluck('razon_social')->map(function ($nombre) {
                return strlen($nombre) > 20 ? substr($nombre, 0, 20) . '...' : $nombre;
            })->toArray(),
            'valores' => $datos->pluck('total')->map(function ($val) {
                return (float) $val;
            })->toArray()
        ];
    }

    private function obtenerDatosStock()
    {
        $datos = DB::table('productos')
            ->leftJoin('introduccion_productos', 'productos.id', '=', 'introduccion_productos.producto_id')
            ->leftJoin('redespacho_productos', 'productos.id', '=', 'redespacho_productos.producto_id')
            ->select(
                'productos.categoria',
                DB::raw('COALESCE(SUM(introduccion_productos.cantidad_secundaria), 0) - 
                              COALESCE(SUM(redespacho_productos.cantidad_secundaria), 0) as stock_disponible')
            )
            ->groupBy('productos.categoria')
            ->having('stock_disponible', '>', 0)
            ->orderBy('stock_disponible', 'desc')
            ->get();

        return [
            'labels' => $datos->pluck('categoria')->toArray(),
            'valores' => $datos->pluck('stock_disponible')->map(function ($val) {
                return (float) $val;
            })->toArray()
        ];
    }

    private function obtenerProximosVencer()
    {
        // VERSIÓN SIMPLIFICADA PARA EVITAR ERRORES
        return collect(); // Retorna colección vacía temporalmente
    }

    private function obtenerActividadReciente()
    {
        // VERSIÓN SIMPLIFICADA PARA EVITAR ERRORES
        return collect(); // Retorna colección vacía temporalmente
    }

    // Método para reporte de stock
    public function stock(Request $request)
    {
        $stockPorProducto = $this->calcularStockDetallado();

        return view('reportes.stock', compact('stockPorProducto'));
    }

    private function calcularStockDetallado()
    {
        return DB::table('productos')
            ->leftJoin('introduccion_productos', 'productos.id', '=', 'introduccion_productos.producto_id')
            ->leftJoin('redespacho_productos', 'productos.id', '=', 'redespacho_productos.producto_id')
            ->select(
                'productos.id',
                'productos.nombre',
                'productos.categoria',
                'productos.unidad_secundaria',
                DB::raw('COALESCE(SUM(introduccion_productos.cantidad_secundaria), 0) as total_introducido'),
                DB::raw('COALESCE(SUM(redespacho_productos.cantidad_secundaria), 0) as total_redespachado'),
                DB::raw('COALESCE(SUM(introduccion_productos.cantidad_secundaria), 0) - 
                         COALESCE(SUM(redespacho_productos.cantidad_secundaria), 0) as stock_disponible')
            )
            ->where('productos.activo', true)
            ->groupBy('productos.id', 'productos.nombre', 'productos.categoria', 'productos.unidad_secundaria')
            ->orderBy('productos.categoria')
            ->orderBy('productos.nombre')
            ->get();
    }

    // Método para exportar introducciones
    public function exportarIntroducciones(Request $request)
    {
        // Este método se implementará cuando hagamos la exportación a Excel
        return response()->json(['message' => 'Función de exportación pendiente de implementar']);
    }

    // Método para reporte de consumo de la ciudad
    public function consumoCiudad(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $datos = $this->calcularConsumoPorPeriodo($fechaInicio, $fechaFin);

        return view('reportes.consumo-ciudad', compact('datos', 'fechaInicio', 'fechaFin'));
    }

    private function calcularConsumoPorPeriodo($fechaInicio, $fechaFin)
    {
        return DB::table('productos')
            ->leftJoin('introduccion_productos', function ($join) use ($fechaInicio, $fechaFin) {
                $join->on('productos.id', '=', 'introduccion_productos.producto_id')
                    ->join('introducciones', 'introduccion_productos.introduccion_id', '=', 'introducciones.id')
                    ->whereBetween('introducciones.fecha', [$fechaInicio, $fechaFin]);
            })
            ->leftJoin('redespacho_productos', function ($join) use ($fechaInicio, $fechaFin) {
                $join->on('productos.id', '=', 'redespacho_productos.producto_id')
                    ->join('redespachos', 'redespacho_productos.redespacho_id', '=', 'redespachos.id')
                    ->whereBetween('redespachos.fecha', [$fechaInicio, $fechaFin]);
            })
            ->select(
                'productos.nombre',
                'productos.categoria',
                'productos.unidad_secundaria',
                DB::raw('COALESCE(SUM(introduccion_productos.cantidad_secundaria), 0) as total_introducido'),
                DB::raw('COALESCE(SUM(redespacho_productos.cantidad_secundaria), 0) as total_redespachado'),
                DB::raw('COALESCE(SUM(introduccion_productos.cantidad_secundaria), 0) - 
                         COALESCE(SUM(redespacho_productos.cantidad_secundaria), 0) as consumo_ciudad')
            )
            ->groupBy('productos.id', 'productos.nombre', 'productos.categoria', 'productos.unidad_secundaria')
            ->having('total_introducido', '>', 0)
            ->orderBy('consumo_ciudad', 'desc')
            ->get();
    }
}
