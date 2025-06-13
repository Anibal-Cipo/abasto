<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->role($request->role);
        }

        if ($request->filled('activo')) {
            $query->where('activo', $request->activo);
        }

        if ($request->filled('buscar')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->buscar . '%')
                  ->orWhere('email', 'like', '%' . $request->buscar . '%');
            });
        }

        $usuarios = $query->orderBy('name')
                         ->paginate(15)
                         ->appends($request->query());

        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $roles = [
            User::ROLE_ADMIN => 'Administrador',
            User::ROLE_ADMINISTRATIVO => 'Administrativo',
            User::ROLE_INSPECTOR => 'Inspector'
        ];

        return view('usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,administrativo,inspector',
            'password' => 'required|string|min:8|confirmed',
            'activo' => 'boolean'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['activo'] = $request->has('activo');
        $validated['email_verified_at'] = now();

        User::create($validated);

        return redirect()->route('usuarios.index')
                        ->with('success', 'Usuario creado exitosamente.');
    }

    public function show(User $usuario)
    {
        // Estadísticas del usuario
        $stats = [
            'total_introducciones' => $usuario->introducciones()->count(),
            'total_redespachos' => $usuario->redespachos()->count(),
            'introducciones_mes' => $usuario->introducciones()->whereMonth('fecha', now()->month)->count(),
            'redespachos_mes' => $usuario->redespachos()->whereMonth('fecha', now()->month)->count(),
        ];

        return view('usuarios.show', compact('usuario', 'stats'));
    }

    public function edit(User $usuario)
    {
        $roles = [
            User::ROLE_ADMIN => 'Administrador',
            User::ROLE_ADMINISTRATIVO => 'Administrativo',
            User::ROLE_INSPECTOR => 'Inspector'
        ];

        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, User $usuario)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($usuario->id)],
            'role' => 'required|in:admin,administrativo,inspector',
            'password' => 'nullable|string|min:8|confirmed',
            'activo' => 'boolean'
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['activo'] = $request->has('activo');

        $usuario->update($validated);

        return redirect()->route('usuarios.show', $usuario)
                        ->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy(User $usuario)
    {
        // No permitir eliminar el último admin
        if ($usuario->esAdmin() && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'No se puede eliminar el último administrador del sistema.');
        }

        // No permitir auto-eliminación
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $usuario->delete();

        return redirect()->route('usuarios.index')
                        ->with('success', 'Usuario eliminado exitosamente.');
    }
}