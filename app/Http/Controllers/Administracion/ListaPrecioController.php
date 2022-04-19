<?php

namespace App\Http\Controllers\Administracion;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\ListaPrecio;
use App\Models\Proveedor;

use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ListaPrecioImport;

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


    public function actualizarLista(Request $request){

        if($request->ajax()){
            $listaPrecios = ListaPrecio::listaDeProveedor($request->proveedor_id);
            return Response()->json($listaPrecios);
        }
    }




    public function destroy(Request $request){
         if($request->ajax()){
              $ListaPrecios = ListaPrecio::destroy($request->id);


              return response()->json($ListaPrecios);
          }
          return response()->json(['mensaje' => 'No se encuentra la Lista de PRecios del Proveedor'+$request->id]);
     }
}
