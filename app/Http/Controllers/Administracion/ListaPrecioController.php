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

    //LISTA DE PROVEEDOR
    public function index() {
        //$withoutList = "LockCreate";
        $listaPrecios = ListaPrecio::getAllListasDePrecios();
        /*$proveedoresSinLista = ListaPrecio::proveedoresSinLista();
        if (!$proveedoresSinLista) {
            $withoutList = "UnLockCreate";
        }*/
    return view('administracion.listaprecios.index', compact('listaPrecios'/*,'withoutList'*/));
    }
    public function getListasVacias() {
        $proveedoresSinLista = ListaPrecio::proveedoresSinLista();
        return response()->json(['alert' => 'success','message' => $proveedoresSinLista]);
    }
    
    // - Alta
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
    // - Baja
    public function destroy(string $proveedor_id, Request $request) {
        $data = ListaPrecio::deleteListaByProveedorId($proveedor_id);
        $data->delete();
        $request->session()->flash('success', 'Los productos del proveedor fueron borrados con éxito');
        return redirect()->route('administracion.listaprecios.index');
    }

    public function addListadoProveedor(Request $request) {
        $myarray = explode("|", $request->cadena);
        $save = new ListaPrecio;

        $save->proveedor_id = $myarray[0];
        $save->codigoProv = $myarray[1];
        $save->producto_id = $myarray[2];
        $save->presentacion_id = $myarray[3];
        $save->costo = $myarray[4];

        $save->save(); 
        return response()->json(['alert' => 'success','message' => 'Listado creado con éxito.']);
    }

    //DETALLE DE LISTA DE PROVEEDOR
    public function show(string $razon_social) {
        $listaPrecios = ListaPrecio::getListaDeProveedor($razon_social);
        $proveedor = Proveedor::getDatosProveedor($razon_social);
        return view('administracion.listaprecios.show', compact('listaPrecios', 'proveedor'));
    }
    // - Alta
    public function agregarProductoLista() {   
        $jsonProductos = array('dataProductos' => []);
        $productos = DB::table('productos')->get();
        foreach($productos as $producto) {
            $jsonProductos['dataProductos'][] = [
                'productoId' => $producto->id,
                'droga' => $producto->droga
            ];
        }

        $jsonPresentaciones = array('dataPresentaciones' => []);
        $presentacions = DB::table('presentacions')->get();
        foreach($presentacions as $presentacion) {
            $jsonPresentaciones['dataPresentaciones'][] = [
                'presentacionId' => $presentacion->id,
                'presentacion' => $presentacion->forma. ', ' .$presentacion->presentacion
            ];
        }

        $jsonResponse = array('dataResponse' => []);
        $jsonResponse['dataResponse'][] = [
            'nombre' => $jsonProductos,
            'detalle' => $jsonPresentaciones
        ];

        return response()->json($jsonResponse);
    }
    public function ingresarProductoLista(Request $request) {
        $newProdLista = new ListaPrecio;
        $find_producto_listaPrecio = ListaPrecio::findByProducto($request->proveedor_id , $request->producto_id, $request->presentacion_id);

        if(!$find_producto_listaPrecio){
            $datos = $request->validate([
                'producto_id' => 'required',
                'presentacion_id' => 'required',
                'costo'  => 'required|numeric|min:0',
                'codigoProv'    => 'required|numeric|regex:/^\d*[0-9]+(?:\.[0-9]{1,2})?$/',
            ]);
    
            $newProdLista = new ListaPrecio($request->all());
            $newProdLista->save();

            return response()->json(['alert' => 'success','message' => 'El producto se ha modificado con éxito.']);
            
        }else{
            return response()->json(['alert' => 'warning','message' => 'El producto ya se encuentra en el listado del proveedor.']);
        }
    }
    // - Baja
    public function itemDestroy(string $razon_social, string $listaId, Request $request) {
        $data = ListaPrecio::deleteItemListaByListaId($listaId);

        $request->session()->flash('success', 'El producto del proveedor fue quitado de la lísta con éxito');
        return redirect()->route('administracion.listaprecios.show', $razon_social);
    }
    // - Modificación
    public function editarProductoLista(Request $request) {
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
    public function actualizarProductoLista(Request $request) {
        $producto_listaPrecio = ListaPrecio::find($request->listaId);
        $datos = $request->validate([
            'costo'  => 'required|numeric|min:0',
        ]);

        $producto_listaPrecio->update($datos);

        return response()->json(['success' => 'El producto se ha modificado con éxito.']);
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
    
    /*public function editItemList(string $listaId)
    {
        $itemListaPrecio = ListaPrecio::getItemLista($listaId);
        return view('administracion.listaprecios.edit', compact('itemListaPrecio'));
    }*/
    
    /*public function exportlist(Request $request){
        $RS = $request->collect('search-rs');
        return Excel::download(new ListaPrecioExport($RS), 'ListadoPrecios.xlsx');
    }*/ 

}
