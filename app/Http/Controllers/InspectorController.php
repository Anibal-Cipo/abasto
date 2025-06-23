<?php

namespace App\Http\Controllers;

use App\Models\Introductor;
use App\Models\Introduccion;
use App\Models\Redespacho;
use Illuminate\Http\Request;

class InspectorController extends Controller
{
    public function __construct()
    {
        // Solo inspectores pueden acceder a este controlador
        $this->middleware('role:inspector');
    }

    public function index()
    {
        // Dashboard simple para inspectores
        $stats = [
            'introducciones_hoy' => Introduccion::hoy()->count(),
            'introducciones_con_stock' => Introduccion::conStock()->count(),
            'introductores_activos' => Introductor::activos()->count(),
        ];

        return view('inspector.index', compact('stats'));
    }

    public function buscar()
    {
        return view('inspector.buscar');
    }

    public function buscarResultados(Request $request)
    {
        $request->validate([
            'termino' => 'required|string|min:3|max:50'
        ], [
            'termino.required' => 'Debes ingresar un término de búsqueda.',
            'termino.min' => 'El término debe tener al menos 3 caracteres.',
            'termino.max' => 'El término no puede exceder 50 caracteres.'
        ]);

        $introductores = Introductor::activos()
            ->buscar($request->termino)
            ->with(['introduccionesRecientes' => function ($query) {
                $query->with(['productos.producto', 'redespachos'])
                    ->orderBy('fecha', 'desc')
                    ->orderBy('hora', 'desc')
                    ->limit(5);
            }])
            ->limit(10)
            ->get();

        return view('inspector.resultados', compact('introductores', 'request'));
    }

    public function qrScanner()
    {
        return view('inspector.qr-scanner');
    }

    public function mostrarIntroduccion($id)
    {
        $introduccion = Introduccion::with([
            'introductor',
            'usuario',
            'productos.producto',
            'redespachos.productos.producto'
        ])->findOrFail($id);

        $stockDisponible = $introduccion->stockDisponible();

        return view('inspector.introduccion-show', compact('introduccion', 'stockDisponible'));
    }

    public function mostrarRedespacho($id)
    {
        $redespacho = Redespacho::with([
            'introduccion.introductor',
            'usuario',
            'productos.producto'
        ])->findOrFail($id);

        return view('inspector.redespacho-show', compact('redespacho'));
    }
}
