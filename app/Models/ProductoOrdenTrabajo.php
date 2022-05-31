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
        'orden_trabajo_id', 'producto_id', 'presentacion_id', 'lotes', 'cantidad', 'precio', 'total'
    ];

    // RELACIONES

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function ordenTrabajo(): BelongsTo
    {
        return $this->belongsTo(OrdenTrabajo::class);
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
