<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proveedor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'proveedors';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'razonSocial',
        'cuit',
        'contacto',
        'direccion',
        'url'
    ];

    // Se definen las relaciones
    /**
     * Get all of the lotes for the Proveedor
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lotes(): HasMany
    {
        return $this->hasMany(Lote::class);
    }
}
