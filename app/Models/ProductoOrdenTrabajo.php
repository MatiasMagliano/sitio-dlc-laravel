<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function presentacion(): BelongsTo
    {
        return $this->belongsTo(Presentacion::class);
    }

    public function ordenTrabajo(): BelongsTo
    {
        return $this->belongsTo(OrdenTrabajo::class);
    }

    public function lotes()
    {
        return Lote::wherein('id', $this->lotes());
    }
}
