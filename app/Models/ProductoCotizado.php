<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductoCotizado extends Model
{
    protected $fillable = [
        'cotizacions_id', 'presentacion_id', 'cantidad', 'precio', 'total'
    ];



    // relaciones
    /**
     * Get the presentaciones that owns the ProductoCotizado
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function presentaciones(): BelongsTo
    {
        return $this->belongsTo(Presentacion::class);
    }
    /**
     * Get the cotizaciones that owns the ProductoCotizado
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cotizaciones(): BelongsTo
    {
        return $this->belongsTo(Cotizacion::class);
    }
}
