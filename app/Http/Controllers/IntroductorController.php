<?php

namespace App\Http\Controllers;

use App\Models\Introductor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class IntroductorController extends Controller
{
    public function index(Request $request)
    {
        $query = Introductor::query();

        // Filtros
        if ($request->filled('buscar')) {
            $query->buscar($request->buscar);
        }

        if ($request->filled('activo')) {
            $query->where('activo', $request->activo);
        }

        $introductores = $query->orderBy('razon_social')
            ->paginate(15)
            ->appends($request->query());

        return view('introductores.index', compact('introductores'));
    }

    public function create()
    {
        return view('introductores.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'razon_social' => 'required|string|max:255',
            'cuit' => 'required|string|min:11|max:13|unique:introductores,cuit',
            'direccion' => 'required|string',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'habilitacion_municipal' => 'nullable|string|max:255',
        ]);

        // Limpiar CUIT (quitar guiones y espacios)
        $validated['cuit'] = preg_replace('/[^0-9]/', '', $validated['cuit']);
        
        // Validar que tenga exactamente 11 dígitos después de limpiar
        if (strlen($validated['cuit']) !== 11) {
            return back()->withErrors(['cuit' => 'El CUIT debe tener exactamente 11 dígitos.'])->withInput();
        }

        // Manejar checkbox activo
        $validated['activo'] = $request->has('activo') ? 1 : 0;

        $introductor = Introductor::create($validated);

        return redirect()->route('introductores.show', $introductor->id)
            ->with('success', 'Introductor creado exitosamente.');
    }

    public function show($id)
    {
        $introductor = Introductor::findOrFail($id);
        
        // Cargar relaciones solo si existen
        $introductor->load(['introducciones' => function($query) {
            $query->with(['productos', 'redespachos'])
                  ->orderBy('fecha', 'desc')
                  ->orderBy('hora', 'desc')
                  ->limit(5);
        }]);

        // Estadísticas del introductor
        $stats = [
            'total_introducciones' => $introductor->introducciones()->count(),
            'introducciones_mes' => $introductor->introducciones()
                ->whereMonth('fecha', now()->month)
                ->count(),
            'con_stock' => $introductor->introducciones()->count(), // Simplificado por ahora
            'ultima_introduccion' => $introductor->introducciones()
                ->latest('fecha')
                ->latest('hora')
                ->first()
        ];

        return view('introductores.show', compact('introductor', 'stats'));
    }

    public function edit($id)
    {
        $introductor = Introductor::findOrFail($id);
        return view('introductores.edit', compact('introductor'));
    }

    public function update(Request $request, $id)
    {
        $introductor = Introductor::findOrFail($id);
        
        $validated = $request->validate([
            'razon_social' => 'required|string|max:255',
            'cuit' => [
                'required',
                'string',
                'min:11',
                'max:13',
                Rule::unique('introductores')->ignore($id)
            ],
            'direccion' => 'required|string',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'habilitacion_municipal' => 'nullable|string|max:255',
        ]);

        // Limpiar CUIT (quitar guiones y espacios)
        $validated['cuit'] = preg_replace('/[^0-9]/', '', $validated['cuit']);

        // Validar que tenga exactamente 11 dígitos después de limpiar
        if (strlen($validated['cuit']) !== 11) {
            return back()->withErrors(['cuit' => 'El CUIT debe tener exactamente 11 dígitos.'])->withInput();
        }
        
        // Manejar checkbox activo
        $validated['activo'] = $request->has('activo') ? 1 : 0;

        $introductor->update($validated);

        return redirect()->route('introductores.show', $introductor->id)
            ->with('success', 'Introductor actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $introductor = Introductor::findOrFail($id);
        
        // Verificar si tiene introducciones
        if ($introductor->introducciones()->exists()) {
            return back()->with('error', 'No se puede eliminar: el introductor tiene introducciones registradas.');
        }

        $introductor->delete();

        return redirect()->route('introductores.index')
            ->with('success', 'Introductor eliminado exitosamente.');
    }

    // API para búsqueda de inspectores
    public function buscarApi(Request $request)
    {
        $termino = $request->get('q');

        $introductores = Introductor::where('activo', true)
            ->where(function($query) use ($termino) {
                $query->where('razon_social', 'like', "%{$termino}%")
                      ->orWhere('cuit', 'like', "%{$termino}%");
            })
            ->with(['introducciones' => function($query) {
                $query->with(['productos', 'redespachos'])
                      ->orderBy('fecha', 'desc')
                      ->limit(5);
            }])
            ->limit(10)
            ->get();

        return response()->json($introductores);
    }
}