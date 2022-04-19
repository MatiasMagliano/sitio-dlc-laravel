<?php

namespace App\Http\Controllers\Administracion;

use App\Models\Cotizacion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Presentacion;
use App\Models\Producto;
use App\Models\ProductoCotizado;

class CotizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cotizaciones = Cotizacion::latest()->get();
        return view('administracion.cotizaciones.index', compact('cotizaciones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clientes = Cliente::all();
        return view('administracion.cotizaciones.create', compact('clientes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // se valida que los campos estén presentes
        $request -> validate([
            'identificador' => 'required|max:50',
            'cliente_id' => 'required'
        ]);
        // Se valida que no haya una misma validación con el mismo identificador al mismo cliente
        $existente = Cotizacion::where('cliente_id', $request->get('cliente'))
            ->where('identificador', $request->get('identificador'))
            ->where('finalizada', null)->get();

        if($existente->count())
        {
            $request->session()->flash('error', 'Ya existe este identificador en una cotización sin finalizar. <a href="'.route('administracion.cotizaciones.show', $existente->first()).'">Haga click aquí para verla.</a>');
            return redirect()->route('administracion.cotizaciones.index');
        }

        $request->request->add(['estado' => 'Agregando líneas']);
        $cotizacion = new Cotizacion($request->all());
        $cotizacion->save();

        $request->session()->flash('success', 'Cotización registrada con éxito. Agregue líneas a la cotización.');
        return redirect()->route('administracion.cotizaciones.show', ['cotizacione' => $cotizacion->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cotizacion  $cotizacion
     * @return \Illuminate\Http\Response
     */
    public function show(Cotizacion $cotizacione)
    {
        $cotizacion = $cotizacione;
        $presentaciones = Presentacion::all();
        return view('administracion.cotizaciones.show', compact('cotizacion', 'presentaciones'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cotizacion  $cotizacion
     * @return \Illuminate\Http\Response
     */
    public function edit(Cotizacion $cotizacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cotizacion  $cotizacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cotizacion $cotizacion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cotizacion  $cotizacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cotizacion $cotizacion)
    {
        //
    }


    // MÉTODOS ESPECIALES
    public function finalizar(Cotizacion $cotizacion)
    {
        //FINALIZAR COTIZACION
    }

    public function agregarProducto(Cotizacion $cotizacion)
    {
        //AGREGAR PRESENTACION A COTIZACION
        $productos = Producto::all();

        return view('administracion.cotizaciones.agregarProducto', compact('cotizacion', 'productos'));
    }

    // AJAX QUE OBTIENE LOS PRECIOS SUGERIDOS
    public function preciosSugeridos(Request $request)
    {
        if($request->ajax()){
            // la lógica de la función sería obtener todos los precios para esa presentación
            // por ahora, se envía la misma lista para todos los productos
            $sugerencias = [];

            return response()->json($sugerencias);
        }
    }

    public function editarProductoCotizado(Cotizacion $cotizacion, ProductoCotizado $productoCotizado)
    {
        //EDICION DE LA PRESENTACION COTIZADA
    }

    public function guardarProductoCotizado(Request $request, Cotizacion $cotizacion, ProductoCotizado $productoCotizado)
    {
        //GUARDADO DEL PRODUCTO COTIZADO
        $request -> validate([
            'producto' => 'required',
            'precio' => 'required',
            'cantidad' => 'required'
        ]);

        // el request->producto se desdobla en dos:
        // $producto_ids[0]: producto_id
        // $producto_ids[1]: presentacion_id
        $producto = $request->get('producto');
        $producto_ids = explode('|', $producto); //esta función separa y guarda en un array

        //se prepara el request para guardarlo completo en la bbdd
        $request->request->add([
            'producto_id' => $producto_ids[0],
            'presentacion_id' => $producto_ids[1]
        ]);
        $request->merge([
            'total' => $request->get('precio') * $request->get('cantidad')
        ]);

        //dd($request);
        $productoCotizado->create($request->all());

        $request->session()->flash('success', 'Producto agregado con éxito.');
        return redirect()
            ->route('administracion.cotizaciones.show', ['cotizacione' => $cotizacion]);
    }

    public function actualizarProductoCotizado(Request $request, Cotizacion $cotizacion, ProductoCotizado $productoCotizado)
    {
        //ACTUALIZADO DEL PRODUCTO COTIZADO
    }

    public function borrarProductoCotizado(Request $request, Cotizacion $cotizacion, ProductoCotizado $productoCotizado)
    {
        //BORRADO DEL PRODUCTO COTIZADO
        $productoCotizado->delete();

        $request->session()->flash('success', 'Producto agregado con éxito.');
        return response()->json();
    }
}
