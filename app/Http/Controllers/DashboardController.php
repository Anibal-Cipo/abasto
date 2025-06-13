<?php

namespace App\Http\Controllers;

use App\Models\Introduccion;
use App\Models\Introductor;
use App\Models\Redespacho;
use App\Models\Producto;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Estadísticas generales
        $stats = [
            'introducciones_hoy' => Introduccion::hoy()->count(),
            'introducciones_mes' => Introduccion::whereMonth('fecha', now()->month)->count(),
            'redespachos_hoy' => Redespacho::hoy()->count(),
            'introductores_activos' => Introductor::activos()->count(),
            'introducciones_con_stock' => Introduccion::conStock()->count(),
        ];

        // Últimas introducciones
        $ultimasIntroducciones = Introduccion::with(['introductor', 'usuario'])
                                           ->orderBy('fecha', 'desc')
                                           ->orderBy('hora', 'desc')
                                           ->limit(10)
                                           ->get();

        // Introducciones por vencer (próximos 3 días)
        $proximasVencer = Introduccion::with(['introductor', 'productos.producto'])
                                    ->whereHas('productos.producto', function($query) {
                                        $query->whereRaw('DATE_ADD(introducciones.fecha, INTERVAL productos.dias_vencimiento DAY) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 DAY)');
                                    })
                                    ->get();

        // Top introductores del mes
        $topIntroductores = Introductor::withCount(['introducciones' => function($query) {
                                      $query->whereMonth('fecha', now()->month);
                                  }])
                                  ->having('introducciones_count', '>', 0)
                                  ->orderBy('introducciones_count', 'desc')
                                  ->limit(5)
                                  ->get();

        return view('dashboard', compact('stats', 'ultimasIntroducciones', 'proximasVencer', 'topIntroductores'));
    }
}