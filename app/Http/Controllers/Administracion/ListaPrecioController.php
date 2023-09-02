<?php

namespace App\Http\Controllers\Administracion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\ListaPrecio;
use App\Models\Producto;
use App\Models\Presentacion;
use App\Models\Proveedor;
use App\Rules\ValidacionAfip;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Hamcrest\Core\HasToString;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ListaPrecioController extends Controller
{

    //LISTA DE PROVEEDOR
    public function index() {
        $listaPrecios = ListaPrecio::getAllListasDePrecios();
        return view('administracion.listaprecios.index', compact('listaPrecios'));
        //return view('administracion.listaprecios.index');
    }

    public function ajaxdt(Request $request)
    {
        // RESPONDE UN JSON PARA POPULAR EL SERVERSIDE-DATATABLE
        $search = $request->query('search', array('value' => '', 'regex' => false));
        $draw = $request->query('draw', 0);
        $start = $request->query('start', 0);
        $length = $request->query('length', 25);
        $order = $request->query('order', array(0, 'desc'));

        // busqueda general
        $filter = $search['value'];
        // busqueda individual
        $GLOBALS['b_columna'] = Arr::except($request->query('columns'), array('_token', '_method'));

        $sortColumns = array(
            0 => 'proveedors.razon_social',
            1 => 'alta',
            2 => 'modificado'
        ); //Columnas con buscarores

        // QUERY COMPLETA DE COTIZACIONES
        $query = ListaPrecio::select(
            'lista_precios.proveedor_id',
            'proveedors.razon_social AS razon_social',
            'proveedors.cuit AS cuit', 
            'proveedors.created_at AS alta', 
            ListaPrecio::raw('count(lista_precios.id) AS prods'), 
            ListaPrecio::raw('min(lista_precios.created_at) AS creado'), 
            ListaPrecio::raw('max(lista_precios.updated_at) AS modificado')
            )->whereNull('proveedors.deleted_at');
            
            
        $query->rightJoin('proveedors','lista_precios.proveedor_id','=','proveedors.id')
                ->groupBy('proveedors.cuit','proveedors.razon_social','lista_precios.proveedor_id', 'proveedors.created_at')
                ->orderBy('proveedors.razon_social')
                ->get();

        $query->rightJoin('proveedors', function($join){
            $join->on('lista_precios.proveedor_id', '=', 'proveedors.id')
                ->where(function($query){
                    $b_columna = $GLOBALS['b_columna'];
                    if(isset($b_columna[0]['search']['value'])) {
                        $query = $query->where('proveedors.razon_social', 'like', '%'.$b_columna[0]['search']['value'].'%');
                    }
                });
        });

        $query->rightJoin('proveedors', function($join){
            $join->on('lista_precios.proveedor_id', '=', 'proveedors.id')
                ->where(function($query){
                    $b_columna = $GLOBALS['b_columna'];
                    if(isset($b_columna[1]['search']['value'])) {
                        $query = $query->where('creado', 'like', '%'.$b_columna[1]['search']['value'].'%');
                    }
                });
        });

        $query->rightJoin('proveedors', function($join){
            $join->on('lista_precios.proveedor_id', '=', 'proveedors.id')
                ->where(function($query){
                    $b_columna = $GLOBALS['b_columna'];
                    if(isset($b_columna[2]['search']['value'])) {
                        $query = $query->where('modificado', 'like', '%'.$b_columna[2]['search']['value'].'%');
                    }
                });
        });

        //filtro particular
        $b_columna = $GLOBALS['b_columna'];
        if(isset($b_columna[1]['search']['value'])) {
            $query->where('identificador', 'like', '%'.$b_columna[1]['search']['value'].'%');
        }

        $recordsTotal = $query->count();

        $sortColumnName = $sortColumns[$order[0]['column']];

        $query->orderBy($sortColumnName, $order[0]['dir'])
            ->take($length)
            ->skip($start);

        $json = array(
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => [],
        );

        $listado = $query->get();

        foreach ($listado as $lista) {
            $json['data'][] = [
                view('administracion.listaprecio.partials.razon_social', ['lista' => $lista])->render(),
                view('administracion.listaprecio.partials.creado', ['lista' => $lista])->render(),
                view('administracion.listaprecio.partials.modificado', ['lista' => $lista])->render(),
                view('administracion.listaprecio.partials.acciones', ['lista' => $lista])->render(),
            ];
        }

        return $json;
    }

    public function AgregarListadoPreciosProveedor() {
        $productos = Producto::select('id','droga')->get();
        $presentaciones = Presentacion::select('id','forma','presentacion','hospitalario','trazabilidad','divisible')->get();
        $provincias = DB::table('provincias')->select('id', 'nombre')->get();
        $localidades = DB::table('localidades')->select('id', 'nombre')->get();
        return view('administracion.listaprecios.alta', compact('productos','presentaciones','provincias','localidades'));
    }
    
    // - Alta
    public function NuevoListadoPrecioProveedor(Request $request) {
        // se valida que los campos estén presentes
        $datosProveedor = $request->validate([
            'razon_social' => 'required',
            'cuit' => 'required',
            'email' => 'required',
            'web' => 'max:255',
            'domicilio' => 'required',
            'provincia_id' => 'required',
            'localidad_id' => 'required'
        ]);

        $existe = ListaPrecio::findByRSCUIT($request->razon_social, $request->cuit);

        if ($existe) {
            return response()->json(['alert' => 'warning','message' => 'El proveedor que intenta cargar ya existe.']);
        }else {
            $existeRS = ListaPrecio::findByRS($request->razon_social);
            if ($existeRS) {
                return response()->json(['alert' => 'warning','message' => 'Ya existe un proveedor con esta razon social.']);
            }else {
                $existeCUIT = ListaPrecio::findByCUIT($request->cuit);
                if ($existeCUIT) {
                    return response()->json(['alert' => 'warning','message' => 'Ya existe el proveedor: '. $existeCUIT->RS .', con ese CUIT dado de alta.']);
                }else {
                    $ubicacion = ListaPrecio::getUbicacion($request->provincia_id, $request->localidad_id);

                    $proveedor = new Proveedor;
        
                    $proveedor->razon_social = $request->razon_social;
                    $proveedor->cuit = $request->cuit;
                    $proveedor->contacto = $request->email;
                    $proveedor->url =  $request->web;
                    $proveedor->direccion =  $request->domicilio. ', ' .$ubicacion->localidad. ', ' .$ubicacion->provincia;
                    
                    $proveedor->save();

                    return response()->json(['alert' => 'success','message' => 'El alta de proveedor se ejecutó correctamente. Ahora será redirigido a la página principal']);
                    //return redirect()->route('administracion.listaprecios.editar', ['razon_social' => $request->razon_social]);
                    //return redirect()->route('administracion.listaprecios.editar', ['razon_social' => 'hg65hdnyt-yu']);
                    //$this->show(strval($request->razon_social));
                    //return redirect()->route('administracion.listaprecios.editar', $request->razon_social);
                }
            }
        }
    }
    public function create(){
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
    public function VaciarListado(string $proveedor_id/*, Request $request*/) {
        $data = ListaPrecio::GetListaPreciosByProveedorId($proveedor_id);
        $data->delete();
        //$request->session()->flash('success', 'Se ha vaciado el listado de precios del proveedor con éxito');
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
    // - RollBack
    public function VolverListado(string $proveedor_id/*, Request $request*/) {
        $data = ListaPrecio::GetListadoUltimoEstado($proveedor_id);
        foreach($data as $item) {
            $item->deleted_at = NULL;
            $item->save();
        }
        //return $data;
        return redirect()->route('administracion.listaprecios.index');
    }


    //DETALLE DE LISTA DE PROVEEDOR
    public function MostrarListado(string $razon_social) {
        $listaPrecios = ListaPrecio::getListaDeProveedor($razon_social);
        $proveedor = Proveedor::getDatosProveedor($razon_social);
        return view('administracion.listaprecios.editar', compact('listaPrecios', 'proveedor'));
    }

    // - Alta
    public function TraerDataAgregarProductoLista() {   
        $jsonProductos = array('dataProductos' => []);
        $productos = DB::table('productos')->get();
        foreach($productos as $producto) {
            $jsonProductos['dataProductos'][] = [
                'productoId' => $producto->id,
                'droga' => $producto->droga
            ];
        }

        $jsonPresentaciones = array('dataPresentaciones' => []);
        $presentacions = ListaPrecio::AllPresentacionsToPost();
        //$presentacions = DB::table('presentacions')->get();
        foreach($presentacions as $presentacion) {
            $jsonPresentaciones['dataPresentaciones'][] = [
                'presentacionId' => $presentacion->id,
                'presentacion' => $presentacion->forma. ', ' .$presentacion->presentacion. $presentacion->Inp
            ];
        }

        $jsonResponse = array('dataResponse' => []);
        $jsonResponse['dataResponse'][] = [
            'nombre' => $jsonProductos,
            'detalle' => $jsonPresentaciones
        ];
            
        return response()->json($jsonResponse);
    }
    public function TraerDataAgregarProductoLista1() {   
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

    public function IngresarProductoLista(Request $request) {
        $rs = Proveedor::find($request->proveedor_id);
        $existeCodigo = ListaPrecio::findCodigoProv($request->codigoProv);
        if($existeCodigo == 0){
            $existeProducto = ListaPrecio::findByProducto($request->proveedor_id , $request->producto_id, $request->presentacion_id);
            if($existeProducto == 0){
                $newProdLista = new ListaPrecio;
                $newProdLista->producto_id = $request->producto_id;
                $newProdLista->presentacion_id = $request->presentacion_id;
                $newProdLista->proveedor_id = $request->proveedor_id;
                $newProdLista->codigoProv = $request->codigoProv;
                $newProdLista->costo = $request->costo;
                
                $newProdLista->save();
                
                $request->session()->flash('success', 'El producto se ha ingresado con éxito.');
                return redirect(route('administracion.listaprecios.editar', ['razon_social' => $rs->razon_social]));
                
            }else{
                $request->session()->flash('warning', 'El producto ya se encuentra en el listado del proveedor.');
                return redirect(route('administracion.listaprecios.editar', ['razon_social' => $rs->razon_social]));
            }
            
        }else{
            $request->session()->flash('warning', 'Ya existe un producto con el mismo código de proveedor.');
            return redirect(route('administracion.listaprecios.editar', ['razon_social' => $rs->razon_social]));
        }
    }

    // - Baja
    public function QuitarProductoLista(string $razon_social, string $listaId/*, Request $request*/) {
        $data = ListaPrecio::GetProductoListaPreciosByListaId($listaId);
        $data->delete();
        //$request->session()->flash('success', 'El producto del proveedor fue quitado de la lísta con éxito');
        return redirect()->route('administracion.listaprecios.editar', $razon_social);
    }

    // - Modificación
    public function TraerDataModificarProductoLista(Request $request) {
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
    public function ActualizarProductoLista(Request $request) {
        $producto_listaPrecio = ListaPrecio::find($request->listaId);
        $cliente = Proveedor::find($producto_listaPrecio->proveedor_id);
        $producto_listaPrecio->costo = $request->costo;
        $producto_listaPrecio->save();

        $request->session()->flash('success', 'El producto se ha modificado con éxito.');
        return redirect(route('administracion.listaprecios.editar', ['razon_social' => $cliente->razon_social]));
    }

    /*public function getListasVacias() {
        $proveedoresSinLista = ListaPrecio::proveedoresSinLista();
        return response()->json(['alert' => 'success','message' => $proveedoresSinLista]);
    }*/
    /*public function new(Request $request) {
        // se valida que los campos estén presentes
        $datosProveedor = $request->validate([
            'razon_social' => 'required|unique:razon_social',
        ]);

        $existe = Proveedor::where('razon_social', $request->get('razon_social'))->where('cuit', $request->get('tipo_afip'))->get();
        $existeRS = Proveedor::where('razon_social', $request->get('razon_social'))->get();
        $existeCUIT = Proveedor::where('cuit', $request->get('tipo_afip'))->get();

        if ($existe->count()) {
            return response()->json(['alert' => 'warning','message' => 'El proveedor que intenta cargar ya existe.']);
        }else if($existeRS->count()){
            return response()->json(['alert' => 'warning','message' => 'Ya existe un proveedor con esta razon social.']);
        }else if($existeCUIT->count()){
            return response()->json(['alert' => 'warning','message' => 'Ya existe un proveedor con esta con ese CUIT.']);
        }else{

            $proveedor= Proveedor::create($datosProveedor);

            $request->session()->flash('success', 'Proveedor registrado con éxito. Ahora puede agregar los productos a us listado de precios.');
                return redirect()->route('administracion.listaprecios.show', ['proveedor' => $proveedor->razon_social]);
        }
    }*/
    /*public function show(string $listaPrecio) {
        $proveedor = Proveedor::getDatosProveedor($listaPrecio);
        return view('administracion.listaprecios.show', compact('listaPrecio','proveedor'));
    }
    public function loadDetalleListado(Request $request) {
        if ($request->ajax())
        {
            $listaPrecios = ListaPrecio::getListaDeProveedor("Hyatt and Sons");

            return response()->json(['success' => $listaPrecios]);
            //return response()->json($listaPrecios);
        }
    }*/
    /*public function editItemList(string $listaId) {
        $itemListaPrecio = ListaPrecio::getItemLista($listaId);
        return view('administracion.listaprecios.edit', compact('itemListaPrecio'));
    }*/
    /*public function exportlist(Request $request) {
        $RS = $request->collect('search-rs');
        return Excel::download(new ListaPrecioExport($RS), 'ListadoPrecios.xlsx');
    }*/ 
}
