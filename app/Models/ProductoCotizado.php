<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductoCotizado extends Model
{
    use HasFactory;

    protected $table = 'producto_cotizados';
    protected $withCount = ['producto'];

    protected $fillable = [
        'cotizacion_id', 'producto_id', 'presentacion_id', 'cantidad', 'precio', 'total'
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

    public function cotizaciones(): BelongsTo
    {
        return $this->belongsTo(Cotizacion::class);
    }
}
