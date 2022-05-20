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

    public static function listarDescuentos($producto, $presentacion, $cotizacion)
    {   
        
        $mercaderia = ListaPrecio::select('proveedors.razon_social','costo AS costo_1','costo AS costo_2','costo AS costo_3','costo AS costo_4','costo AS costo_5')
        ->join('proveedors','lista_precios.proveedor_id','=','proveedors.id')
        ->where('lista_precios.id', ListaPrecio::getIdListaPrecio($producto, $presentacion))
        ->get();

        $descuentos = EsquemaPrecio::select('porcentaje_1','porcentaje_2','porcentaje_3','porcentaje_4','porcentaje_5')
        ->join('clientes','esquema_precios.cliente_id','=','clientes.id')
        ->join('cotizacions','clientes.id','=','cotizacions.cliente_id')
        ->where('cotizacions.id', $cotizacion)
        ->get();        

        foreach($mercaderia as $m_row){
            $m_row->razon_social;
            foreach($descuentos as $d_row){
                $m_row->costo_1 = $m_row->costo_1 * (1 + ($d_row->porcentaje_1 / 100));
                $m_row->costo_2 = $m_row->costo_2 * (1 + ($d_row->porcentaje_2 / 100));
                $m_row->costo_3 = $m_row->costo_3 * (1 + ($d_row->porcentaje_3 / 100));
                $m_row->costo_4 = $m_row->costo_4 * (1 + ($d_row->porcentaje_4 / 100));
                $m_row->costo_5 = $m_row->costo_5 * (1 + ($d_row->porcentaje_5 / 100));
            }
        }

        return $mercaderia;


        // return ListaPrecio::select('proveedors.razon_social','costo AS costo_1','costo AS costo_2','costo AS costo_3','costo AS costo_4','costo AS costo_5')
        //     ->join('proveedors','lista_precios.proveedor_id','=','proveedors.id')
        //     ->where('lista_precios.id', ListaPrecio::getIdListaPrecio($producto, $presentacion))
        //     ->get();
    }

}
