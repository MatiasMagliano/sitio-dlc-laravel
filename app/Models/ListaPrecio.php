<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ListaPrecio extends Model
{

    protected $table = 'lista_precios';

    //Llenables
    /**
     * @var array
     */
    protected $fillable = [
        'costo',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function proveedores(): BelongsTo{
        return $this->belongsTo(Proveedor::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lotepresentacionproducto(): BelongsTo{
        return $this->belongsTo(LotePresentacionProducto::class);
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public static function listaDeProveedor($proveedor)
    {
        return ListaPrecio::select('codigoProv','droga','presentacion','forma','costo')
            ->join('proveedors','lista_precios.proveedor_id','=','proveedors.id')
            ->join('lote_presentacion_producto', 'lista_precios.lpp_id','=','lote_presentacion_producto.id')
            ->join('productos','lote_presentacion_producto.producto_id','=','productos.id')
            ->join('presentacions','lote_presentacion_producto.presentacion_id','=','presentacions.id')
            ->where('proveedor_id','=', $proveedor)
            ->get();
    }
}
