<?php
// app/Models/CentroCosto.php - VERSIÓN CORREGIDA

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentroCosto extends Model
{
    use HasFactory;

    protected $connection = 'infracciones';
    protected $table = 'cc';

    // IMPORTANTE: Desactivar timestamps ya que la tabla no los tiene
    public $timestamps = false;

    protected $fillable = ['codigo', 'cc'];

    // Relaciones
    public function actas()
    {
        return $this->hasMany(Acta::class, 'cc', 'id');
    }

    public function tiposInfraccion()
    {
        return $this->hasMany(TipoInfraccion::class, 'cc', 'codigo');
    }
}
