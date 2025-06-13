<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    
    // Usuario autenticado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Búsqueda de introductores
    Route::get('/introductores/buscar', [IntroductorController::class, 'buscarApi']);
    
    // Búsqueda por QR
    Route::get('/introducciones/qr/{qrCode}', [IntroduccionController::class, 'buscarPorQr']);
    
    // Listados básicos (para selects)
    Route::get('/introductores', function() {
        return \App\Models\Introductor::activos()
                                     ->orderBy('razon_social')
                                     ->select('id', 'razon_social', 'cuit')
                                     ->get();
    });
    
    Route::get('/productos', function() {
        return \App\Models\Producto::activos()
                                  ->orderBy('categoria')
                                  ->orderBy('nombre')
                                  ->get();
    });
    
    // Stats para dashboard
    Route::get('/dashboard/stats', [DashboardController::class, 'statsApi']);
});