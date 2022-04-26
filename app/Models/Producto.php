<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
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

    //Devuelve los proveedores de este producto y presentacion dada
    public function proveedores($presentacion_id){
        return Proveedor::select('*')
            ->join('lista_precios', 'lista_precios.proveedor_id', '=', 'proveedors.id')
            ->join('lote_presentacion_producto', 'lote_presentacion_producto.id', '=', 'lista_precios.lpp_id')
            ->where('lote_presentacion_producto.producto_id', '=', $this->id)
            ->where('lote_presentacion_producto.presentacion_id', '=', $presentacion_id)
            ->get();
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
