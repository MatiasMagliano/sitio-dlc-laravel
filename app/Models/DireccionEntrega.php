<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DireccionEntrega extends Model
{
    use HasFactory;

    protected $table = 'direcciones_entrega';

    protected $fillable = [
        'lugar_entrega', 'domicilio', 'condiciones', 'observaciones', 'mas_entregado'
    ];

    // RELACIONES
    public function cotizacion()
    {
        $this->belongsToMany(Cotizacion::class);
    }

    public function clientes(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function provincias(): HasMany
    {
        return $this->hasMany(provincias::class);
    }

    public function localidades(): HasMany
    {
        return $this->hasMany(localidades::class);
    }
}
