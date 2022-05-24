<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use LotePresentacionProducto;

class ProductoOrdenTrabajo extends Model
{
    use HasFactory;

    protected $table = 'producto_orden_trabajos';

    protected $fillable = [
        'orden_trabajo_id', 'producto_id', 'presentacion_id', 'lote_id', 'cantidad', 'precio', 'total'
    ];

    // RELACIONES

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function cotizaciones(): BelongsTo
    {
        return $this->belongsTo(Cotizacion::class);
    }

    public function lotes(): HasManyThrough
    {
        return $this->hasManyThrough(
            Lote::class,
            LotePresentacionProducto::class,
            'abc',
            'xyz'
        );
    }
}
