<?php

namespace App\Http\Controllers;

use App\Models\Redespacho;
use App\Models\Introduccion;
use App\Models\IntroduccionProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use TCPDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RedespachoController extends Controller
{
    public function index(Request $request)
    {
        $query = Redespacho::with(['introduccion.introductor', 'usuario', 'productos.producto']);

        // Filtros
        if ($request->filled('introduccion_id')) {
            $query->where('introduccion_id', $request->introduccion_id);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha', '<=', $request->fecha_hasta);
        }

        $redespachos = $query->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc')
            ->paginate(20)
            ->appends($request->query());

        return view('redespachos.index', compact('redespachos'));
    }

    public function create(Introduccion $introduccion)
    {
        // Verificar que tenga stock disponible
        $stockDisponible = $introduccion->stockDisponible();
        $tieneStock = $stockDisponible->where('stock_disponible', '>', 0)->count() > 0;

        if (!$tieneStock) {
            return back()->with('error', 'La introducción no tiene stock disponible para redespachar.');
        }

        $introduccion->load(['introductor', 'productos.producto']);

        return view('redespachos.create', compact('introduccion', 'stockDisponible'));
    }

    // Actualizar el método store en RedespachoController.php:

    public function store(Request $request, Introduccion $introduccion)
    {
        $validated = $request->validate([
            'numero_redespacho' => 'required|string|max:20|unique:redespachos,numero_redespacho',
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'destino' => 'required|string|max:255',
            'dominio' => 'nullable|string|max:10',
            'habilitacion_destino' => 'nullable|string|max:255',
            'certificado_sanitario' => 'boolean',
            'observaciones' => 'nullable|string',
            'productos' => 'required|array|min:1',
            'productos.*.activo' => 'sometimes|in:1',
            'productos.*.producto_id' => 'required|exists:productos,id',
            'productos.*.cantidad_primaria' => 'nullable|numeric|min:0',
            'productos.*.cantidad_secundaria' => 'required_if:productos.*.activo,1|numeric|min:0.01',
            'productos.*.observaciones' => 'nullable|string',
        ]);

        // Filtrar solo productos seleccionados
        $productosSeleccionados = collect($validated['productos'])
            ->filter(function ($producto) {
                return isset($producto['activo']) && $producto['activo'] == '1';
            });

        if ($productosSeleccionados->isEmpty()) {
            return back()->withErrors(['productos' => 'Debe seleccionar al menos un producto'])->withInput();
        }

        // Validar stock disponible
        $stockDisponible = $introduccion->stockDisponible()->keyBy('producto_id');

        foreach ($productosSeleccionados as $productoData) {
            $stock = $stockDisponible->get($productoData['producto_id']);
            if (!$stock || $productoData['cantidad_secundaria'] > $stock->stock_disponible) {
                $nombreProducto = $stock ? $stock->producto->nombre : 'Producto desconocido';
                return back()->withErrors([
                    'productos' => 'La cantidad solicitada excede el stock disponible para ' . $nombreProducto
                ])->withInput();
            }
        }

        DB::transaction(function () use ($validated, $introduccion, $productosSeleccionados) {
            // Crear redespacho
            $redespacho = $introduccion->redespachos()->create([
                'user_id' => auth()->id(),
                'numero_redespacho' => $validated['numero_redespacho'],
                'fecha' => $validated['fecha'],
                'hora' => $validated['hora'],
                'destino' => $validated['destino'],
                'dominio' => $validated['dominio'],
                'habilitacion_destino' => $validated['habilitacion_destino'],
                'certificado_sanitario' => request()->has('certificado_sanitario'),
                'observaciones' => $validated['observaciones'],
            ]);

            // Crear productos del redespacho
            foreach ($productosSeleccionados as $productoData) {
                $redespacho->productos()->create([
                    'producto_id' => $productoData['producto_id'],
                    'cantidad_primaria' => $productoData['cantidad_primaria'],
                    'cantidad_secundaria' => $productoData['cantidad_secundaria'],
                    'observaciones' => $productoData['observaciones'] ?? null,
                ]);
            }
        });

        return redirect()->route('introducciones.show', $introduccion)
            ->with('success', 'Redespacho registrado exitosamente.');
    }

    public function show(Redespacho $redespacho)
    {
        $redespacho->load([
            'introduccion.introductor',
            'usuario',
            'productos.producto'
        ]);

        return view('redespachos.show', compact('redespacho'));
    }

    public function destroy(Redespacho $redespacho)
    {
        $introduccionId = $redespacho->introduccion_id;
        $redespacho->delete();

        return redirect()->route('introducciones.show', $introduccionId)
            ->with('success', 'Redespacho eliminado exitosamente.');
    }


    // ******************pdf del redespacho ***************************//////

    public function imprimirRedespacho($id)
    {
        $redespacho = Redespacho::with([
            'introduccion.introductor',
            'usuario',
            'productos.producto'
        ])->findOrFail($id);

        // Crear instancia de TCPDF
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Configurar documento
        $pdf->SetCreator('Sistema de Gestión de Abasto');
        $pdf->SetAuthor($redespacho->usuario->name ?? 'Sistema');
        $pdf->SetTitle('Redespacho ' . $redespacho->numero_redespacho);
        $pdf->SetSubject('Redespacho de Productos');

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
        $qrCode = $this->generarQrParaTCPDF($redespacho->numero_redespacho);

        // Generar contenido HTML
        $html = $this->generarHTMLRedespacho($redespacho, $qrCode);

        // Escribir HTML
        $pdf->writeHTML($html, true, false, true, false, '');

        // Salida
        return response($pdf->Output('redespacho-' . $redespacho->numero_redespacho . '.pdf', 'I'))
            ->header('Content-Type', 'application/pdf');
    }

    public function descargarRedespacho($id)
    {
        $redespacho = Redespacho::with([
            'introduccion.introductor',
            'usuario',
            'productos.producto'
        ])->findOrFail($id);

        // Crear instancia de TCPDF
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Configurar documento
        $pdf->SetCreator('Sistema de Gestión de Abasto');
        $pdf->SetAuthor($redespacho->usuario->name ?? 'Sistema');
        $pdf->SetTitle('Redespacho ' . $redespacho->numero_redespacho);

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
        $qrCode = $this->generarQrParaTCPDF($redespacho->numero_redespacho);

        // Generar contenido HTML
        $html = $this->generarHTMLRedespacho($redespacho, $qrCode);

        // Escribir HTML
        $pdf->writeHTML($html, true, false, true, false, '');

        // Salida para descarga
        return response($pdf->Output('redespacho-' . $redespacho->numero_redespacho . '.pdf', 'D'))
            ->header('Content-Type', 'application/pdf');
    }

    private function generarQrParaTCPDF($numeroRedespacho)
    {
        try {
            // Usar API externa que ya sabemos que funciona
            $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=120x120&format=png&data=' . urlencode($numeroRedespacho);
            $qrImageData = file_get_contents($qrUrl);

            if ($qrImageData !== false) {
                $base64 = base64_encode($qrImageData);
                return '<img src="data:image/png;base64,' . $base64 . '" style="width: 90px; height: 90px; border: 1px solid #ddd;">';
            }
        } catch (\Exception $e) {
            \Log::error('Error generando QR para redespacho: ' . $e->getMessage());
        }

        // Fallback
        return '<div style="width: 90px; height: 90px; border: 2px solid #333; text-align: center; line-height: 18px; font-size: 8px; padding: 5px; background-color: #f0f0f0;">
                <strong>QR CODE</strong><br>
                <small style="word-break: break-all;">' . substr($numeroRedespacho, 0, 25) . '</small><br>
                <small>Escanear para info</small>
            </div>';
    }

    private function generarHTMLRedespacho($redespacho, $qrCode)
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
    
    .numero-redespacho {
        width: 35%;
        text-align: center;
        vertical-align: middle;
        padding: 5px;
        border: 2px solid #333;
        line-height: 1.2;
        background-color: #fff3cd;
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
    
    .redespacho-numero {
        font-size: 12px;
        font-weight: bold;
        margin-bottom: 3px;
        line-height: 1.1;
    }
    
    .redespacho-fecha {
        font-size: 12px;
        margin-bottom: 1px;
        line-height: 1.1;
    }
    
    .redespacho-hora {
        font-size: 12px;
        line-height: 1.1;
    }
    
    .titulo-documento {
        text-align: center;
        font-size: 16px;
        font-weight: bold;
        margin: 15px 0;
        padding: 8px;
        background-color: #fff3cd;
        border: 1px solid #333;
        color: #856404;
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
    
    .introduccion-ref {
        background-color: #e7f3ff;
        border: 1px solid #0066cc;
        padding: 8px;
        margin: 8px 0;
        border-radius: 4px;
    }
</style>

<!-- Header Municipal -->
<div class="header-municipal">
    <table class="header-table">
        <tr>
            <td class="logo-section">
                <!-- Logo Municipal -->
                <img src="' . public_path('images/logo-municipal.png') . '" style="height: 60px; object-fit: contain;" alt="Logo Municipal">
            </td>
           <td class="municipio-info">
                <div class="municipio-nombre">MUNICIPALIDAD DE CIPOLLETTI</div>
                <div class="municipio-provincia">RÍO NEGRO</div>
                <div class="departamento">DEPARTAMENTO DE ABASTO E INTRODUCCIÓN</div>
                <div class="direccion">Secretaría de Fiscalización</div>
            </td>
            <td class="numero-redespacho">
                <div style="font-size: 12px; font-weight: bold;">REDESPACHO</div>
                <div class="redespacho-numero">N° ' . $redespacho->numero_redespacho . '</div>
                <div class="redespacho-fecha">Fecha: ' . $redespacho->fecha->format('d/m/Y') . '</div>
                <div class="redespacho-hora">Hora: ' . substr($redespacho->hora, 0, 5) . '</div>
            </td>
        </tr>
    </table>
</div>

<!-- Título del documento -->
<div class="titulo-documento">
    REDESPACHO DE PRODUCTOS
</div>

<!-- Referencia a Introducción Original -->
<div class="introduccion-ref">
    <strong>INTRODUCCIÓN DE ORIGEN:</strong> ' . $redespacho->introduccion->numero_remito . ' | 
    <strong>FECHA:</strong> ' . $redespacho->introduccion->fecha->format('d/m/Y') . ' | 
    <strong>INTRODUCTOR:</strong> ' . $redespacho->introduccion->introductor->razon_social . '
</div>

<!-- Contenido principal con tabla para layout -->
<table style="width: 100%;">
    <tr>
        <td style="width: 70%; vertical-align: top;">
            <!-- Datos del Redespacho -->
            <table class="info-table">
                <tr>
                    <td class="label">Destino:</td>
                    <td colspan="3">' . ($redespacho->destino ?? '-') . '</td>
                </tr>
                <tr>
                    <td class="label">Dominio:</td>
                    <td>' . ($redespacho->dominio ?? '-') . '</td>
                    <td class="label">Habilitación:</td>
                    <td>' . ($redespacho->habilitacion_destino ?? '-') . '</td>
                </tr>
                <tr>
                    <td class="label">Cert. Sanitario:</td>
                    <td>' . ($redespacho->certificado_sanitario ? 'SÍ' : 'NO') . '</td>
                    <td class="label"></td>
                    <td></td>
                </tr>
            </table>

            <!-- Introductor Original -->
            <div class="section-title">INTRODUCTOR ORIGINAL</div>
            <table class="info-table">
                <tr>
                    <td class="label">Razón Social:</td>
                    <td colspan="3">' . $redespacho->introduccion->introductor->razon_social . '</td>
                </tr>
                <tr>
                    <td class="label">CUIT:</td>
                    <td>' . $redespacho->introduccion->introductor->cuit_formateado . '</td>
                    <td class="label">Remito Origen:</td>
                    <td>' . $redespacho->introduccion->numero_remito . '</td>
                </tr>
            </table>

            <!-- Productos Redespachados -->
            <div class="section-title">PRODUCTOS REDESPACHADOS</div>
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

        foreach ($redespacho->productos as $producto) {
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
            </div>

            <!-- Información de Trazabilidad -->
            <div style="margin-top: 12px; border: 1px solid #ccc; padding: 8px; background-color: #f9f9f9;">
                <div style="font-weight: bold; font-size: 10px; margin-bottom: 5px;">TRAZABILIDAD</div>
                <div style="font-size: 9px; margin-bottom: 2px;">Origen: ' . $redespacho->introduccion->numero_remito . '</div>
                <div style="font-size: 9px; margin-bottom: 2px;">Fecha Origen: ' . $redespacho->introduccion->fecha->format('d/m/Y') . '</div>
                <div style="font-size: 9px;">QR Origen: ' . $redespacho->introduccion->qr_code . '</div>
            </div>
        </td>
    </tr>
</table>';

        if ($redespacho->observaciones) {
            $html .= '
<!-- Observaciones -->
<div class="section-title">OBSERVACIONES</div>
<div style="border: 1px solid #ccc; padding: 8px; min-height: 30px; background-color: #fafafa;">' .
                nl2br(htmlspecialchars($redespacho->observaciones)) .
                '</div>';
        }

        $html .= '
<!-- Footer -->
<div class="footer">
    <div style="border-top: 1px solid #333; padding-top: 8px;">
        <strong>Sistema de Gestión Abasto - REDESPACHO</strong><br>
        Usuario: ' . ($redespacho->usuario->name ?? 'Sistema') . ' | 
        Generado: ' . now()->format('d/m/Y H:i') . ' | 
        ID Interno: ' . $redespacho->id . '
    </div>
</div>';

        return $html;
    }
}