<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActaTipo extends Model
{
    use HasFactory;

    protected $connection = 'infracciones';
    protected $table = 'acta_tipo';

    // Desactivar timestamps ya que la tabla no los tiene según tu SQL
    public $timestamps = false;

    protected $fillable = [
        'id_tipo',
        'id_acta',
        'valor_1',
        'valor_2',
        'fecha_alta'
    ];

    protected $casts = [
        'fecha_alta' => 'datetime'
    ];

    // Relaciones
    public function acta()
    {
        return $this->belongsTo(Acta::class, 'id_acta');
    }

    public function tipoInfraccion()
    {
        return $this->belongsTo(TipoInfraccion::class, 'id_tipo');
    }
}