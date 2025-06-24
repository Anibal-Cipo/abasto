<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;

    protected $connection = 'infracciones';
    protected $table = 'vehiculos';

     // AGREGAR ESTA LÍNEA:
    public $timestamps = false;

    protected $fillable = [
        'dominio',
        'motor',
        'chasis',
        'color',
        'id_modelo',
        'id_marca',
        'tipo_vehiculo'
    ];

    // Relaciones
    public function marca()
    {
        return $this->belongsTo(VehiculoMarca::class, 'id_marca');
    }

    public function modelo()
    {
        return $this->belongsTo(VehiculoModelo::class, 'id_modelo');
    }

    public function actas()
    {
        return $this->hasMany(Acta::class, 'id_objeto');
    }

    // Métodos helper
    public function getMarcaModeloAttribute()
    {
        $marcaModelo = '';
        if ($this->marca) {
            $marcaModelo .= $this->marca->marca;
        }
        if ($this->modelo) {
            $marcaModelo .= ($marcaModelo ? ' ' : '') . $this->modelo->modelo;
        }
        return $marcaModelo ?: 'No especificado';
    }

    public static function buscarPorDominio($dominio)
    {
        return static::where('dominio', strtoupper($dominio))->first();
    }

    // Mutator para el dominio en mayúsculas
    public function setDominioAttribute($value)
    {
        $this->attributes['dominio'] = strtoupper($value);
    }
}
