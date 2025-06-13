<?php

namespace App\Http\Controllers;

use App\Models\Introduccion;
use App\Models\Introductor;
use App\Models\Producto;
use App\Models\IntroduccionArchivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use TCPDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;



class IntroduccionController extends Controller
{
    public function index(Request $request)
    {
        $query = Introduccion::with(['introductor', 'usuario', 'productos.producto']);

        // Filtros
        if ($request->filled('introductor_id')) {
            $query->where('introductor_id', $request->introductor_id);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha', '<=', $request->fecha_hasta);
        }

        if ($request->filled('con_stock')) {
            $query->conStock();
        }

        $introducciones = $query->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc')
            ->paginate(20)
            ->appends($request->query());

        // Para el filtro de introductores
        $introductores = Introductor::activos()
            ->orderBy('razon_social')
            ->pluck('razon_social', 'id');

        return view('introducciones.index', compact('introducciones', 'introductores'));
    }

    public function create()
    {
        $introductores = Introductor::activos()->introductores()->orderBy('razon_social')->get();
        $receptores = Introductor::activos()->receptores()->orderBy('razon_social')->get();
        $productos = Producto::activos()->orderBy('categoria')->orderBy('nombre')->get();

        // Sugerir número de remito
        $numeroRemitoSugerido = Introduccion::generarNumeroRemito();

        return view('introducciones.create', compact('introductores', 'receptores', 'productos', 'numeroRemitoSugerido'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'introductor_id' => 'required|exists:introductores,id',
            'numero_remito' => 'required|string|max:20|unique:introducciones,numero_remito',
            'remito_papel' => 'boolean',
            'envia' => 'nullable|string|max:255',                    // NUEVO
            'procedencia' => 'nullable|string|max:255',              // NUEVO
            'vigente' => 'boolean',                                  // NUEVO
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'vehiculo' => 'nullable|string|max:255',
            'dominio' => 'nullable|string|max:10',
            'habilitacion_vehiculo' => 'nullable|string|max:255',
            'receptor_id' => 'nullable|exists:introductores,id', // CAMBIO: de 'receptores' a 'receptor_id'
            'temperatura' => 'nullable|numeric|between:-50,50',
            'observaciones' => 'nullable|string',
            'precintos_origen' => 'nullable|string|max:255',
            'reprecintado' => 'nullable|string|max:255',
            'ganaderia_numero' => 'nullable|string|max:100',
            'pt_numero' => 'nullable|string|max:50',
            'ptr_numero' => 'nullable|string|max:50',
            'productos' => 'required|array|min:1',
            'productos.*.producto_id' => 'required|exists:productos,id',
            'productos.*.cantidad_primaria' => 'nullable|numeric|min:0',
            'productos.*.cantidad_secundaria' => 'required|numeric|min:0.01',
            'productos.*.observaciones' => 'nullable|string',
            'archivos.*' => 'nullable|file|mimes:jpeg,png,pdf|max:5120',
            'certificado_sanitario' => 'nullable|file|mimes:jpeg,png,pdf|max:5120',  // NUEVO
        ]);

        DB::transaction(function () use ($validated, $request) {
            if (!$request->has('remito_papel')) {
                $validated['remito_papel'] = false;
                $validated['numero_remito_papel'] = null;
            } else {
                $validated['remito_papel'] = true;
            }

            // CAMBIO: Convertir receptor_id a nombre del receptor
            $receptorNombre = null;
            if (isset($validated['receptor_id']) && $validated['receptor_id']) {
                $receptor = Introductor::find($validated['receptor_id']);
                $receptorNombre = $receptor ? $receptor->razon_social : null;
            }

            // Crear introducción
            $introduccion = Introduccion::create([
                'introductor_id' => $validated['introductor_id'],
                'user_id' => auth()->id(),
                'numero_remito' => $validated['numero_remito'],
                'remito_papel' => $validated['remito_papel'],
                'numero_remito_papel' => $validated['numero_remito_papel'] ?? null,
                'envia' => $validated['envia'],                          // NUEVO
                'procedencia' => $validated['procedencia'],              // NUEVO
                'vigente' => $validated['vigente'] ?? true,
                'fecha' => $validated['fecha'],
                'hora' => $validated['hora'],
                'vehiculo' => $validated['vehiculo'],
                'dominio' => $validated['dominio'],
                'habilitacion_vehiculo' => $validated['habilitacion_vehiculo'],
                'receptores' => $receptorNombre, // CAMBIO: Guardar el nombre del receptor
                'temperatura' => $validated['temperatura'],
                'precintos_origen' => $validated['precintos_origen'],
                'reprecintado' => $validated['reprecintado'],
                'ganaderia_numero' => $validated['ganaderia_numero'],
                'observaciones' => $validated['observaciones'],
                'pt_numero' => $validated['pt_numero'],
                'ptr_numero' => $validated['ptr_numero'],
            ]);

            // Crear productos
            foreach ($validated['productos'] as $productoData) {
                $introduccion->productos()->create($productoData);
            }

            // Generar QR
            $introduccion->generarQrCode();

            // Subir archivos
            $this->procesarArchivos($request, $introduccion);
        });

        return redirect()->route('introducciones.index')
            ->with('success', 'Introducción registrada exitosamente.');
    }

