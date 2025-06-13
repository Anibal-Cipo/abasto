<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class IntroduccionArchivo extends Model
{
    use HasFactory;

    protected $table = 'introduccion_archivos';

    protected $fillable = [
        'introduccion_id',
        'tipo_archivo',
        'nombre_original',
        'nombre_archivo',
        'ruta_archivo',
        'mime_type',
        'tamaño_archivo'
    ];

    protected $casts = [
        'tamaño_archivo' => 'integer',
    ];

    // Constantes
    const TIPO_PT = 'PT';
    const TIPO_PTR = 'PTR';
    const TIPO_REMITO = 'REMITO_IMAGEN';

    // Relaciones
    public function introduccion()
    {
        return $this->belongsTo(Introduccion::class);
    }

    // Scopes
    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo_archivo', $tipo);
    }

    // Accessors
    public function getUrlAttribute()
    {
        return Storage::url($this->ruta_archivo);
    }

    public function getTamañoHumanoAttribute()
    {
        $bytes = $this->tamaño_archivo;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
