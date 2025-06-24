<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acta extends Model
{
    use HasFactory;

    protected $connection = 'infracciones';
    protected $table = 'acta';

     // IMPORTANTE: Desactivar timestamps ya que la tabla no los tiene
    public $timestamps = false;

    protected $fillable = [
        'numero_acta',
        'tipo_acta',
        'id_persona',
        'id_objeto',
        'numero_licencia',
        'lugar_emision',
        'es_verbal',
        'retiene_licencia',
        'retiene_vehiculo',
        'retiro_vehiculo',
        'graduacion_1',
        'equipo_1',
        'graduacion_2',
        'equipo_2',
        'motivo',
        'observaciones',
        'destino_acta',
        'monto',
        'sam',
        'profesional',
        'ubicacion',
        'longitud',
        'latitud',
        'usuario',
        'cc',
        'fecha_hora',
        'hora_inicio',
        'borrado',
        'safim',
        'estado',
        'id_expediente',
        'fecha_alta'
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
        'fecha_alta' => 'datetime',
        'retiro_vehiculo' => 'datetime',
        'longitud' => 'decimal:8',
        'latitud' => 'decimal:8',
        'graduacion_1' => 'decimal:2',
        'graduacion_2' => 'decimal:2',
        'monto' => 'decimal:2',
    ];

    // Relaciones
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona');
    }

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'id_objeto');
    }

    public function centrocosto()
    {
        return $this->belongsTo(CentroCosto::class, 'cc');
    }

    public function tipos()
    {
        return $this->hasMany(ActaTipo::class, 'id_acta');
    }

    public function documentacion()
    {
        return $this->hasMany(ActaDocumentacion::class, 'id_acta');
    }

    // Scopes
    public function scopeActivas($query)
    {
        return $query->where('borrado', 'N');
    }

    public function scopePorInspector($query, $usuario)
    {
        return $query->where('usuario', $usuario);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_acta', $tipo);
    }

    // Métodos helper
    public function getTokenAttribute()
    {
        return encrypt($this->id);
    }

    public function getUrlPublicaAttribute()
    {
        return route('actas.publica', $this->token);
    }

    public function getTipoActaDescripcionAttribute()
    {
        $tipos = [
            'A' => 'Abasto',
            'B' => 'Bromatología',
            'C' => 'Comercio',
            'T' => 'Tránsito',
            'TC' => 'Tránsito - Comprobación',
            'S' => 'Sanidad e Higiene',
        ];

        return $tipos[$this->tipo_acta] ?? 'Desconocido';
    }
}
