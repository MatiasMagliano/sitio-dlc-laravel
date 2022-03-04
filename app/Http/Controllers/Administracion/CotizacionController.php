<?php

namespace App\Http\Controllers\Administracion;

use App\Models\Cotizacion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cliente;
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
        $existente = Cotizacion::where('cliente_id', $request->get('cliente'))
            ->where('identificador', $request->get('identificador'))
            ->where('finalizada', null)->get();

        // esto hay que verlo en el analisis, ANALIZAR SI EXISTE LA POSIBILIDAD DE COTIZAR DOS COTIZACIONES
        if($existente->count())
        {
            $request->session()->flash('error', 'Ya existe una cotización para este cliente sin finalizar con este identificador. <a href="'.route('sales.show', $existente->first()).'">Click aquí para verla.</a>');
            return back();
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
        return view('administracion.cotizaciones.show', compact('cotizacion'));
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

    public function editarProductoCotizado(Cotizacion $cotizacion, ProductoCotizado $productoCotizado)
    {
        //EDICION DE LA PRESENTACION COTIZADA
    }

    public function guardarProductoCotizado(Request $request, Cotizacion $cotizacion, ProductoCotizado $productoCotizado)
    {
        //GUARDADO DEL PRODUCTO COTIZADO

        // se obtienen en un arrary
        // $producto[0]: producto_id
        // $producto[1]: presentacion_id
        $producto = $request->get('producto');
        $producto_ids = explode('|', $producto); //esta función separa y guarda en un array
    }

    public function actualizarProductoCotizado(Request $request, Cotizacion $cotizacion, ProductoCotizado $productoCotizado)
    {
        //ACTUALIZADO DEL PRODUCTO COTIZADO
    }

    public function borrarProductoCotizado(Cotizacion $cotizacion, ProductoCotizado $productoCotizado)
    {
        //BORRADO DEL PRODUCTO COTIZADO
    }
}
