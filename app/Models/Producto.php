<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;

class Producto extends Model
{
    use HasFactory, SoftDeletes, Sortable;

    protected $table = 'productos';

    //Se setean los campos "llenables" en masa
    /**
     * @var array
     */
    protected $fillable = [
        'droga'
    ];

    public $sortable = [
        'droga'
    ];

    //Se definen las relaciones
    public static function presentaciones($producto)
    {
        return DB::table('presentacions')
            ->leftJoin('lote_presentacion_producto', 'lote_presentacion_producto.presentacion_id', '=', 'presentacions.id')
            ->where('lote_presentacion_producto.producto_id', $producto)
            ->select('presentacions.*')
            ->distinct()
            ->get();
    }

    public static function lotes($producto, $presentacion)
    {
        return DB::table('lotes')
            ->leftJoin('lote_presentacion_producto', 'lote_presentacion_producto.lote_id', '=', 'lotes.id')
            ->where('lote_presentacion_producto.producto_id', $producto)
            ->where('lote_presentacion_producto.presentacion_id', $presentacion)
            ->select('lotes.*')
            ->get();
    }

    public static function deposito($producto, $presentacion)
    {
        return DB::table('deposito_casa_centrals')
            ->leftJoin('lote_presentacion_producto', 'lote_presentacion_producto.dcc_id', '=', 'deposito_casa_centrals.id')
            ->where('lote_presentacion_producto.producto_id', $producto)
            ->where('lote_presentacion_producto.presentacion_id', $presentacion)
            ->select('deposito_casa_centrals.*')
            ->distinct()
            ->get();
    }

    public static function proveedores($producto, $presentacion){
        return DB::table('proveedors')
            ->join('lista_precios', 'lista_precios.proveedor_id', '=', 'proveedors.id')
            ->where('lista_precios.producto_id', '=', $producto)
            ->where('lista_precios.presentacion_id', '=', $presentacion)
            ->select('proveedors.*')
            ->get();
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
