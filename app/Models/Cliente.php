<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre_corto', 'razon_social', 'tipo_afip', 'afip', 'telefono', 'email', 'contacto', 'direccion',
    ];

    // casteos
    protected $casts = [
        'ultima_compra' => 'datetime'
    ];

    // relaciones
    /* hasMany --> cotizacion
    *  hasMany --> ¿transacciones?
     */
}
