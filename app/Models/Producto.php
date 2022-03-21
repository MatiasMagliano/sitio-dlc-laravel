<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'productos';

    //Se setean los campos "llenables" en masa
    /**
     * @var array
     */
    protected $fillable = [
        'droga'
    ];

    //Se definen las relaciones

    /**
     * The presentaciones that belong to the Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function presentaciones(): BelongsToMany
    {
        return $this->belongsToMany(Presentacion::class, 'lote_presentacion_producto', 'producto_id', 'presentacion_id')->distinct();
    }

    public function lotesDeProducto(){
        return $this->hasManyThrough(
            Lote::class,
            LotePresentacionProducto::class,
            'producto_id',
            'id',
            null,
            'lote_id'
        );
    }
}
