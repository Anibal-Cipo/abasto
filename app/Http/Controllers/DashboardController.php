<?php

namespace App\Http\Controllers;

use App\Models\Introductor;
use App\Models\Introduccion;
use App\Models\Redespacho;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Si es inspector, redirigir a su dashboard específico
        if ($user->esInspector()) {
            return redirect()->route('inspector.index');
        }

        // Dashboard para admin y administrativos
        return $this->dashboardAdministrativo();
    }

    private function dashboardAdministrativo()
    {
        // Estadísticas generales 
        $stats = [
            'introducciones_hoy' => Introduccion::hoy()->count(),
            'introducciones_mes' => Introduccion::whereMonth('fecha', now()->month)->count(),
            'redespachos_hoy' => Redespacho::whereDate('fecha', today())->count(),
            'introducciones_con_stock' => Introduccion::conStock()->count(),
        ];

        // Últimas introducciones
        $ultimasIntroducciones = Introduccion::with(['introductor', 'usuario'])
            ->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc')
            ->limit(10)
            ->get();

        // Introducciones próximas a vencer (simplificado por ahora)
        $proximasVencer = collect(); // Por ahora vacío hasta implementar correctamente

        // Top introductores del mes
        $topIntroductores = Introductor::withCount(['introducciones' => function ($query) {
            $query->whereMonth('fecha', now()->month);
        }])
            ->having('introducciones_count', '>', 0)
            ->orderBy('introducciones_count', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'stats',
            'ultimasIntroducciones',
            'proximasVencer',
            'topIntroductores'
        ));
    }

    public function statsApi()
    {
        $stats = [
            'introducciones_hoy' => Introduccion::hoy()->count(),
            'introducciones_con_stock' => Introduccion::conStock()->count(),
            'introductores_activos' => Introductor::activos()->count(),
            'redespachos_hoy' => Redespacho::whereDate('fecha', today())->count(),
        ];

        return response()->json($stats);
    }
}
