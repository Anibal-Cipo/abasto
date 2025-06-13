<?php

// 1. routes/web.php - CORREGIDO PARA LARAVEL UI
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IntroductorController;
use App\Http\Controllers\IntroduccionController;
use App\Http\Controllers\RedespachoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\InspectorController; // AGREGAR ESTA LÍNEA
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

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Introductores
    Route::resource('introductores', IntroductorController::class);

    // API para búsqueda de introductores (para inspectores)
    Route::get('/api/introductores/buscar', [IntroductorController::class, 'buscarApi'])
        ->name('introductores.buscar.api');

    // Introducciones
    Route::resource('introducciones', IntroduccionController::class);

    // Búsqueda por QR (para inspectores)
    Route::get('/introducciones/qr/{qrCode}', [IntroduccionController::class, 'buscarPorQr'])
        ->name('introducciones.qr');

    // Redespachos
    Route::resource('redespachos', RedespachoController::class)->except(['create', 'edit', 'update']);

    // Crear redespacho desde una introducción específica
    Route::get('/introducciones/{introduccion}/redespachos/create', [RedespachoController::class, 'create'])
        ->name('redespachos.create');

    Route::post('/introducciones/{introduccion}/redespachos', [RedespachoController::class, 'store'])
        ->name('redespachos.store');

    // Interface especial para inspectores
    Route::prefix('inspector')->name('inspector.')->group(function () {
        Route::get('/', [InspectorController::class, 'index'])->name('index');
        Route::get('/buscar', [InspectorController::class, 'buscar'])->name('buscar');
        Route::post('/buscar', [InspectorController::class, 'buscarResultados'])->name('buscar.resultados');
        Route::get('/qr-scanner', [InspectorController::class, 'qrScanner'])->name('qr.scanner');
    });

    // Rutas solo para administradores y administrativos
    Route::middleware(['role:admin,administrativo'])->group(function () {

        // Productos
        Route::resource('productos', ProductoController::class);

        // Reportes
        Route::prefix('reportes')->name('reportes.')->group(function () {
            Route::get('/', [ReporteController::class, 'index'])->name('index');
            Route::get('/introducciones', [ReporteController::class, 'introducciones'])->name('introducciones');
            Route::get('/redespachos', [ReporteController::class, 'redespachos'])->name('redespachos');
            Route::get('/stock', [ReporteController::class, 'stock'])->name('stock');
            Route::get('/vencimientos', [ReporteController::class, 'vencimientos'])->name('vencimientos');

            // Exportar reportes
            Route::get('/introducciones/export', [ReporteController::class, 'exportarIntroducciones'])
                ->name('introducciones.export');
            Route::get('/redespachos/export', [ReporteController::class, 'exportarRedespachos'])
                ->name('redespachos.export');
        });

        // Usuarios (solo para admin)
        Route::middleware(['role:admin'])->group(function () {
            Route::resource('usuarios', UserController::class);
        });
    });
    // Rutas para PDF del remito
    Route::get('/introducciones/{id}/imprimir', [IntroduccionController::class, 'imprimirRemito'])
        ->name('introducciones.imprimir');

    Route::get('/introducciones/{id}/descargar', [IntroduccionController::class, 'descargarRemito'])
        ->name('introducciones.descargar');

    // Rutas de redespachos
    Route::get('/redespachos', [RedespachoController::class, 'index'])->name('redespachos.index');
    Route::get('/introducciones/{introduccion}/redespachos/create', [RedespachoController::class, 'create'])->name('redespachos.create');
    Route::post('/introducciones/{introduccion}/redespachos', [RedespachoController::class, 'store'])->name('redespachos.store');
    Route::get('/redespachos/{redespacho}', [RedespachoController::class, 'show'])->name('redespachos.show');
    Route::delete('/redespachos/{redespacho}', [RedespachoController::class, 'destroy'])->name('redespachos.destroy');

    // Rutas para impresión de redespachos
    Route::get('/redespachos/{id}/imprimir', [RedespachoController::class, 'imprimirRedespacho'])
        ->name('redespachos.imprimir')
        ->middleware('auth');

    Route::get('/redespachos/{id}/descargar', [RedespachoController::class, 'descargarRedespacho'])
        ->name('redespachos.descargar')
        ->middleware('auth');
});
