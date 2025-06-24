<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoInfraccion extends Model
{
    use HasFactory;

    protected $connection = 'infracciones';
    protected $table = 'tipo_infraccion';

      // AGREGAR ESTA LÍNEA:
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'descripcion',
        'cc',
        'sam',
        'puntos',
        'allanamiento'
    ];

    protected $casts = [
        'sam' => 'decimal:2',
        'puntos' => 'integer',
    ];

    // Relaciones
    public function centroCosto()
    {
        return $this->belongsTo(CentroCosto::class, 'cc', 'codigo');
    }

    public function actaTipos()
    {
        return $this->hasMany(ActaTipo::class, 'id_tipo');
    }

    // Scopes
    public function scopePorCentroCosto($query, $cc)
    {
        return $query->where('cc', $cc);
    }

    public function scopeConAllanamiento($query)
    {
        return $query->where('allanamiento', 'S');
    }
}
