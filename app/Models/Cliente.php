<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre_corto', 'razon_social', 'tipo_afip', 'afip', 'telefono', 'email', 'contacto',
    ];

    // casteos
    protected $casts = [
        'ultima_cotizacion' => 'datetime',
        'ultima_compra'     => 'datetime'
    ];

    // RELACIONES
    public function cotizaciones()
    {
        return $this->belongsToMany(Cotizacion::class);
    }

    public function dde(): HasMany
    {
        return $this->hasMany(DireccionEntrega::class);
    }
}
