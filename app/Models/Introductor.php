<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Introductor extends Model
{
    use HasFactory;

    protected $table = 'introductores';

    protected $fillable = [
        'razon_social',
        'cuit',
        'direccion',
        'telefono',
        'email',
        'habilitacion_municipal',
        'activo',
        'tipo'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // Relaciones
    public function introducciones()
    {
        return $this->hasMany(Introduccion::class);
    }

    public function introduccionesRecientes($limite = 5)
    {
        return $this->hasMany(Introduccion::class)
                    ->with(['productos.producto', 'archivos'])
                    ->orderBy('fecha', 'desc')
                    ->orderBy('hora', 'desc')
                    ->limit($limite);
    }

    // Scopes
    // Scopes para tipos
    public function scopeIntroductores($query)
    {
        return $query->whereIn('tipo', ['introductor', 'ambos']);
    }
     public function scopeReceptores($query)
    {
        return $query->whereIn('tipo', ['receptor', 'ambos']);
    }
    

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeBuscar($query, $termino)
    {
        return $query->where(function($q) use ($termino) {
            $q->where('razon_social', 'like', "%{$termino}%")
              ->orWhere('cuit', 'like', "%{$termino}%");
        });
    }

    // Mutators
    public function setCuitAttribute($value)
    {
        $this->attributes['cuit'] = preg_replace('/[^0-9]/', '', $value);
    }

    // Accessors
    public function getCuitFormateadoAttribute()
    {
        $cuit = $this->cuit;
        return substr($cuit, 0, 2) . '-' . substr($cuit, 2, 8) . '-' . substr($cuit, 10, 1);
    }
}

