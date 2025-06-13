<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'categoria',
        'tipo_medicion',
        'unidad_primaria',
        'unidad_secundaria',
        'dias_vencimiento',
        'requiere_temperatura',
        'activo'
    ];

    protected $casts = [
        'dias_vencimiento' => 'integer',
        'requiere_temperatura' => 'boolean',
        'activo' => 'boolean',
    ];

    // Constantes
    const TIPO_CANTIDAD = 'CANTIDAD';
    const TIPO_PESO = 'PESO';
    const TIPO_MIXTO = 'MIXTO';

    const CATEGORIAS = [
        'CARNES' => 'Carnes',
        'LACTEOS' => 'Lácteos',
        'FRUTAS' => 'Frutas y Verduras',
        'PANADERIA' => 'Panadería',
        'FIAMBRES' => 'Fiambres',
        'BEBIDAS' => 'Bebidas',
        'OTROS' => 'Otros'
    ];

    // Relaciones
    public function introduccionProductos()
    {
        return $this->hasMany(IntroduccionProducto::class);
    }

    public function redespachoProductos()
    {
        return $this->hasMany(RedespachoProducto::class);
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopePorCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    public function scopeMixtos($query)
    {
        return $query->where('tipo_medicion', self::TIPO_MIXTO);
    }

    // Accessors
    public function getEsMixtoAttribute()
    {
        return $this->tipo_medicion === self::TIPO_MIXTO;
    }

    public function getUnidadDisplayAttribute()
    {
        if ($this->es_mixto) {
            return $this->unidad_primaria . ' / ' . $this->unidad_secundaria;
        }
        return $this->unidad_secundaria;
    }
}
