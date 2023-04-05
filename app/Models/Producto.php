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
    protected $fillable = ['droga'];

    //Se definen las relaciones
    public function presentacion()
    {
        return $this->belongsToMany(
            Presentacion::class,
            LotePresentacionProducto::class,
        );
    }
    public function lote()
    {
        return $this->belongsToMany(
            Lote::class,
            LotePresentacionProducto::class
        );
    }

    // DEVUELVE TODOS LOS DEPÓSITOS DEL PRODUCTO
    public function dcc()
    {
        return $this->belongsToMany(
            DepositoCasaCentral::class,
            LotePresentacionProducto::class,
            '',
            'dcc_id'
        );
    }

    // DEVUELVE UN DEPÓSITO
    public function deposito($presentacion)
    {
        return $this->belongsToMany(
            DepositoCasaCentral::class,
            LotePresentacionProducto::class,
            '',
            'dcc_id'
        )->wherePivot('presentacion_id', '=', $presentacion);
    }

    //RELACIONES ESPECIALES (NO MODELOS) --> DEVUELVEN COLECCIONES DE DATOS
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

    public static function proveedores($producto, $presentacion){
        return DB::table('proveedors')
            ->join('lista_precios', 'lista_precios.proveedor_id', '=', 'proveedors.id')
            ->where('lista_precios.producto_id', '=', $producto)
            ->where('lista_precios.presentacion_id', '=', $presentacion)
            ->select('proveedors.*')
            ->get();
    }
}
