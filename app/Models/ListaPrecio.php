<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

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

    public function lotepresentacionproducto(): BelongsTo{
        return $this->belongsTo(LotePresentacionProducto::class);
    }

        //trae  id lote presentacion
    public static function getIdListaPrecio($producto, $presentacion){
        return DB::table('lista_precios')
            ->where('producto_id', $producto)
            ->where('presentacion_id', $presentacion)    
            ->pluck('id');       
    }


    public static function listaDeProveedor($proveedor)
    {
        return ListaPrecio::select('codigoProv','droga','presentacion','forma','costo')
            ->join('productos','lista_precios.producto_id','=','productos.id')
            ->join('presentacions','lista_precios.presentacion_id','=','presentacions.id')
            ->where('proveedor_id','=', $proveedor)
            ->get();
    }

    public static function listarDescuentos($producto, $presentacion, $cliente)
    {   

        $descuentos = DB::table('esquema_precios')
        ->join('clientes','esquema_precios.cliente_id','=','clientes.id')
        ->where('clientes.id', $cliente)
        ->get();        



        return ListaPrecio::select('proveedors.razon_social','costo AS costo_1','costo AS costo_2','costo AS costo_3','costo AS costo_4','costo AS costo_5')
            ->join('proveedors','lista_precios.proveedor_id','=','proveedors.id')
            ->where('lista_precios.id', ListaPrecio::getIdListaPrecio($producto, $presentacion))
            ->get();
    }

}
