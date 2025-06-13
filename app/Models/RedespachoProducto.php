<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RedespachoProducto extends Model
{
    use HasFactory;

    protected $table = 'redespacho_productos';

    protected $fillable = [
        'redespacho_id',
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
    public function redespacho()
    {
        return $this->belongsTo(Redespacho::class);
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
}
