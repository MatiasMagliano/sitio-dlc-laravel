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
        )->groupBy([
                'lote_presentacion_producto.producto_id',
                'lote_presentacion_producto.presentacion_id',
                'presentacions.id',
                'presentacions.forma',
                'presentacions.presentacion',
                'presentacions.hospitalario',
                'presentacions.trazabilidad',
                'presentacions.divisible',
                'presentacions.created_at',
                'presentacions.updated_at',
                'presentacions.deleted_at'
        ]);
    }
    public function lote()
    {
        return $this->belongsToMany(
            Lote::class,
            LotePresentacionProducto::class
        );
    }
    public function dcc()
    {
        return $this->belongsToMany(
            DepositoCasaCentral::class,
            LotePresentacionProducto::class,
            '',
            'dcc_id'
        )->groupBy([
            'lote_presentacion_producto.producto_id',
            'lote_presentacion_producto.presentacion_id',
            'lote_presentacion_producto.dcc_id',
        ]);
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
}
