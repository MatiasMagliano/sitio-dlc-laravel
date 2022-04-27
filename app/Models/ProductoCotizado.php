<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductoCotizado extends Model
{
    use HasFactory;

    protected $table = 'producto_cotizados';

    protected $fillable = [
        'cotizacion_id', 'producto_id', 'presentacion_id', 'cantidad', 'precio', 'total'
    ];



    // relaciones
    /**
     * Get the producto that owns the ProductoCotizado
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
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