    public function show($id)
    {
        $introduccion = Introduccion::with([
            'introductor',
            'usuario',
            'productos.producto',
            'archivos',
            'redespachos.productos.producto'
        ])->findOrFail($id);

        $stockDisponible = $introduccion->stockDisponible();

        return view('introducciones.show', compact('introduccion', 'stockDisponible'));
    }

    public function edit($id)
    {
        $introduccion = Introduccion::with(['productos.producto', 'archivos', 'introductor'])
            ->findOrFail($id);

        if ($introduccion->redespachos()->exists()) {
            return back()->with('error', 'No se puede editar: la introducción ya tiene redespachos asociados.');
        }

        $introductores = Introductor::activos()->introductores()->orderBy('razon_social')->get();
        $receptores = Introductor::activos()->receptores()->orderBy('razon_social')->get();
        $productos = Producto::activos()->orderBy('categoria')->orderBy('nombre')->get();

        return view('introducciones.edit', compact('introduccion', 'introductores', 'receptores', 'productos'));
    }

    public function update(Request $request, $id)
    {
        $introduccion = Introduccion::findOrFail($id);

        if ($introduccion->redespachos()->exists()) {
            return back()->with('error', 'No se puede modificar: la introducción ya tiene redespachos asociados.');
        }

        $validated = $request->validate([
            'introductor_id' => 'required|exists:introductores,id',
            'numero_remito' => 'required|string|max:20|unique:introducciones,numero_remito,' . $id,
            'envia' => 'nullable|string|max:255',                    // NUEVO
            'procedencia' => 'nullable|string|max:255',              // NUEVO
            'vigente' => 'boolean',
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'vehiculo' => 'nullable|string|max:255',
            'dominio' => 'nullable|string|max:10',
            'habilitacion_vehiculo' => 'nullable|string|max:255',
            'receptor_id' => 'nullable|exists:introductores,id',
            'temperatura' => 'nullable|numeric|between:-50,50',
            'observaciones' => 'nullable|string',
            'pt_numero' => 'nullable|string|max:50',
            'ptr_numero' => 'nullable|string|max:50',
            'productos' => 'required|array|min:1',
            'productos.*.producto_id' => 'required|exists:productos,id',
            'productos.*.cantidad_primaria' => 'nullable|numeric|min:0',
            'productos.*.cantidad_secundaria' => 'required|numeric|min:0.01',
            'productos.*.observaciones' => 'nullable|string',
            'archivos.*' => 'nullable|file|mimes:jpeg,png,pdf|max:5120',
            'certificado_sanitario' => 'nullable|file|mimes:jpeg,png,pdf|max:5120',
        ]);

        DB::transaction(function () use ($validated, $request, $introduccion) {
            // Convertir receptor_id a nombre del receptor
            $receptorNombre = null;
            if (isset($validated['receptor_id']) && $validated['receptor_id']) {
                $receptor = Introductor::find($validated['receptor_id']);
                $receptorNombre = $receptor ? $receptor->razon_social : null;
            }
            // Si no se selecciona receptor, mantener el valor actual si no había receptor antes
            // O establecer como null si se deselecciona
            if (!isset($validated['receptor_id']) || !$validated['receptor_id']) {
                $receptorNombre = null; // Explícitamente null si no se selecciona
            }

            // Actualizar introducción
            $introduccion->update([
                'introductor_id' => $validated['introductor_id'],
                'numero_remito' => $validated['numero_remito'],
                'envia' => $validated['envia'],                          // NUEVO
                'procedencia' => $validated['procedencia'],              // NUEVO
                'vigente' => $validated['vigente'] ?? $introduccion->vigente, // NUEVO
                'fecha' => $validated['fecha'],
                'hora' => $validated['hora'],
                'vehiculo' => $validated['vehiculo'],
                'dominio' => $validated['dominio'],
                'habilitacion_vehiculo' => $validated['habilitacion_vehiculo'],
                'receptores' => $receptorNombre,
                'temperatura' => $validated['temperatura'],
                'observaciones' => $validated['observaciones'],
                'pt_numero' => $validated['pt_numero'],
                'ptr_numero' => $validated['ptr_numero'],
            ]);

            // Eliminar productos existentes y crear nuevos
            $introduccion->productos()->delete();
            foreach ($validated['productos'] as $productoData) {
                $introduccion->productos()->create($productoData);
            }

            // Procesar archivos nuevos
            $this->procesarArchivos($request, $introduccion);
        });

        return redirect()->route('introducciones.show', $introduccion)
            ->with('success', 'Introducción actualizada exitosamente.');
    }

