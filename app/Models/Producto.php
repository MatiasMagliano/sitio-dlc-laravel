<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

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

    /**
     * The proveedores that belong to the Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function proveedores(): BelongsToMany
    {
        return $this->belongsToMany(Proveedor::class, 'lote_presentacion_producto', 'producto_id', 'proveedor_id')->distinct();
    }

    /**
     * The lotes that belong to the Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function lotes(): BelongsToMany
    {
        return $this->belongsToMany(LotePresentacionProducto::class, 'lote_presentacion_producto', 'producto_id', 'lote_id')->distinct();
    }

    // RELACIONES CUSTOM
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

    // event handlers para hacer el softdelete de las relaciones
    public static function boot() {
        parent::boot();

        static::deleting(function($producto) {
            // BORRA todos los lotes relacionados (antes de borrar el pivot)
            $producto->lotesDeProducto->each->delete;
            // BORRA todas las entradas de lote_presentacion_producto (pivots)
            LotePresentacionProducto::where('producto_id', $producto->id)->delete();
        });
    }
}
