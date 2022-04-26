<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre_corto', 'razon_social', 'tipo_afip', 'afip', 'telefono', 'email', 'contacto',
    ];

    // casteos
    protected $casts = [
        'ultima_compra' => 'datetime'
    ];

    // relaciones
    /**
     * Get all of the direccionesEntrega for the Cliente
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function direccionesEntrega(): HasMany
    {
        return $this->hasMany(DireccionEntrega::class);
    }
    /* hasMany --> cotizacion
    *  hasMany --> ¿transacciones?
     */
}
