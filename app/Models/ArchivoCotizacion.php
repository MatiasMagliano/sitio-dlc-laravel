<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArchivoCotizacion extends Model
{
    protected $table = 'archivo_cotizacions';

    protected $fillable = [
        'nombre_archivo', 'causa_subida', 'motivo_rechazo'
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