    public function destroy(Introduccion $introduccion)
    {
        if ($introduccion->redespachos()->exists()) {
            return back()->with('error', 'No se puede eliminar: la introducción tiene redespachos asociados.');
        }

        // Eliminar archivos del storage
        foreach ($introduccion->archivos as $archivo) {
            Storage::delete($archivo->ruta_archivo);
        }

        $introduccion->delete();

        return redirect()->route('introducciones.index')
            ->with('success', 'Introducción eliminada exitosamente.');
    }

    // Métodos auxiliares
    private function procesarArchivos(Request $request, Introduccion $introduccion)
    {
        $tiposArchivo = ['remito_imagen' => 'REMITO_IMAGEN', 'pt' => 'PT', 'ptr' => 'PTR', 'certificado_sanitario' => 'CERTIFICADO_SANITARIO'];

        foreach ($tiposArchivo as $inputName => $tipoArchivo) {
            if ($request->hasFile($inputName)) {
                $archivo = $request->file($inputName);
                $nombreArchivo = Str::uuid() . '.' . $archivo->getClientOriginalExtension();
                $rutaArchivo = $archivo->storeAs('introducciones/' . $introduccion->id, $nombreArchivo, 'public');

                IntroduccionArchivo::create([
                    'introduccion_id' => $introduccion->id,
                    'tipo_archivo' => $tipoArchivo,
                    'nombre_original' => $archivo->getClientOriginalName(),
                    'nombre_archivo' => $nombreArchivo,
                    'ruta_archivo' => $rutaArchivo,
                    'mime_type' => $archivo->getMimeType(),
                    'tamaño_archivo' => $archivo->getSize(),
                ]);
            }
        }
    }

    // Para inspectores - búsqueda por QR
    public function buscarPorQr($qrCode)
    {
        $introduccion = Introduccion::where('qr_code', $qrCode)
            ->with([
                'introductor',
                'productos.producto',
                'redespachos.productos.producto'
            ])
            ->first();

        if (!$introduccion) {
            return response()->json(['error' => 'Introducción no encontrada'], 404);
        }

        $stockDisponible = $introduccion->stockDisponible();

        return response()->json([
            'introduccion' => $introduccion,
            'stock_disponible' => $stockDisponible
        ]);
    }

    // ******************pdf del remito ***************************//////

    public function imprimirRemito($id)
    {
        $introduccion = Introduccion::with([
            'introductor',
            'usuario',
            'productos.producto'
        ])->findOrFail($id);

        // Crear instancia de TCPDF
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Configurar documento
        $pdf->SetCreator('Sistema de Gestión de Abasto');
        $pdf->SetAuthor($introduccion->usuario->name ?? 'Sistema');
        $pdf->SetTitle('Remito ' . $introduccion->numero_remito);
        $pdf->SetSubject('Remito de Introducción');

        // Configurar margenes
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);

