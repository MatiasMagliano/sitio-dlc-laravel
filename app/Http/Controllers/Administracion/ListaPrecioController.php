<?php

namespace App\Http\Controllers\Administracion;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\ListaPrecio;
use App\Models\Proveedor;

use App\Exports\ListaPrecioExport;
use App\Exports\ArchivoPrimarioExport;
use Maatwebsite\Excel\Facades\Excel;



class ListaPrecioController extends Controller
{


    // public function importExcel(Request $request){
    //     $file = $request->file('file');
    //     Excel::import(new ListaPrecioImport, $file);

    //     return back()->with('message', 'Importación de listado completada');
    // }


    /**
     * Devuelve una vista para agregar lotes a un producto
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listaPrecios = ListaPrecio::all();
        $proveedors = Proveedor::all();

        $users = DB::table('lista_precios')
            ->join('proveedors','lista_precios.proveedor_id','=','proveedors.id')
            ->join('lote_presentacion_producto', 'lista_precios.lpp_id','=','lote_presentacion_producto.id')
            ->join('productos','lote_presentacion_producto.producto_id','=','productos.id')
            ->join('presentacions','lote_presentacion_producto.presentacion_id','=','presentacions.id')
            ->select('lista_precios.*','productos.droga','presentacions.presentacion','proveedors.razon_social')
            ->get();
   
        return view('administracion.listaprecios.index', compact('listaPrecios','users','proveedors'));
    }

    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function actualizarLista(Request $request){

        if($request->ajax()){
            $listaPrecios = ListaPrecio::listaDeProveedor($request->proveedor_id);
            return Response()->json($listaPrecios);
        }
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function destroy(Request $request){
         if($request->ajax()){
              $ListaPrecios = ListaPrecio::destroy($request->id);
              return response()->json($ListaPrecios);
          }
          return response()->json(['mensaje' => 'No se encuentra la Lista de Ppecios del Proveedor'+$request->razon_social]);
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
