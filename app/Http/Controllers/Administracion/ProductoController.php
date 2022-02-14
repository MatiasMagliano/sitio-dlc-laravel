<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Lote;
use App\Models\Presentacion;
use App\Models\Producto;
use App\Models\Proveedor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Muestra la vista de productos (a golpe de vista).
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productos = Producto::all();
        return view('administracion.productos.index', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $presentaciones = Presentacion::all();
        $proveedores = Proveedor::all();
        $config = [
            'format' => 'DD/MM/YYYY',
            'dayViewHeaderFormat' => 'MMM YYYY',
            'minDate' => "js:moment().startOf('month')",
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
        // validación pura de Laravel
        $request->validate([
            'droga' => 'required|unique:productos|max:50',
        ]);

        $request->validate([
            'proveedor' => 'required',
            'presentacion' => 'required'
        ]);

        $hoy = Carbon::now()->format('d/m/Y');
        $request->validate([
            'identificador' => 'required|unique:lotes|max:20',
            'precioCompra' => 'required|numeric|max:5000',
            'cantidad' => 'required|numeric|min:1|max:50000',
            'vencimiento' => 'required|date_format:d/m/Y|after:'.$hoy,
        ]);

        $lote = new Lote;
        $lote->identificador = $request->identificador;
        $lote->precioCompra = $request->precioCompra;
        $lote->cantidad = $request->cantidad;
        $lote->desde = Carbon::now()->format('Y-m-d H:i:s');
        $lote->hasta = Carbon::createFromFormat('d/m/Y', $request->vencimiento);

        $producto = Producto::create([
            'droga' => $request->droga,
        ]);
        $producto->proveedores()->attach($request->proveedor);
        $producto->presentaciones()->attach($request->presentacion);
        $producto->lotes()->save($lote);

        $request->session()->flash('success', 'El producto se ha creado con éxito');
        return redirect(route('administracion.productos.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Productos  $productos
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $producto = Producto::findOrFail($id);
        return view('administracion.productos.show', compact('producto'));
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
    public function destroy(Request $request)
    {
        // hace un softdelete sobre producto y redirecciona al index de productos
        if($request->ajax()){
            Producto::destroy($request->id);

            $request->session()->flash('success', 'El producto ha sido eliminado correctamente');

            return response()->json(['redireccion' => route('administracion.productos.index')]);
        }
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
}
