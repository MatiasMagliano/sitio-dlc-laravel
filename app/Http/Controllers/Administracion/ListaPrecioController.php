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
        $listaPrecios = ListaPrecio::getAllListasDePrecios();
        $proveedoresSinLista = ListaPrecio::proveedoresSinLista();
        $count = count($proveedoresSinLista);
        $withoutList = "LockCreate";
        if ($count > 0){ // Usar ajax
            $withoutList = "UnLockCreate";
        }
        return view('administracion.listaprecios.index', compact('listaPrecios','withoutList'));
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

    public function show(string $razon_social)
    {
        $listaPrecios = ListaPrecio::getListaDeProveedor($razon_social);
        $proveedor = Proveedor::getDatosProveedor($razon_social);
        return view('administracion.listaprecios.show', compact('listaPrecios', 'proveedor'));
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

    public function deleteList($proveedor_id){

        ListaPrecio::deleteListaByProveedorId($proveedor_id);

        return redirect()->route('listaprecios.index'); 
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

    
    public function destroy(string $proveedor_id, Request $request)
    {

        $data = ListaPrecio::deleteListaByProveedorId($proveedor_id);

        $request->session()->flash('success', 'Los productos del proveedor fueron borrados con éxito');
        return redirect()->route('administracion.listaprecios.index');
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
