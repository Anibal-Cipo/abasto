class ActaTipo extends Model
{
    use HasFactory;

    protected $connection = 'infracciones';
    protected $table = 'acta_tipo';

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