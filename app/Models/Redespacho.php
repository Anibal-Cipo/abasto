<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Redespacho extends Model
{
    use HasFactory;

    protected $fillable = [
        'introduccion_id',
        'user_id',
        'numero_redespacho',
        'fecha',
        'hora',
        'destino',
        'dominio',
        'habilitacion_destino',
        'certificado_sanitario',
        'observaciones'
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora' => 'datetime:H:i',
        'certificado_sanitario' => 'boolean',
    ];

    // Relaciones
    public function introduccion()
    {
        return $this->belongsTo(Introduccion::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function productos()
    {
        return $this->hasMany(RedespachoProducto::class);
    }

    // Scopes
    public function scopeHoy($query)
    {
        return $query->whereDate('fecha', today());
    }

    public function getFechaHoraAttribute()
    {
        return $this->fecha->format('d/m/Y') . ' ' . $this->hora->format('H:i');
    }
    public static function generarNumeroRedespacho()
    {
        $año = date('Y');
        $ultimoRedespacho = self::whereYear('created_at', $año)
            ->orderBy('id', 'desc')
            ->first();

        if ($ultimoRedespacho) {
            $partes = explode('-', $ultimoRedespacho->numero_redespacho);
            $ultimoNumero = intval(end($partes));
            $nuevoNumero = $ultimoNumero + 1;
        } else {
            $nuevoNumero = 1;
        }

        return 'RED-' . $año . '-' . str_pad($nuevoNumero, 6, '0', STR_PAD_LEFT);
    }
}
