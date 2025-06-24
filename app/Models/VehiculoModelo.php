<?php
// app/Models/VehiculoModelo.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehiculoModelo extends Model
{
    use HasFactory;

    protected $connection = 'infracciones';
    protected $table = 'vehiculo_modelo';
     // AGREGAR ESTA LÍNEA:
    public $timestamps = false;

    protected $fillable = ['modelo', 'id_marca'];

    public function marca()
    {
        return $this->belongsTo(VehiculoMarca::class, 'id_marca');
    }

    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class, 'id_modelo');
    }
}
