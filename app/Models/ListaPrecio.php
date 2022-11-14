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
        'codigoProv',
        'producto_id',
        'presentacion_id',
        'proveedor_id',
        'costo',
    ];

    //Index
    public static function getAllListasDePrecios(){
        $allListasDePrecios = ListaPrecio::select('lista_precios.proveedor_id','proveedors.razon_social AS razon_social','proveedors.cuit AS cuit',
        ListaPrecio::raw('count(lista_precios.id) AS prods') , ListaPrecio::raw('min(lista_precios.created_at) AS creado'),
        ListaPrecio::raw('max(lista_precios.updated_at) AS modificado'))
            ->join('proveedors','lista_precios.proveedor_id','=','proveedors.id')
            ->groupBy('proveedors.cuit','proveedors.razon_social','lista_precios.proveedor_id')
            ->orderBy('proveedors.razon_social')
            ->get();
        return $allListasDePrecios;
    }
    public static function proveedoresSinLista(){
        $proveedoresSinLista = Proveedor::select(Proveedor::raw('count(*) AS provs'))
            ->leftjoin('lista_precios','proveedors.id','lista_precios.proveedor_id')
            ->whereNull(['proveedors.deleted_at'])
            ->whereNull('lista_precios.proveedor_id')
            ->get();
        return $proveedoresSinLista;
    }

    //Show
    public static function getListaDeProveedor($razon_social)
    {
        $listado = ListaPrecio::select('proveedors.cuit','lista_precios.id as listaId','producto_id','presentacion_id','razon_social','cuit','codigoProv','droga','presentacion','forma','costo','lista_precios.updated_at')
            ->join('productos','lista_precios.producto_id','=','productos.id')
            ->join('proveedors','lista_precios.proveedor_id','=','proveedors.id')
            ->join('presentacions','lista_precios.presentacion_id','=','presentacions.id')
            ->where('proveedors.razon_social','=', $razon_social)
            ->get();
        return $listado;
    }

    public static function deleteListaByProveedorId($proveedor_id)
    {
        $deleted = DB::table('lista_precios')->where('proveedor_id','=', $proveedor_id)->delete();
        return $deleted;
    }


        //trae  id lote presentacion
    public static function getIdListaPrecio($producto, $presentacion){
        return DB::table('lista_precios')
            ->where('producto_id', $producto)
            ->where('presentacion_id', $presentacion)
            ->pluck('id');
    }




    public static function deletelistaDeProveedor($cuit)
    {
        $listaDeProveedor = DB::table('lista_precios')
        ->join('proveedors','lista_precios.proveedor_id','=','proveedors.id')
        ->where('proveedors.cuit', '=', $cuit)
        ->delete();
        return $listaDeProveedor;
    }

    public static function getItemLista($idItem)
    {
        $itemLista = ListaPrecio::select('lista_precios.id','producto.droga','presentacions.forma','presentacions.presentacion','lista_precio.codigoProv','lista_precio.costo')
        ->join('proveedors','lista_precios.proveedor_id','=','proveedors.id')
        ->join('productos','lista_precios.producto_id','=','productos.id')
        ->join('presentacions','lista_precios.presentacion_id','=','presentacions.id')
        ->where('lista_precios.id','=', $idItem)
        ->get();

        return $itemLista;
    }


    public static function listarDescuentos($producto, $presentacion, $cotizacion)
    {

        $mercaderia = ListaPrecio::select('proveedors.razon_social','costo AS costo_1','costo AS costo_2','costo AS costo_3','costo AS costo_4','costo AS costo_5')
            ->join('proveedors','lista_precios.proveedor_id','=','proveedors.id')
            ->whereIn('lista_precios.id', ListaPrecio::getIdListaPrecio($producto, $presentacion))
            ->get();

        $descuentos = EsquemaPrecio::select('porcentaje_1','porcentaje_2','porcentaje_3','porcentaje_4','porcentaje_5')
            ->join('clientes','esquema_precios.cliente_id','=','clientes.id')
            ->join('cotizacions','clientes.id','=','cotizacions.cliente_id')
            ->where('cotizacions.id', $cotizacion)
            ->get();

        // ESTA FUNCIONALIDAD DEBERÍA IR EN EL CONTROLLER (el modelo sólo debe preocuparse por los datos)
        foreach($mercaderia as $m_row){
            $m_row->razon_social;
            foreach($descuentos as $d_row){

                if($d_row->porcentaje_1 <= 0){
                    $m_row->costo_1 = '';
                }else{
                    $m_row->costo_1 = round(
                        $m_row->costo_1 * (1 + ($d_row->porcentaje_1 / 100)),
                        2
                    );
                }
                if($d_row->porcentaje_2 <= 0){
                    $m_row->costo_2 = '';
                }else{
                    $m_row->costo_2 = round(
                        $m_row->costo_2 * (1 + ($d_row->porcentaje_2 / 100)),
                        2
                    );
                }
                if($d_row->porcentaje_3 <= 0){
                    $m_row->costo_3 = '';
                }else{
                    $m_row->costo_3 = round(
                        $m_row->costo_3 * (1 + ($d_row->porcentaje_3 / 100)),
                        2
                    );
                }
                if($d_row->porcentaje_4 <= 0){
                    $m_row->costo_4 = '';
                }else{
                    $m_row->costo_4 = round(
                        $m_row->costo_4 * (1 + ($d_row->porcentaje_4 / 100)),
                        2
                    );
                }
                if($d_row->porcentaje_5 <= 0){
                    $m_row->costo_5 = '';
                }else{
                    $m_row->costo_5 = round(
                        $m_row->costo_5 * (1 + ($d_row->porcentaje_5 / 100)),
                        2
                    );
                }
            }
        }

        return $mercaderia;


        // return ListaPrecio::select('proveedors.razon_social','costo AS costo_1','costo AS costo_2','costo AS costo_3','costo AS costo_4','costo AS costo_5')
        //     ->join('proveedors','lista_precios.proveedor_id','=','proveedors.id')
        //     ->where('lista_precios.id', ListaPrecio::getIdListaPrecio($producto, $presentacion))
        //     ->get();
    }



}
