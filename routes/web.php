<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IntroductorController;
use App\Http\Controllers\IntroduccionController;
use App\Http\Controllers\RedespachoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\InspectorController;
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

    // Dashboard principal (redirige según rol)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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

    // Rutas de solo lectura para inspectores (pueden VER pero no editar)
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
    });

    // Rutas para admin y administrativos (CRUD completo)
    Route::middleware(['role:admin,administrativo'])->group(function () {

        // Introductores - CRUD completo
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

        // Redespachos - CRUD completo (menos show que está arriba)
        Route::get('/redespachos', [RedespachoController::class, 'index'])->name('redespachos.index');
        Route::delete('/redespachos/{redespacho}', [RedespachoController::class, 'destroy'])->name('redespachos.destroy');

        // Crear redespacho desde una introducción específica
        Route::get('/introducciones/{introduccion}/redespachos/create', [RedespachoController::class, 'create'])
            ->name('redespachos.create');
        Route::post('/introducciones/{introduccion}/redespachos', [RedespachoController::class, 'store'])
            ->name('redespachos.store');

        // Productos
        Route::resource('productos', ProductoController::class);

        // Reportes
        Route::prefix('reportes')->name('reportes.')->group(function () {
            Route::get('/', [ReporteController::class, 'index'])->name('index');
            Route::get('/introducciones', [ReporteController::class, 'introducciones'])->name('introducciones');
            Route::get('/redespachos', [ReporteController::class, 'redespachos'])->name('redespachos');
            Route::get('/stock', [ReporteController::class, 'stock'])->name('stock');
        });
    });

    // Rutas solo para administradores
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('usuarios', UserController::class);
    });
});
