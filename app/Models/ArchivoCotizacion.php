<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArchivoCotizacion extends Model
{
    use SoftDeletes;
    protected $table = 'archivo_cotizacions';

    protected $fillable = [
        'ruta', 'nombre_archivo', 'causa_subida'
    ];

    // RELACIONES
    /**
     * Get the cotizacion that owns the ArchivoCotizacion
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cotizacion(): BelongsTo
    {
        return $this->belongsTo(Cotizacion::class);
    }
}
