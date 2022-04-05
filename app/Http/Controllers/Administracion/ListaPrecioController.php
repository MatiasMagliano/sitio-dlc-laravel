<?php

namespace App\Http\Controllers\Administracion;
use App\Http\Controllers\Controller;
use App\Models\ListaPrecio;
use App\Models\Proveedor;
use App\Models\Presentacion;
use App\Models\Producto;
use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ListaPrecioExport;
use App\Imports\ListaPrecioImport;

class ListaPrecioController extends Controller
{

    public function exportExcel(){
        return Excel::download(new ListaPrecioExport, 'ListadoDePrecios.xlsx');
    }

    public function importExcel(Request $request){
        $file = $request->file('file');
        Excel::import(new ListaPrecioImport, $file);

        return back()->with('message', 'Importación de listado completada');
    }



    /**
     * Devuelve una vista para agregar lotes a un producto
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listaPrecios = ListaPrecio::all();
        $proveedors = Proveedor::all();
        $presentaciones = Presentacion::all();
        $producto = Producto::all();
        $config = [
             'format' => 'DD/MM/YYYY',
             'dayViewHeaderFormat' => 'MMM YYYY',
             'minDate' => "js:moment().startOf('month')",
        ];

        return view('administracion.listaprecios.index', compact('listaPrecios', 'proveedors', 'presentaciones'));
    }

     

    public function actualizarLista(Request $request)
    {
        if($request->ajax()){
            $where = array('proveedor_id' => $request->idProveedor);
	        $listaPrecios = ListaPrecio::where($where)->get();
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
