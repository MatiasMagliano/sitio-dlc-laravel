<?php

namespace App\Http\Controllers\Administracion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ListaPrecio;
use App\Exports\ListaPrecioExport;
use App\Models\Producto;
use App\Models\Presentacion;
use App\Models\Proveedor;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;



class ListaPrecioController extends Controller
{

    public function index()
    {
        $listaPrecios = ListaPrecio::select('proveedors.cuit AS cuit','proveedors.razon_social AS razon_social', 
        ListaPrecio::raw('count(lista_precios.id) AS prods') , ListaPrecio::raw('min(lista_precios.created_at) AS creado'), 
        ListaPrecio::raw('max(lista_precios.updated_at) AS modificado'))
            ->join('proveedors','lista_precios.proveedor_id','=','proveedors.id')
            ->groupBy('proveedors.cuit', 'proveedors.razon_social')
            ->get();

        // $proveedors = Proveedor::all();

        // $users = DB::table('lista_precios')
        //     ->join('proveedors','lista_precios.proveedor_id','=','proveedors.id')
        //     ->join('productos','lista_precios.producto_id','=','productos.id')
        //     ->join('presentacions','lista_precios.presentacion_id','=','presentacions.id')
        //     ->select('lista_precios.*','productos.droga','presentacions.presentacion','proveedors.razon_social')
        //     ->get();
            
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

    public function mostrarLista(Request $request){
        if($request->ajax()){
            $listaPrecios = ListaPrecio::listaDeProveedor($request->proveedor_id);
            return Response()->json($listaPrecios);
        }
    }

    public function addListadoProveedor(Request $request){
        if($request->ajax()){
            $addLista = new ListaPrecio();

            $addLista->producto_id = $request('producto_id');
            $addLista->presentacion_id = $request('presentacion_id');
            $addLista->codigoProv = $request('codigoProv');
            $addLista->costo = $request('costo');

            $addLista->save();

            
        }
    }

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