        // Desactivar header y footer automáticos
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Agregar página
        $pdf->AddPage();

        // Generar QR
        $qrCode = $this->generarQrParaTCPDF($introduccion->qr_code);

        // Generar contenido HTML
        $html = $this->generarHTMLRemito($introduccion, $qrCode);

        // Escribir HTML
        $pdf->writeHTML($html, true, false, true, false, '');

        // Salida
        return response($pdf->Output('remito-' . $introduccion->numero_remito . '.pdf', 'I'))
            ->header('Content-Type', 'application/pdf');
    }

    public function descargarRemito($id)
    {
        $introduccion = Introduccion::with([
            'introductor',
            'usuario',
            'productos.producto'
        ])->findOrFail($id);

        // Crear instancia de TCPDF
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Configurar documento
        $pdf->SetCreator('Sistema de Gestión de Abasto');
        $pdf->SetAuthor($introduccion->usuario->name ?? 'Sistema');
        $pdf->SetTitle('Remito ' . $introduccion->numero_remito);

        // Configurar margenes
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);

        // Desactivar header y footer automáticos
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Agregar página
        $pdf->AddPage();

        // Generar QR
        $qrCode = $this->generarQrParaTCPDF($introduccion->qr_code);

        // Generar contenido HTML
        $html = $this->generarHTMLRemito($introduccion, $qrCode);

        // Escribir HTML
        $pdf->writeHTML($html, true, false, true, false, '');

        // Salida para descarga
        return response($pdf->Output('remito-' . $introduccion->numero_remito . '.pdf', 'D'))
            ->header('Content-Type', 'application/pdf');
    }


    private function generarQrParaTCPDF($qrCode)
    {
        try {
            // Usar API externa que ya sabemos que funciona
            $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=120x120&format=png&data=' . urlencode($qrCode);
            $qrImageData = file_get_contents($qrUrl);

            if ($qrImageData !== false) {
                $base64 = base64_encode($qrImageData);
                return '<img src="data:image/png;base64,' . $base64 . '" style="width: 90px; height: 90px; border: 1px solid #ddd;">';
            }
        } catch (\Exception $e) {
            \Log::error('Error generando QR: ' . $e->getMessage());
        }

        // Fallback
        return '<div style="width: 90px; height: 90px; border: 2px solid #333; text-align: center; line-height: 18px; font-size: 8px; padding: 5px; background-color: #f0f0f0;">
                    <strong>QR CODE</strong><br>
                    <small style="word-break: break-all;">' . substr($qrCode, 0, 25) . '</small><br>
                    <small>Escanear para info</small>
                </div>';
    }



    private function generarHTMLRemito($introduccion, $qrCode)
    {
        $html = '
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; margin: 0; padding: 0; }
        
        .header-municipal {
            width: 100%;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .logo-section {
            width: 15%;
            text-align: center;
            vertical-align: middle;
            padding: 3px;
        }
        
        .municipio-info {
            width: 50%;
            text-align: center;
            vertical-align: middle;
            padding: 3px;
            line-height: 1.1;
        }
        
        .numero-remito {
            width: 35%;
            text-align: center;
            vertical-align: middle;
            padding: 5px;
            border: 2px solid #333;
            line-height: 1.2;
        }
        
        .municipio-nombre {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 1px;
            line-height: 1.1;
        }
        
        .municipio-provincia {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 1px;
            line-height: 1.1;
        }
        
        .departamento {
            font-size: 10px;
            margin-top: 1px;
            margin-bottom: 1px;
            line-height: 1.1;
        }
        
        .direccion {
            font-size: 9px;
            color: #666;
            margin-top: 1px;
            line-height: 1.1;
        }
        
        .remito-numero {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 3px;
            line-height: 1.1;
        }
        
        .remito-fecha {
            font-size: 12px;
            margin-bottom: 1px;
            line-height: 1.1;
        }
        
        .remito-hora {
            font-size: 12px;
            line-height: 1.1;
        }
        
        .titulo-documento {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 15px 0;
            padding: 8px;
            background-color: #f5f5f5;
            border: 1px solid #333;
        }
        
        .section-title { 
            background-color: #f5f5f5; 
            padding: 4px; 
            font-weight: bold; 
            border: 1px solid #333; 
            margin: 8px 0 4px 0; 
            text-align: center; 
            font-size: 10px; 
        }
        
        .info-table { 
            width: 100%; 
            margin-bottom: 8px; 
            border-collapse: collapse; 
        }
        
        .info-table td { 
            padding: 2px 4px; 
            border-bottom: 1px solid #ccc; 
            font-size: 9px; 
        }
        
        .label { 
            font-weight: bold; 
            width: 70px; 
            background-color: #f9f9f9; 
        }
        
        .productos-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 4px; 
        }
        
        .productos-table th, .productos-table td { 
            border: 1px solid #333; 
            padding: 3px; 
            text-align: left; 
            font-size: 9px; 
        }
        
        .productos-table th { 
            background-color: #e9e9e9; 
            font-weight: bold; 
            text-align: center; 
        }
        
        .qr-container { 
            text-align: center; 
            border: 1px solid #333; 
            padding: 8px; 
            margin-left: 8px; 
            background-color: #fafafa; 
        }
        
        .footer { 
            border-top: 1px solid #333; 
            padding-top: 4px; 
            font-size: 8px; 
            text-align: center; 
            color: #666; 
            margin-top: 12px; 
        }
        
        .logo-placeholder {
            width: 60px;
            height: 60px;
            border: 1px solid #ccc;
            background-color: #f0f0f0;
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            font-size: 8px;
            color: #666;
        }
    </style>

    <!-- Header Municipal -->
    <div class="header-municipal">
        <table class="header-table">
            <tr>
                <td class="logo-section">
                    <!-- Logo Municipal -->
                    <img src="' . public_path('images/logo-municipal.png') . '" style=" height: 60px; object-fit: contain;" alt="Logo Municipal">
                </td>
               <td class="municipio-info">
                    <div class="municipio-nombre">MUNICIPALIDAD DE CIPOLLETTI</div>
                    <div class="municipio-provincia">RÍO NEGRO</div>
                    <div class="departamento">DEPARTAMENTO DE ABASTO E INTRODUCCIÓN</div>
                    <div class="direccion">Secretaría de Fiscalización</div>
                </td>
                <td class="numero-remito">
                    <div style="font-size: 12px; font-weight: bold; ">REMITO DE INSPECCIÓN</div>
                    <div class="remito-numero">N° ' . $introduccion->numero_remito . '</div>
                    <div class="remito-fecha">Fecha: ' . $introduccion->fecha->format('d/m/Y') . '</div>
                    <div class="remito-hora">Hora: ' . substr($introduccion->hora, 0, 5) . '</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Título del documento -->
    <div class="titulo-documento">
        REMITO DE INSPECCIÓN
    </div>

    <!-- Contenido principal con tabla para layout -->
    <table style="width: 100%;">
        <tr>
            <td style="width: 70%; vertical-align: top;">
                <!-- Datos del Remito -->
                <table class="info-table">
                    <tr>
                        <td class="label">Vehículo:</td>
                        <td>' . ($introduccion->vehiculo ?? '-') . '</td>
                        <td class="label">Dominio:</td>
                        <td>' . ($introduccion->dominio ?? '-') . '</td>
                    </tr>
                    <tr>
                        <td class="label">Habilitación:</td>
                        <td>' . ($introduccion->habilitacion_vehiculo ?? '-') . '</td>
                        <td class="label">Temperatura:</td>
                        <td>' . ($introduccion->temperatura ? $introduccion->temperatura . '°C' : '-') . '</td>
                    </tr>
                    <tr>
                        <td class="label">Envía:</td>
                        <td>' . ($introduccion->envia ?? '-') . '</td>
                        <td class="label">Procedencia:</td>
                        <td>' . ($introduccion->procedencia ?? '-') . '</td>
                    </tr>
                    <tr>
                        <td class="label">Vigente:</td>
                        <td>' . ($introduccion->vigente ? 'SÍ' : 'NO') . '</td>
                        <td class="label"></td>
                        <td></td>
                    </tr>';

        if ($introduccion->precintos_origen || $introduccion->reprecintado) {
            $html .= '
                    <tr>
                        <td class="label">Precintos Origen:</td>
                        <td>' . ($introduccion->precintos_origen ?? '-') . '</td>
                        <td class="label">Reprecintado:</td>
                        <td>' . ($introduccion->reprecintado ?? '-') . '</td>
                    </tr>';
        }

        $html .= '
                </table>

                <!-- Introductor -->
                <div class="section-title">INTRODUCTOR</div>
                <table class="info-table">
                    <tr>
                        <td class="label">Razón Social:</td>
                        <td colspan="3">' . $introduccion->introductor->razon_social . '</td>
                    </tr>
                    <tr>
                        <td class="label">CUIT:</td>
                        <td>' . $introduccion->introductor->cuit_formateado . '</td>
                        <td class="label">Ganadería N°:</td>
                        <td>' . ($introduccion->ganaderia_numero ?? '-') . '</td>
                    </tr>
                </table>';

        if ($introduccion->receptores) {
            // Siempre mostrar la sección receptor
            $html .= '
                <!-- Receptor -->
                <div class="section-title">RECEPTOR</div>
                <table class="info-table">
                    <tr>
                        <td class="label">Receptor:</td>
                        <td colspan="3">' . ($introduccion->receptores ?: 'Sin receptor asignado') . '</td>
                    </tr>
                </table>';
        }

        $html .= '
                <!-- Productos -->
                <div class="section-title">PRODUCTOS</div>
                <table class="productos-table">
                    <thead>
                        <tr>
                            <th style="width: 50%">Producto</th>
                            <th style="width: 15%">Cant. 1</th>
                            <th style="width: 15%">Cant. 2</th>
                            <th style="width: 20%">Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>';

        foreach ($introduccion->productos as $producto) {
            $html .= '
                        <tr>
                            <td>' . $producto->producto->nombre . '</td>
                            <td style="text-align: center;">' .
                ($producto->cantidad_primaria ? number_format($producto->cantidad_primaria, 2) : '-') .
                '</td>
                            <td style="text-align: center;">' .
                number_format($producto->cantidad_secundaria, 2) .
                '</td>
                            <td>' . ($producto->observaciones ?? '-') . '</td>
                        </tr>';
        }

        $html .= '
                    </tbody>
                </table>
            </td>
            <td style="width: 30%; vertical-align: top;">
                <!-- QR Code -->
                <div class="qr-container">
                    <div style="font-weight: bold; margin-bottom: 10px;">CÓDIGO QR</div>
                    <div style="text-align: center; margin: 10px 0;">
                        ' . $qrCode . '
                    </div>
                    
                </div>';

        if ($introduccion->pt_numero || $introduccion->ptr_numero) {
            $html .= '
                <div style="margin-top: 12px; border: 1px solid #ccc; padding: 8px; background-color: #f9f9f9;">
                    <div style="font-weight: bold; font-size: 10px; margin-bottom: 5px;">PERMISOS DE TRÁNSITO</div>';

            if ($introduccion->pt_numero) {
                $html .= '<div style="font-size: 9px; margin-bottom: 2px;">P.T. N°: ' . $introduccion->pt_numero . '</div>';
            }

            if ($introduccion->ptr_numero) {
                $html .= '<div style="font-size: 9px;">P.T.R. N°: ' . $introduccion->ptr_numero . '</div>';
            }

            $html .= '</div>';
        }

        $html .= '
            </td>
        </tr>
    </table>';

        if ($introduccion->observaciones) {
            $html .= '
    <!-- Observaciones -->
    <div class="section-title">OBSERVACIONES</div>
    <div style="border: 1px solid #ccc; padding: 8px; min-height: 30px; background-color: #fafafa;">' .
                nl2br(htmlspecialchars($introduccion->observaciones)) .
                '</div>';
        }

        $html .= '
    <!-- Footer -->
    <div class="footer">
        <div style="border-top: 1px solid #333; padding-top: 8px;">
            <strong>Sistema de Gestión Abasto</strong><br>
            Usuario: ' . ($introduccion->usuario->name ?? 'Sistema') . ' | 
            Generado: ' . now()->format('d/m/Y H:i') . ' | 
            ID Interno: ' . $introduccion->id . '
        </div>
    </div>';

        return $html;
    }
}
