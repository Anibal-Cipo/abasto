<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    protected $connection = 'infracciones';
    protected $table = 'personas';

     // AGREGAR ESTA LÍNEA:
    public $timestamps = false;

    protected $fillable = [
        'dni',
        'apellido',
        'nombre',
        'nacionalidad',
        'fecha_nacimiento',
        'calle',
        'altura',
        'localidad',
        'localidad_desc',
        'telefono',
        'email',
        'observaciones'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'dni' => 'integer',
        'altura' => 'integer'
    ];

    // Relaciones
    public function actas()
    {
        return $this->hasMany(Acta::class, 'id_persona');
    }

    // Métodos helper
    public function getNombreCompletoAttribute()
    {
        return trim($this->apellido . ', ' . $this->nombre);
    }

    public function getDireccionCompletaAttribute()
    {
        $direccion = '';
        if ($this->calle) {
            $direccion .= $this->calle;
            if ($this->altura) {
                $direccion .= ' ' . $this->altura;
            }
        }
        if ($this->localidad_desc) {
            $direccion .= ($direccion ? ', ' : '') . $this->localidad_desc;
        }
        return $direccion;
    }

    public static function buscarPorDni($dni)
    {
        return static::where('dni', $dni)->first();
    }
}
