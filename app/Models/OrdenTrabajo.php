<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrdenTrabajo extends Model
{
    use HasFactory;

    protected $table = 'orden_trabajos';

    protected $fillable = [
        'cotizacion_id', 'user_id', 'plazo_entrega', 'en_produccion', 'estado_id'
    ];

    protected $casts = [
        'en_produccion' => 'datetime'
    ];

    // RELACIONES
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cotizacion(): BelongsTo
    {
        return $this->belongsTo(Cotizacion::class);
    }

    public function productos(): HasMany
    {
        return $this->hasMany(ProductoOrdenTrabajo::class);
    }

    public function estado(){
        return $this->belongsTo(Estado::class);
    }
}
