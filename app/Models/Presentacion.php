<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Presentacion extends Model
{
    use HasFactory, SoftDeletes;

    //Se setean los campos "llenables" en masa
    /**
     * @var array
     */
    protected $fillable = [
        'forma',
        'presentacion',
        'hospitalario',
        'trazabilidad'
    ];

    // Se definen las relaciones

    /**
     * Get all of the Lotes for the Presentacion
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lotes(): HasMany
    {
        return $this->hasMany(Lote::class);
    }
}
