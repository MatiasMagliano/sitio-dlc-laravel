<?php

namespace App\Models;

use Hamcrest\Core\HasToString;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ListaPrecio extends Model
{

    use SoftDeletes;

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
        'costo'
    ];

    //INDEX
    public static function getAllListasDePrecios() {
        $allListasDePrecios = Proveedor::select('proveedors.id AS proveedorId','proveedors.razon_social AS razon_social',
        'proveedors.cuit AS cuit', /*'proveedors.created_at AS alta',*/ //ListaPrecio::raw('min(lista_precios.created_at) AS creado'), 
        ListaPrecio::raw('count(lista_precios.id) AS prods'),
        ListaPrecio::raw('count(lista_precios.deleted_at) AS inactives'), ListaPrecio::raw('count(lista_precios.id) - count(lista_precios.deleted_at) AS actives'), 
        ListaPrecio::raw('CASE WHEN min(lista_precios.created_at) IS NULL THEN proveedors.created_at ELSE min(lista_precios.created_at) END AS creado'),
        ListaPrecio::raw('CASE WHEN max(lista_precios.updated_at) IS NULL THEN proveedors.created_at ELSE max(lista_precios.updated_at) END AS modificado'),
        ListaPrecio::raw('MAX(lista_precios.deleted_at) AS lasts')/*,ListaPrecio::raw('max(lista_precios.updated_at) AS modificado')*/)
            ->leftJoin('lista_precios','lista_precios.proveedor_id','=','proveedors.id')
            ->groupBy('proveedors.id','proveedors.cuit','proveedors.razon_social','lista_precios.proveedor_id', 'proveedors.created_at')
            ->orderBy('proveedors.razon_social')
            ->get();
        return $allListasDePrecios;
    }
    public static function getAllListasDePrecios1() {
        $allListasDePrecios = ListaPrecio::select('proveedors.id AS proveedorId','proveedors.razon_social AS razon_social',
        'proveedors.cuit AS cuit', /*'proveedors.created_at AS alta',*/ //ListaPrecio::raw('min(lista_precios.created_at) AS creado'), 
        ListaPrecio::raw('count(lista_precios.id) AS prods'),
        ListaPrecio::raw('count(lista_precios.deleted_at) AS inactives'), ListaPrecio::raw('count(lista_precios.id) - count(lista_precios.deleted_at) AS actives'), 
        ListaPrecio::raw('CASE WHEN min(lista_precios.created_at) IS NULL THEN proveedors.created_at ELSE min(lista_precios.created_at) END AS creado'),
        ListaPrecio::raw('CASE WHEN max(lista_precios.updated_at) IS NULL THEN proveedors.created_at ELSE max(lista_precios.updated_at) END AS modificado'),
        'lista_precios.deleted_at'/*,ListaPrecio::raw('max(lista_precios.updated_at) AS modificado')*/)
            ->rightJoin('proveedors','lista_precios.proveedor_id','=','proveedors.id')
            ->groupBy('proveedors.id','proveedors.cuit','proveedors.razon_social','lista_precios.proveedor_id', 'proveedors.created_at','lista_precios.deleted_at')
            ->orderBy('proveedors.razon_social');


        $listados = ListaPrecio::select('proveedors.id AS proveedorId','proveedors.razon_social AS razon_social',
        'proveedors.cuit AS cuit', /*'proveedors.created_at AS alta',*/ //ListaPrecio::raw('min(lista_precios.created_at) AS creado'), 
        ListaPrecio::raw('count(lista_precios.id) AS prods'),
        ListaPrecio::raw('count(lista_precios.deleted_at) AS inactives'), ListaPrecio::raw('count(lista_precios.id) - count(lista_precios.deleted_at) AS actives'), 
        ListaPrecio::raw('CASE WHEN min(lista_precios.created_at) IS NULL THEN proveedors.created_at ELSE min(lista_precios.created_at) END AS creado'),
        ListaPrecio::raw('CASE WHEN max(lista_precios.updated_at) IS NULL THEN proveedors.created_at ELSE max(lista_precios.updated_at) END AS modificado'),
        'lista_precios.deleted_at'/*,ListaPrecio::raw('max(lista_precios.updated_at) AS modificado')*/)
            ->rightJoin('proveedors','lista_precios.proveedor_id','=','proveedors.id')
            ->whereNotNull('lista_precios.deleted_at')
            ->groupBy('proveedors.id','proveedors.cuit','proveedors.razon_social','lista_precios.proveedor_id', 'proveedors.created_at','lista_precios.deleted_at')
            ->orderBy('proveedors.razon_social')
            ->union($allListasDePrecios)
            ->get();
        return $listados;
    }

    // - Alta
    public static function findByRSCUIT($razon_social, $cuit) {
        $existe = Proveedor::where([['razon_social','=', $razon_social],['cuit','=', $cuit]])->count();
        return $existe;
    }
    public static function findByRS($razon_social) {
        $existe = Proveedor::where([['razon_social','=', $razon_social]])->count();
        return $existe;
    }
    public static function findByCUIT($cuit) {
        $existe = Proveedor::select('razon_social as RS')
            ->where('cuit','=',$cuit)
            ->get()->first();

        return $existe;
    }
    public static function getprovByRS($razon_social) {
        $existe = Proveedor::select('*')->where('razon_social','=',$razon_social)->get()->first();
        return $existe;
    }
    public static function getUbicacion($provincia_id, $localidad_id) {
        $existe = Localidad::select('localidades.nombre AS localidad','provincias.nombre AS provincia')
            ->join('provincias','localidades.provincia_id','=','provincias.id')
            ->where([['provincias.id','=',$provincia_id],['localidades.id','=',$localidad_id]])
            ->get()->first();
        return $existe;
    }
    public static function getProveedorId($razon_social){
        $existe = Proveedor::select('*')->where('razon_social','=',$razon_social)->get()->first();
        return $existe;
    }

    public static function proveedoresSinLista() {
        $proveedoresSinLista = Proveedor::select(Proveedor::raw('count(*) AS provs'))
            ->leftjoin('lista_precios','proveedors.id','lista_precios.proveedor_id')
            ->whereNull(['proveedors.deleted_at'])
            ->whereNull('lista_precios.proveedor_id')
            ->count();

        return $proveedoresSinLista;
    }
    public static function GetListaPreciosByProveedorId($proveedor_id) {
        $data = ListaPrecio::select('*')->where('proveedor_id','=', $proveedor_id);
        return $data;
    }
    // - RollBack
    public static function GetListadoUltimoEstado($proveedor_id) {
        $lastTime = ListaPrecio::select('deleted_at')
            ->onlyTrashed()
            ->where('proveedor_id','=', $proveedor_id)
            ->orderBy('deleted_at', 'desc')
            ->first();

        $data = ListaPrecio::select('id','producto_id','presentacion_id','proveedor_id','codigoProv','costo','created_at','updated_at',
        ListaPrecio::raw('MAX(deleted_at) AS deleted_at'))
            ->onlyTrashed()
            ->where('proveedor_id','=', $proveedor_id)
            ->where('deleted_at','=', $lastTime->deleted_at)
            ->groupBy('id','producto_id','presentacion_id','proveedor_id','codigoProv','costo','created_at','updated_at')
            ->get();
        return $data;
    }

    //SHOW
    public static function getListaDeProveedor($razon_social) {
        $listado = ListaPrecio::select(
            'lista_precios.id as listaId','lista_precios.proveedor_id','proveedors.razon_social','producto_id','presentacion_id',
            'codigoProv','droga', DB::raw('CONCAT(forma, ", ", presentacion) AS detalle'),'costo','lista_precios.updated_at')
            ->join('productos','lista_precios.producto_id','=','productos.id')
            ->join('presentacions','lista_precios.presentacion_id','=','presentacions.id')
            ->rightJoin('proveedors','lista_precios.proveedor_id','=','proveedors.id')
            ->where('proveedors.razon_social','=', $razon_social)
            ->get();
        return $listado;
    }
    // - Alta
    public static function findByProducto($proveedorId , $producto_id, $presentacion_id) {
        $prodcuto_lista =  ListaPrecio::where([['proveedor_id','=', $proveedorId],['producto_id','=', $producto_id],['presentacion_id','=', $presentacion_id]])->count();
        return $prodcuto_lista;
    }
    // - Baja
    public static function GetProductoListaPreciosByListaId($listaId) {
        $data = ListaPrecio::select('*')->where('id','=', $listaId);
        return $data;
    }



    public static function getIdListaPrecio($producto, $presentacion) {
        return DB::table('lista_precios')
            ->where('producto_id', $producto)
            ->where('presentacion_id', $presentacion)
            ->pluck('id');
    }

    public static function deletelistaDeProveedor($cuit) {
        $listaDeProveedor = DB::table('lista_precios')
        ->join('proveedors','lista_precios.proveedor_id','=','proveedors.id')
        ->where('proveedors.cuit', '=', $cuit)
        ->delete();
        return $listaDeProveedor;
    }

    public static function getItemLista($idItem) {
        $itemLista = ListaPrecio::select('lista_precios.id','producto.droga','presentacions.forma','presentacions.presentacion','lista_precio.codigoProv','lista_precio.costo')
        ->join('proveedors','lista_precios.proveedor_id','=','proveedors.id')
        ->join('productos','lista_precios.producto_id','=','productos.id')
        ->join('presentacions','lista_precios.presentacion_id','=','presentacions.id')
        ->where('lista_precios.id','=', $idItem)
        ->get();

        return $itemLista;
    }

    // COTIZACIONES
    public static function listarDescuentos($producto, $presentacion, $cotizacion) {

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
