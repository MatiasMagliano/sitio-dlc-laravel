<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\DepositoCasaCentral;
use App\Models\ListaPrecio;
use App\Models\Lote;
use App\Models\LotePresentacionProducto;
use App\Models\Presentacion;
use App\Models\Producto;
use App\Models\Proveedor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    /**
     * Muestra la vista de productos (a golpe de vista -> presentaciones, lotes y proveedores).
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $productos = Producto::sortable(['droga' => 'asc'])->paginate(5);
        // return view('administracion.productos.index', compact('productos'));
        return view('administracion.productos.index-dt');
    }

    public function ajaxdt(Request $request)
    {
        $search = $request->query('search', array('value' => '', 'regex' => false));
        $draw = $request->query('draw', 0);
        $start = $request->query('start', 0);
        $length = $request->query('length', 25);
        $order = $request->query('order', array(0, 'asc'));

        $filter = $search['value'];

        $sortColumns = array(
            0 => 'droga',
            1 => 'presentacion',
            2 => 'hosp-traz',
            3 => 'lotes',
            4 => 'dcc',
            8 => 'acciones'
        );

        // QUERY COMPLETA DE PRODUCTOS
        $query = Producto::select('*')
            ->with(
                array(
                    'presentacion' => function($query)
                    {
                        $query->select(
                                'presentacions.id',
                                'presentacions.forma',
                                'presentacions.presentacion',
                                'presentacions.hospitalario',
                                'presentacions.trazabilidad'
                            )
                            ->groupBy([
                                'presentacions.id',
                                'presentacions.forma',
                                'presentacions.presentacion',
                                'presentacions.hospitalario',
                                'presentacions.trazabilidad'
                            ])->with(
                                array(
                                    'lote' => function($query)
                                    {
                                        $query->select('*')
                                            /*->wherePivotIn('producto_id', ARRAY DE PRODUCTOS )*/;
                                    },
                                    'dcc' => function($query){
                                        $query->select(
                                            'existencia',
                                            'cotizacion',
                                            'disponible'
                                        )->groupBy([
                                            'existencia',
                                            'cotizacion',
                                            'disponible',
                                            'deposito_casa_centrals.id',
                                            'deposito_casa_centrals.existencia',
                                            'deposito_casa_centrals.cotizacion',
                                            'deposito_casa_centrals.disponible',
                                            'deposito_casa_centrals.created_at',
                                            'deposito_casa_centrals.updated_at',
                                            'lote_presentacion_producto.producto_id',
                                            'lote_presentacion_producto.presentacion_id',
                                            'lote_presentacion_producto.dcc_id',
                                        ]);
                                    }
                                )
                            );
                    }
                )
            );

        if (!empty($filter)) {
            $query->where('droga', 'like', '%' . $filter . '%');
        }

        $recordsTotal = $query->count();

        $sortColumnName = $sortColumns[$order[0]['column']];

        // se detecta el length -1
        if($length == '-1')
        {
            $query->orderBy($sortColumnName, $order[0]['dir']);
        }
        else
        {
            $query->orderBy($sortColumnName, $order[0]['dir'])
                ->take($length)
                ->skip($start);
        }

        $json = array(
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => [],
        );

        $productos = $query->get();

        foreach ($productos as $producto)
        {
            foreach ($producto->presentacion as $presentacion)
            {
                $json['data'][] = [
                    $producto->droga,
                    view('administracion.productos.partials.presentaciones', ['presentacion' => $presentacion])->render(),
                    view('administracion.productos.partials.lotes', ['presentacion' => $presentacion])->render(),
                    view('administracion.productos.partials.stock', ['presentacion' => $presentacion])->render(),
                    view('administracion.productos.partials.acciones', ['producto' => $producto, 'presentacion' => $presentacion])->render(),
                ];
            }
        }

        return $json;
    }

    public function create()
    {
        $presentaciones = Presentacion::select('id', 'forma', 'presentacion')->orderby('forma', 'ASC')->get();
        $proveedores = Proveedor::select('id', 'razon_social', 'cuit')->orderby('razon_social', 'ASC')->get();
        $config = [
            'format' => 'DD/MM/YYYY',
            'dayViewHeaderFormat' => 'MMM YYYY'
        ];
        return view('administracion.productos.crear', compact('presentaciones', 'proveedores'))->with('config', $config);
    }

    public function store(Request $request)
    {
        // EL ATTACH NO SE PUEDE HACER DE A UNO... HAY QUE COLOCAR TODOS LOS VALORES en una sola transacción

        // validación pura de Laravel
        $request->validate([
            'droga' => 'required|unique:productos|max:50',
        ]);

        $request->validate([
            'proveedor' => 'required',
            'codigoProv' => 'required',
            'presentacion' => 'required'
        ]);

        // creamos el modelo y se guarda al final de la transacción
        $producto = new Producto;
        $producto->droga = $request->droga;

        if (!$request->sin_lote) {
            $hoy = Carbon::now()->format('d/m/Y');
            $mañana = Carbon::now()->addDay()->format('d/m/Y');
            $request->validate([
                'identificador' => 'required|unique:lotes|max:20',
                'precio_compra' => 'required|numeric|max:5000',
                'cantidad' => 'required|numeric|min:1|max:50000',
                'fecha_elaboracion' => 'required|date_format:d/m/Y|before:' . $hoy,
                'fecha_compra' => 'required|date_format:d/m/Y|before:' . $mañana,
                'fecha_vencimiento' => 'required|date_format:d/m/Y|after:' . $hoy,
            ]);

            // se genera el lote
            $lote = new Lote;
            $lote->identificador = $request->identificador;
            $lote->precio_compra = $request->precio_compra;
            $lote->cantidad = $request->cantidad;
            $lote->fecha_elaboracion = Carbon::createFromFormat('d/m/Y', $request->fecha_elaboracion);
            $lote->fecha_compra = Carbon::createFromFormat('d/m/Y', $request->fecha_compra);
            $lote->fecha_vencimiento = Carbon::createFromFormat('d/m/Y', $request->fecha_vencimiento);
            $lote->save();

            // se crea un nuevo depósito dcc
            $deposito = DepositoCasaCentral::create([
                'existencia' => $lote->cantidad,
                'cotizacion' => 0,
                'disponible' => $lote->cantidad
            ]);
            $deposito->save();

            // se crea una nueva relación con TODOS LOS DATOS en LotePresentacionProducto (el pivot)
            $producto->save();
            $lpp = LotePresentacionProducto::create([
                'producto_id'     => $producto->id,
                'presentacion_id' => $request->presentacion,
                'lote_id'         => $lote->id,
                'dcc_id'          => $deposito->id
            ]);
            $lpp->save();

            $listaDePrecios = new ListaPrecio;
            $listaDePrecios->producto_id = $producto->id;
            $listaDePrecios->presentacion_id = $request->presentacion;
            $listaDePrecios->proveedor_id = $request->proveedor;
            $listaDePrecios->codigoProv = $request->codigoProv;
            $listaDePrecios->costo = $request->precio_compra;

            $listaDePrecios->save();

            $request->session()->flash('success', 'El producto y su lote, se han creado con éxito');
            return redirect(route('administracion.productos.index'));
        }
        else
        {
            // se fuerza la relación pero SIN LOS DATOS DE LOTE y se guarda el modelo producto
            // se crea un nuevo depósito dcc
            $deposito = DepositoCasaCentral::create([
                'existencia' => 0,
                'cotizacion' => 0,
                'disponible' => 0
            ]);
            $deposito->save();

            // se crea una nueva relación con TODOS LOS DATOS en LotePresentacionProducto (el pivot)
            $producto->save();
            $lpp = LotePresentacionProducto::create([
                'producto_id'     => $producto->id,
                'presentacion_id' => $request->presentacion,
                'lote_id'         => -1,
                'dcc_id'          => $deposito->id
            ]);
            $lpp->save();

            $request->session()->flash('success', 'El producto SIN LOTE, se ha creado con éxito');
            return redirect(route('administracion.productos.index'));
        }
    }

    public function show($producto_id, $presentacion_id)
    {
        //
        $producto = Producto::findOrFail($producto_id);
        return view('administracion.productos.show', compact('producto', 'presentacion_id'));
    }

    public function edit(Producto $producto, Presentacion $presentacion)
    {
        $presentaciones = Presentacion::all();
        $proveedor = Producto::proveedores($producto->id, $presentacion->id);
        $proveedores = Proveedor::all();
        return view('administracion.productos.edit', compact('producto', 'presentacion', 'presentaciones', 'proveedor', 'proveedores'));
    }

    public function update(UpdateProductoRequest $request, Producto $productos)
    {
        //
    }

    public function destroy($id, Request $request)
    {
        // hace un softdelete sobre producto
        $borrado = Producto::destroy($id);

        if (!$borrado) {
            $request->session()->flash('error', 'Ocurrió un error al intentar borrar el producto');
        } else {
            $request->session()->flash('success', 'El producto ha sido eliminado correctamente');
        }

        return redirect()->route('administracion.productos.index');
    }

    // Ajax responde dt de proveedores en vistas de edición de producto
    public function ajaxProveedores(Request $request){
        if($request->ajax()){
            $proveedor = Producto::proveedores($request->producto, $request->presentacion);
            return response()->json($proveedor);
        }
    }

    // Ajax que guarda un nuevo proveedor a un producto
    public function ajaxNuevoPorveedor(Request $request){
        if($request->ajax()){

            // validación pura de Laravel
            $request->validate([
                'codigo_proveedor' => 'required|max:50',
            ]);

            ListaPrecio::create([
                'codigoProv' => $request->codigo_proveedor,
                'producto_id' => $request->producto,
                'presentacion_id' => $request->presentacion,
                'proveedor_id' => $request->proveedor,
                'costo' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            return response()->json(['success' => 'Se agregó nuevo proveedor']);
        }
    }
}
