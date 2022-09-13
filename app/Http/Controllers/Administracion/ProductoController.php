<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\ListaPrecio;
use App\Models\Lote;
use App\Models\Presentacion;
use App\Models\Producto;
use App\Models\Proveedor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Muestra la vista de productos (a golpe de vista -> presentaciones, lotes y proveedores).
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productos = Producto::sortable(['droga' => 'asc'])->paginate(5);
        return view('administracion.productos.index', compact('productos'));
        //return view('administracion.productos.index-dt');
    }

    public function ajaxdt(Request $request)
    {
        $search = $request->query('search', array('value' => '', 'regex' => false));
        $draw = $request->query('draw', 0);
        $start = $request->query('start', 0);
        $length = $request->query('length', 25);
        $order = $request->query('order', array(2, 'asc'));

        $filter = $search['value'];

        $sortColumns = array(
            0 => 'droga',
            1 => 'presentacion',
            2 => 'hosp-traz',
            3 => 'lotes',
            4 => 'proveedores',
            4 => 'existencia',
            5 => 'cotizacion',
            6 => 'disponible'
        );

        // QUERY COMPLETA DE COTIZACIONES
        $query = Producto::join('lote_presentacion_producto', 'lote_presentacion_producto.producto_id', '=', 'productos.id')
            ->join('presentacions', 'lote_presentacion_producto.presentacion_id', '=', 'presentacions.id')
            ->join('', '', '', '')
            ->whereIn('cotizacions.estado_id', [4, 5])
            ->select(
                'cotizacions.*',
                'clientes.razon_social',
                'cotizacions.created_at as creacion',
                'cotizacions.updated_at as modificacion',
                'estados.estado',
            );

        if (!empty($filter)) {
            $query->where('cotizacions.identificador', 'like', '%' . $filter . '%');
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

        $cotizaciones = $query->get();

        foreach ($cotizaciones as $cotizacion) {

            $json['data'][] = [
                $cotizacion->created_at,
                $cotizacion->updated_at,
                $cotizacion->identificador,
                $cotizacion->cliente->razon_social,
                '<span class="badge badge-secondary">'. $cotizacion->estado .'</span>',
                view('administracion.cotizaciones.partials.acciones', ['cotizacion' => $cotizacion])->render(),
            ];
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // EL ATTACH NO SE PUEDE HACER DE A UNO... HAY QUE COLOCAR TODOS LOS VALORES en una sola transacción

        // validación pura de Laravel
        $request->validate([
            'droga' => 'required|unique:productos|max:50',
        ]);

        $request->validate([
            'proveedor' => 'required',
            'presentacion' => 'required'
        ]);

        // creamos el modelo y se guarda al final de la transacción
        $producto = new Producto;
        $producto->droga = $request->droga;

        if(!$request->sin_lote){
            $hoy = Carbon::now()->format('d/m/Y');
            $request->validate([
                'identificador' => 'required|unique:lotes|max:20',
                'precio_compra' => 'required|numeric|max:5000',
                'cantidad' => 'required|numeric|min:1|max:50000',
                'fecha_elaboracion' => 'required|date_format:d/m/Y|before:'.$hoy,
                'fecha_compra' => 'required|date_format:d/m/Y|before:'.$hoy,
                'fecha_vencimiento' => 'required|date_format:d/m/Y|after:'.$hoy,
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


            // se crea una nueva relación con TODOS LOS DATOS y guarda el modelo producto
            $producto->save();
            // ACÁ MATI AGREGAR LA LÓGICA DE PROVEEDOR/LISTA
            $idProducto = Producto::select('id')->where('droga','=',$request->droga)->first();

            $listaDePrecios = new ListaPrecio;
            $listaDePrecios->producto_id = $idProducto->id;
            $listaDePrecios->presentacion_id = $request->presentacion;
            $listaDePrecios->proveedor_id = $request->proveedor;
            $listaDePrecios->codigoProv = $request->codigoProv;
            $listaDePrecios->costo = $request->precio_compra;

            $listaDePrecios->save();

            $request->session()->flash('success', 'El producto y su lote, se han creado con éxito');
            return redirect(route('administracion.productos.index'));
        }

        // se fuerza la relación pero SIN LOS DATOS DE LOTE y se guarda el modelo producto
        $producto->save();
        // $producto->presentaciones()->attach($request->presentacion, [
        //     'proveedor_id' => $request->proveedor,
        //     'lote_id' => '-1'
        // ]);

        $request->session()->flash('success', 'El producto SIN LOTE, se ha creado con éxito');
        return redirect(route('administracion.productos.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Productos  $productos
     * @return \Illuminate\Http\Response
     */
    public function show($producto_id, $presentacion_id)
    {
        //
        $producto = Producto::findOrFail($producto_id);
        return view('administracion.productos.show', compact('producto', 'presentacion_id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Productos  $productos
     * @return \Illuminate\Http\Response
     */
    public function edit(Producto $productos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductosRequest  $request
     * @param  \App\Models\Productos  $productos
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductoRequest $request, Producto $productos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Productos  $productos
     */
    public function destroy($id, Request $request)
    {
        // hace un softdelete sobre producto
        $borrado = Producto::destroy($id);

        if(!$borrado){
            $request->session()->flash('error', 'Ocurrió un error al intentar borrar el producto');
        }else{
            $request->session()->flash('success', 'El producto ha sido eliminado correctamente');
        }

        return redirect()->route('administracion.productos.index');
    }

    /**
     * Función que responde al ajax del modal de búsqueda
     */
    public function buscar(Request $request)
    {
        if ($request->has('droga')) {
            $rta = Producto::where('droga','like','%'.$request->input('droga').'%')->get();
            return response()->json($rta);
        }

        return response()->json(['droga' => 'No hay resultados']);
    }

    public function busqueda(Request $request)
    {
        $termino = $request->input('termino');
        if($termino != "")
        {
            $productos = Producto::where('droga','like','%'.$request->termino.'%')->paginate(10);

            // ESTA LÍNEA ES LA SALVADORA!!! Se utiliza para que los links de paginación no vuelvan al listado original
            $productos->appends(['termino' => $termino]);

            if(count($productos))
            {
                return view('administracion.productos.index', compact('productos'));
            }
            else
            {
                $request->session()->flash('error', 'La búsqueda no dio resultado.');
                return redirect(route('administracion.productos.index'));
            }
        }
    }
}
