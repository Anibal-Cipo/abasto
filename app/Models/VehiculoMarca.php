<?php
// app/Models/VehiculoMarca.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehiculoMarca extends Model
{
    use HasFactory;

    protected $connection = 'infracciones';
    protected $table = 'vehiculo_marca';

     // AGREGAR ESTA LÍNEA:
    public $timestamps = false;

    protected $fillable = ['marca'];

    public function modelos()
    {
        return $this->hasMany(VehiculoModelo::class, 'id_marca');
    }

    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class, 'id_marca');
    }
}
