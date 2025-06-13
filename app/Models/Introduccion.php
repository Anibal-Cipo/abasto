<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Introduccion extends Model
{
    use HasFactory;

    protected $table = 'introducciones';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'introductor_id',
        'user_id',
        'numero_remito',
        'fecha',
        'hora',
        'vehiculo',
        'dominio',
        'habilitacion_vehiculo',
        'receptores',
        'temperatura',
        'observaciones',
        'pt_numero',
        'ptr_numero',
        'qr_code',
        'precintos_origen',
        'reprecintado',
        'ganaderia_numero',
        'remito_papel',
        'numero_remito_papel',
        'envia',              // NUEVO
        'procedencia',        // NUEVO
        'vigente',     
    ];

    // CASTS SIMPLES - sin problemas
    protected $casts = [
        'fecha' => 'date',
        'temperatura' => 'decimal:2',
        'remito_papel' => 'boolean',
        'vigente' => 'boolean',  // NUEVO
    ];

    // RELACIONES
    public function introductor()
    {
        return $this->belongsTo(Introductor::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function productos()
    {
        return $this->hasMany(IntroduccionProducto::class);
    }

    public function archivos()
    {
        return $this->hasMany(IntroduccionArchivo::class);
    }

    public function redespachos()
    {
        return $this->hasMany(Redespacho::class);
    }

    // ACCESSORS - Para mostrar datos formateados
    public function getFechaFormateadaAttribute()
    {
        return $this->fecha ? $this->fecha->format('d/m/Y') : 'Sin fecha';
    }

    public function getHoraFormateadaAttribute()
    {
        if (!$this->hora) {
            return 'Sin hora';
        }
        // Si hora es "13:57:00", mostrar solo "13:57"
        return substr($this->hora, 0, 5);
    }

    public function getFechaHoraAttribute()
    {
        return $this->fecha_formateada . ' ' . $this->hora_formateada;
    }

    // MÉTODOS AUXILIARES
    public function generarQrCode()
    {
        $this->qr_code = 'INT-' . $this->id . '-' . md5($this->numero_remito . $this->fecha);
        $this->save();
        return $this->qr_code;
    }

    public function stockDisponible()
    {
        return $this->productos->map(function ($item) {
            $totalRedespachado = $this->redespachos()
                ->with('productos')
                ->get()
                ->pluck('productos')
                ->flatten()
                ->where('producto_id', $item->producto_id)
                ->sum('cantidad_secundaria');

            $item->stock_disponible = $item->cantidad_secundaria - $totalRedespachado;
            return $item;
        });
    }

    // SCOPES
    public function scopeHoy($query)
    {
        return $query->whereDate('fecha', today());
    }

    public function scopePorFecha($query, $fechaInicio, $fechaFin = null)
    {
        if ($fechaFin) {
            return $query->whereBetween('fecha', [$fechaInicio, $fechaFin]);
        }
        return $query->whereDate('fecha', $fechaInicio);
    }

    public function scopeConStock($query)
    {
        return $query->whereHas('productos', function ($q) {
            $q->whereRaw('cantidad_secundaria > (
                SELECT COALESCE(SUM(rp.cantidad_secundaria), 0) 
                FROM redespacho_productos rp 
                INNER JOIN redespachos r ON r.id = rp.redespacho_id 
                WHERE r.introduccion_id = introducciones.id 
                AND rp.producto_id = introduccion_productos.producto_id
            )');
        });
    }

    // MÉTODOS ESTÁTICOS
    public static function generarNumeroRemito()
    {
        $año = date('Y');
        $ultimoRemito = self::whereYear('created_at', $año)
            ->where('remito_papel', false)
            ->orderBy('id', 'desc')
            ->first();

        if ($ultimoRemito) {
            $partes = explode('-', $ultimoRemito->numero_remito);
            $ultimoNumero = intval(end($partes));
            $nuevoNumero = $ultimoNumero + 1;
        } else {
            $nuevoNumero = 1;
        }

        return 'REMI-' . $año . '-' . str_pad($nuevoNumero, 6, '0', STR_PAD_LEFT);
    }

    public function getNumeroRemitoDisplayAttribute()
    {
        return $this->remito_papel ? $this->numero_remito_papel : $this->numero_remito;
    }
}