<?php

namespace App\Http\Controllers\Administracion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ListaPrecio;
use App\Models\Producto;
use App\Models\Presentacion;
use App\Models\Proveedor;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
//use App\Exports\ListaPrecioExport;
//use Maatwebsite\Excel\Facades\Excel;


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

    /*public function show(string $listaPrecio)
    {
        $proveedor = Proveedor::getDatosProveedor($listaPrecio);
        return view('administracion.listaprecios.show', compact('listaPrecio','proveedor'));
    }

    public function loadDetalleListado(Request $request)
    {
        if ($request->ajax())
        {
            $listaPrecios = ListaPrecio::getListaDeProveedor("Hyatt and Sons");

            return response()->json(['success' => $listaPrecios]);
            //return response()->json($listaPrecios);
        }
    }*/
    
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

    public function editItemList(string $listaId)
    {
        $itemListaPrecio = ListaPrecio::getItemLista($listaId);
        return view('administracion.listaprecios.edit', compact('itemListaPrecio'));
    }
    
    public function destroy(string $proveedor_id, Request $request)
    {

        $data = ListaPrecio::deleteListaByProveedorId($proveedor_id);

        $request->session()->flash('success', 'Los productos del proveedor fueron borrados con éxito');
        return redirect()->route('administracion.listaprecios.index');
    }

    public function itemDestroy(string $razon_social, string $listaId, Request $request)
    {
        $data = ListaPrecio::deleteItemListaByListaId($listaId);

        $request->session()->flash('success', 'Los productos del proveedor fueron borrados con éxito');
        return redirect()->route('administracion.listaprecios.show', $razon_social);
    }
    
    public function editarProductoLista(Request $request)
    {
        if($request->ajax())
        {
            $producto_listaPrecio = ListaPrecio::find($request->producto);
            $respuesta = array(
                'producto_listaPrecio' => $producto_listaPrecio,
                'producto'  => Producto::find($producto_listaPrecio->producto_id),
                'presentacion' => Presentacion::find($producto_listaPrecio->presentacion_id),
            );

            return response()->json($respuesta);
        }
    }

    public function actualizarProductoLista(Request $request)
    {
        $producto_listaPrecio = ListaPrecio::find($request->listaId);
        $datos = $request->validate([
            'costo'  => 'required|numeric|min:0',
            'codigoProv'    => 'required|numeric|regex:/^\d*[0-9]+(?:\.[0-9]{1,2})?$/',
        ]);

        $producto_listaPrecio->update($datos);

        return response()->json(['success' => 'El producto se ha modificado con éxito.']);
    }

    /*public function exportlist(Request $request){
        $RS = $request->collect('search-rs');
        return Excel::download(new ListaPrecioExport($RS), 'ListadoPrecios.xlsx');
    }*/ 

}
