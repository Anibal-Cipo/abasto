<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IntroductorController;
use App\Http\Controllers\IntroduccionController;
use App\Http\Controllers\RedespachoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\InspectorController;
use App\Http\Controllers\ActaController; // NUEVA LÍNEA AGREGADA
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Ruta principal - redirigir según autenticación
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Laravel UI Auth Routes
Auth::routes(['verify' => true]);

// Ruta home redirige a dashboard
Route::get('/home', function () {
    return redirect()->route('dashboard');
})->name('home')->middleware(['auth', 'verified']);

// Rutas protegidas por autenticación
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard principal (accesible por todos los roles)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // API Routes accesibles por todos los roles autenticados
    Route::prefix('api')->group(function () {
        // Búsqueda de introductores
        Route::get('/introductores/buscar', [IntroductorController::class, 'buscarApi'])
            ->name('introductores.buscar.api');

        // Búsqueda por QR
        Route::get('/introducciones/qr/{qrCode}', [IntroduccionController::class, 'buscarPorQr'])
            ->name('introducciones.qr');

        // Stats para dashboard
        Route::get('/dashboard/stats', [DashboardController::class, 'statsApi'])
            ->name('dashboard.stats.api');
    });

    // Rutas para inspectores únicamente
    Route::middleware(['role:inspector'])->prefix('inspector')->name('inspector.')->group(function () {
        Route::get('/', [InspectorController::class, 'index'])->name('index');
        Route::get('/buscar', [InspectorController::class, 'buscar'])->name('buscar');
        Route::post('/buscar', [InspectorController::class, 'buscarResultados'])->name('buscar.resultados');
        Route::get('/qr-scanner', [InspectorController::class, 'qrScanner'])->name('qr.scanner');

        // Vistas específicas para inspector (móvil-optimizadas)
        Route::get('/introduccion/{id}', [InspectorController::class, 'mostrarIntroduccion'])->name('introduccion.show');
        Route::get('/redespacho/{id}', [InspectorController::class, 'mostrarRedespacho'])->name('redespacho.show');
    });

    // ================================
    // RUTAS DE INFRACCIONES - NUEVO
    // ================================

    // Rutas de Infracciones para Inspectores
    Route::middleware(['role:inspector,admin'])->prefix('infracciones')->name('infracciones.')->group(function () {

        // Dashboard principal de infracciones
        Route::get('/', [ActaController::class, 'index'])->name('index');

        // Crear nueva acta (inspectores y admins)
        Route::middleware(['role:inspector,admin'])->group(function () {
            Route::get('/create', [ActaController::class, 'create'])->name('create');
            Route::post('/create', [ActaController::class, 'store'])->name('store');
        });

        // Ver acta específica
        Route::get('/{acta}', [ActaController::class, 'show'])->name('show');

        // Mis actas (lista de actas del inspector)
        Route::get('/mis-actas/listado', [ActaController::class, 'misActas'])->name('mis-actas');

        // Búsqueda y utilidades AJAX
        Route::post('/buscar-persona', [ActaController::class, 'buscarPersona'])->name('buscar-persona');
        Route::post('/buscar-vehiculo', [ActaController::class, 'buscarVehiculo'])->name('buscar-vehiculo');
        Route::get('/obtener-marcas', [ActaController::class, 'obtenerMarcas'])->name('obtener-marcas');
        Route::post('/obtener-modelos', [ActaController::class, 'obtenerModelos'])->name('obtener-modelos');

        // Impresión térmica (solo para inspectores que crearon el acta o admins)
        Route::get('/{acta}/imprimir-termica', [ActaController::class, 'imprimirTermica'])
            ->name('imprimir-termica');
    });

    // Rutas para admin y administrativos (CRUD completo)
    Route::middleware(['role:admin,administrativo'])->group(function () {

        // Introductores - CRUD completo (rutas explícitas)
        Route::get('/introductores', [IntroductorController::class, 'index'])->name('introductores.index');
        Route::get('/introductores/create', [IntroductorController::class, 'create'])->name('introductores.create');
        Route::post('/introductores', [IntroductorController::class, 'store'])->name('introductores.store');
        Route::get('/introductores/{introductor}/edit', [IntroductorController::class, 'edit'])->name('introductores.edit');
        Route::put('/introductores/{introductor}', [IntroductorController::class, 'update'])->name('introductores.update');
        Route::delete('/introductores/{introductor}', [IntroductorController::class, 'destroy'])->name('introductores.destroy');

        // Introducciones - CRUD completo
        Route::get('/introducciones', [IntroduccionController::class, 'index'])->name('introducciones.index');
        Route::get('/introducciones/create', [IntroduccionController::class, 'create'])->name('introducciones.create');
        Route::post('/introducciones', [IntroduccionController::class, 'store'])->name('introducciones.store');
        Route::get('/introducciones/{introduccion}/edit', [IntroduccionController::class, 'edit'])->name('introducciones.edit');
        Route::put('/introducciones/{introduccion}', [IntroduccionController::class, 'update'])->name('introducciones.update');
        Route::delete('/introducciones/{introduccion}', [IntroduccionController::class, 'destroy'])->name('introducciones.destroy');

        // Redespachos - CRUD completo
        Route::get('/redespachos', [RedespachoController::class, 'index'])->name('redespachos.index');
        Route::delete('/redespachos/{redespacho}', [RedespachoController::class, 'destroy'])->name('redespachos.destroy');

        // Crear redespacho desde una introducción específica
        Route::get('/introducciones/{introduccion}/redespachos/create', [RedespachoController::class, 'create'])
            ->name('redespachos.create');
        Route::post('/introducciones/{introduccion}/redespachos', [RedespachoController::class, 'store'])
            ->name('redespachos.store');

        // Productos - CRUD completo
        Route::resource('productos', ProductoController::class);

        // Reportes
        Route::prefix('reportes')->name('reportes.')->group(function () {
            Route::get('/', [ReporteController::class, 'index'])->name('index');
            Route::get('/dashboard', [ReporteController::class, 'dashboard'])->name('dashboard');
            Route::get('/dashboard-data', [ReporteController::class, 'dashboardData'])->name('dashboard.data');
            Route::get('/stock', [ReporteController::class, 'stock'])->name('stock');
            Route::get('/consumo-ciudad', [ReporteController::class, 'consumoCiudad'])->name('consumo-ciudad');
            Route::get('/introducciones/export', [ReporteController::class, 'exportarIntroducciones'])->name('introducciones.export');
        });

        // ================================
        // ADMINISTRACIÓN DE INFRACCIONES - NUEVO
        // ================================

        // Panel de administración de infracciones para admins
        Route::prefix('admin/infracciones')->name('admin.infracciones.')->group(function () {
            // Listar todas las actas (no solo las del inspector)
            Route::get('/actas', [ActaController::class, 'adminIndex'])->name('actas.index');

            // Reportes de infracciones
            Route::get('/reportes', [ActaController::class, 'reportes'])->name('reportes');
            Route::get('/estadisticas', [ActaController::class, 'estadisticas'])->name('estadisticas');

            // Gestión de tipos de infracción
            Route::get('/tipos-infraccion', [ActaController::class, 'tiposInfraccion'])->name('tipos.index');
            Route::post('/tipos-infraccion', [ActaController::class, 'storeTipoInfraccion'])->name('tipos.store');
            Route::put('/tipos-infraccion/{tipo}', [ActaController::class, 'updateTipoInfraccion'])->name('tipos.update');
        });
    });

    // Rutas de solo lectura - Accesibles por todos los roles (incluyendo inspectores)
    Route::middleware(['role:admin,administrativo,inspector'])->group(function () {
        // Introductores - solo lectura para inspectores
        Route::get('/introductores/{introductor}', [IntroductorController::class, 'show'])->name('introductores.show');

        // Introducciones - solo lectura para inspectores  
        Route::get('/introducciones/{introduccion}', [IntroduccionController::class, 'show'])->name('introducciones.show');

        // Redespachos - solo lectura para inspectores
        Route::get('/redespachos/{redespacho}', [RedespachoController::class, 'show'])->name('redespachos.show');

        // Rutas de impresión y descarga (solo lectura)
        Route::get('/introducciones/{id}/imprimir', [IntroduccionController::class, 'imprimirRemito'])
            ->name('introducciones.imprimir');
        Route::get('/introducciones/{id}/descargar', [IntroduccionController::class, 'descargarRemito'])
            ->name('introducciones.descargar');
        Route::get('/redespachos/{id}/imprimir', [RedespachoController::class, 'imprimirRedespacho'])
            ->name('redespachos.imprimir');
        Route::get('/redespachos/{id}/descargar', [RedespachoController::class, 'descargarRedespacho'])
            ->name('redespachos.descargar');
    });

    // Rutas solo para administradores
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('usuarios', UserController::class);
    });
});

// ================================
// RUTAS PÚBLICAS DE INFRACCIONES - NUEVO
// ================================

// Ruta pública para ver actas (sin autenticación, mediante token encriptado)
Route::get('/acta/{token}', [ActaController::class, 'vistaPublica'])->name('actas.publica');

// API pública para validar actas (opcional, para futuro)
Route::get('/api/acta/{token}/validar', [ActaController::class, 'validarActaPublica'])->name('actas.validar');
