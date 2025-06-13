<?php
namespace App\Http\Controllers;

use App\Models\Introductor;
use App\Models\Introduccion;
use Illuminate\Http\Request;

class InspectorController extends Controller
{
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
            'termino' => 'required|string|min:3'
        ]);

        $introductores = Introductor::activos()
                                   ->buscar($request->termino)
                                   ->with(['introduccionesRecientes' => function($query) {
                                       $query->with(['productos.producto', 'redespachos'])
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
}