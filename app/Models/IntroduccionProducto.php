<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntroduccionProducto extends Model
{
    use HasFactory;

    protected $table = 'introduccion_productos';

    protected $fillable = [
        'introduccion_id',
        'producto_id',
        'cantidad_primaria',
        'cantidad_secundaria',
        'observaciones'
    ];

    protected $casts = [
        'cantidad_primaria' => 'decimal:2',
        'cantidad_secundaria' => 'decimal:2',
    ];

    // Relaciones
    public function introduccion()
    {
        return $this->belongsTo(Introduccion::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    // Accessors
    public function getCantidadDisplayAttribute()
    {
        if ($this->producto->es_mixto && $this->cantidad_primaria) {
            return $this->cantidad_primaria . ' ' . $this->producto->unidad_primaria . 
                   ' / ' . $this->cantidad_secundaria . ' ' . $this->producto->unidad_secundaria;
        }
        return $this->cantidad_secundaria . ' ' . $this->producto->unidad_secundaria;
    }

    public function getStockRedespachado()
    {
        return RedespachoProducto::whereHas('redespacho', function($query) {
                $query->where('introduccion_id', $this->introduccion_id);
            })
            ->where('producto_id', $this->producto_id)
            ->sum('cantidad_secundaria');
    }

    public function getStockDisponible()
    {
        return $this->cantidad_secundaria - $this->getStockRedespachado();
    }
}
