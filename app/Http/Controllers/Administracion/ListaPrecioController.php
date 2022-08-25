<?php

namespace App\Http\Controllers\Administracion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ListaPrecio;
use App\Exports\ListaPrecioExport;
use App\Models\Producto;
use App\Models\Presentacion;
use App\Models\Proveedor;
use Illuminate\Database\Eloquent\SoftDeletes;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;


class ListaPrecioController extends Controller
{

    use SoftDeletes;

    public function index()
    {
        $listaPrecios = ListaPrecio::select('lista_precios.proveedor_id','proveedors.cuit AS cuit','proveedors.razon_social AS razon_social',
        ListaPrecio::raw('count(lista_precios.id) AS prods') , ListaPrecio::raw('min(lista_precios.created_at) AS creado'), 
        ListaPrecio::raw('max(lista_precios.updated_at) AS modificado'))
            ->join('proveedors','lista_precios.proveedor_id','=','proveedors.id')
            ->groupBy('proveedors.cuit', 'proveedors.razon_social','lista_precios.proveedor_id')
            ->get();
        return view('administracion.listaprecios.index', compact('listaPrecios'));
    }

    public function create()
    {
        $productos = Producto::select('id', 'droga')->whereNull('deleted_at')->get();
        $presentaciones = Presentacion::select('id', 'forma', 'presentacion')->whereNull('deleted_at')->get();

        $proveedores = Proveedor::select('proveedors.id AS id', 'razon_social', 'cuit')
        ->leftjoin('lista_precios','proveedors.id','lista_precios.proveedor_id')
        ->whereNull(['proveedors.deleted_at'])
        ->whereNull('lista_precios.proveedor_id')
        ->get(); 

        $config = [
            'format' => 'DD/MM/YYYY',
            'dayViewHeaderFormat' => 'MMM YYYY'
        ];
        return view('administracion.listaprecios.create', compact('productos','presentaciones', 'proveedores'))->with('config', $config);
    }

    // public function mostrarLista(Request $request){
    //     if($request->ajax()){
    //         $listaPrecios = ListaPrecio::listaDeProveedor($request->proveedor_id);
    //         return Response()->json($listaPrecios);
    //     }
    // }

    public function deleteList(string $proveedor_id){
       
        ListaPrecio::where('proveedor_id','=',15)->delete();

    }

    public function addListadoProveedor(Request $request){
        
        $myarray = explode("|", $request->cadena);

        $save = new ListaPrecio;

        $save->proveedor_id = $myarray[0];
        $save->codigoProv = $myarray[1];
        $save->producto_id = $myarray[2];
        $save->presentacion_id = $myarray[3];
        $save->costo = $myarray[4];

        $save->save(); 
    }

    public function show(string $cuit)
    {
        $listaPrecios = ListaPrecio::listaDeProveedor($cuit);
        $proveedorListado = Proveedor::getDatosProveedor($cuit);
        return view('administracion.listaprecios.show', compact('listaPrecios', 'proveedorListado'));
    }

    public function editItemList(string $listaId)
    {
        $itemListaPrecio = ListaPrecio::getItemLista($listaId);
        return view('administracion.listaprecios.edit', compact('itemListaPrecio'));
    }

    // public function destroy(Request $request){
    //     if($request->ajax()){
    //         $ListaPrecios = ListaPrecio::destroy($request->id);
    //         return response()->json($ListaPrecios);
    //     }
    //     return response()->json(['mensaje' => 'No se encuentra la Lista de Precios del Proveedor'+$request->razon_social]);
    // }

    
    public function destroy(string $cuit)
    {
        //
    }

    // /**
    // * @return \Illuminate\Support\Collection
    // */
    // public function export(Request $prov_id){
    //     return Excel::download(new ListaPrecioExport($prov_id), 'lista_precios.xlsx');
    // }
    // public function exportlist(ListaPrecio $proveedor_id){
    //     return Excel::download(new ListaPrecioExport($proveedor_id), 'lista_precios.xlsx');
    // }


    public function exportlist(Request $request){
        $RS = $request->collect('search-rs');
        return Excel::download(new ListaPrecioExport($RS), 'ListadoPrecios.xlsx');
    }  

}
