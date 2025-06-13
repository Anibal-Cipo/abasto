<?php 

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::query();

        // Filtros
        if ($request->filled('categoria')) {
            $query->porCategoria($request->categoria);
        }

        if ($request->filled('tipo_medicion')) {
            $query->where('tipo_medicion', $request->tipo_medicion);
        }

        if ($request->filled('activo')) {
            $query->where('activo', $request->activo);
        }

        if ($request->filled('buscar')) {
            $query->where('nombre', 'like', '%' . $request->buscar . '%');
        }

        $productos = $query->orderBy('categoria')
                          ->orderBy('nombre')
                          ->paginate(20)
                          ->appends($request->query());

        $categorias = Producto::CATEGORIAS;

        return view('productos.index', compact('productos', 'categorias'));
    }

    public function create()
    {
        $categorias = Producto::CATEGORIAS;
        return view('productos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:productos,nombre',
            'categoria' => 'required|string|max:50',
            'tipo_medicion' => 'required|in:CANTIDAD,PESO,MIXTO',
            'unidad_primaria' => 'nullable|string|max:20',
            'unidad_secundaria' => 'required|string|max:20',
            'dias_vencimiento' => 'required|integer|min:1|max:3650',
            'requiere_temperatura' => 'boolean',
            'activo' => 'boolean'
        ]);

        $validated['requiere_temperatura'] = $request->has('requiere_temperatura');
        $validated['activo'] = $request->has('activo');

        // Validar unidad_primaria para productos MIXTO
        if ($validated['tipo_medicion'] === 'MIXTO' && empty($validated['unidad_primaria'])) {
            return back()->withErrors(['unidad_primaria' => 'La unidad primaria es requerida para productos MIXTO'])
                        ->withInput();
        }

        Producto::create($validated);

        return redirect()->route('productos.index')
                        ->with('success', 'Producto creado exitosamente.');
    }

    public function show(Producto $producto)
    {
        // Estadísticas del producto
        $stats = [
            'total_introducciones' => $producto->introduccionProductos()->count(),
            'cantidad_total_introducida' => $producto->introduccionProductos()->sum('cantidad_secundaria'),
            'cantidad_total_redespachada' => $producto->redespachoProductos()->sum('cantidad_secundaria'),
            'mes_actual' => $producto->introduccionProductos()
                                   ->whereHas('introduccion', function($query) {
                                       $query->whereMonth('fecha', now()->month);
                                   })
                                   ->sum('cantidad_secundaria')
        ];

        $stats['stock_disponible'] = $stats['cantidad_total_introducida'] - $stats['cantidad_total_redespachada'];

        return view('productos.show', compact('producto', 'stats'));
    }

    public function edit(Producto $producto)
    {
        $categorias = Producto::CATEGORIAS;
        return view('productos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, Producto $producto)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100', 
                        Rule::unique('productos')->ignore($producto->id)],
            'categoria' => 'required|string|max:50',
            'tipo_medicion' => 'required|in:CANTIDAD,PESO,MIXTO',
            'unidad_primaria' => 'nullable|string|max:20',
            'unidad_secundaria' => 'required|string|max:20',
            'dias_vencimiento' => 'required|integer|min:1|max:3650',
            'requiere_temperatura' => 'boolean',
            'activo' => 'boolean'
        ]);

        $validated['requiere_temperatura'] = $request->has('requiere_temperatura');
        $validated['activo'] = $request->has('activo');

        // Validar unidad_primaria para productos MIXTO
        if ($validated['tipo_medicion'] === 'MIXTO' && empty($validated['unidad_primaria'])) {
            return back()->withErrors(['unidad_primaria' => 'La unidad primaria es requerida para productos MIXTO'])
                        ->withInput();
        }

        $producto->update($validated);

        return redirect()->route('productos.show', $producto)
                        ->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy(Producto $producto)
    {
        // Verificar si tiene movimientos
        if ($producto->introduccionProductos()->exists() || $producto->redespachoProductos()->exists()) {
            return back()->with('error', 'No se puede eliminar: el producto tiene movimientos registrados.');
        }

        $producto->delete();

        return redirect()->route('productos.index')
                        ->with('success', 'Producto eliminado exitosamente.');
    }
}