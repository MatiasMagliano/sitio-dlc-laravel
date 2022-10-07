<?php

namespace App\Http\Controllers\Administracion;

use App\Models\Cliente;
use App\Models\DireccionEntrega;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EsquemaPrecio;
use App\Rules\ValidacionAfip;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $clientes = Cliente::
            with(array('dde' => function($query){
                $query->orderBy('mas_entregado', 'desc');
            }))
            ->get();
        // dd($clientes);
        return view('administracion.clientes.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $provincias = DB::table('provincias')->select('id', 'nombre')->get();
        $localidades = DB::table('localidades')->select('id', 'nombre')->get();
        return view('administracion.clientes.create', compact('provincias', 'localidades'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validación de los datos del cliente
        $datosCliente = $request->validate([
            'nombre_corto'  => 'unique:clientes|required|max:30',
            'razon_social'  => 'required|max:255',
            'tipo_afip'     => 'required',
            'afip'          => ['unique:clientes','required', new ValidacionAfip],
            'contacto'      => 'required|max:50',
            'telefono'      => 'required|string|max:20',
            'email'         => 'required|email|max:50',
            'lugar_entrega' => 'required|max:255',
            'domicilio'     => 'required|max:255',
            'provincia_id'  => 'required|integer',
            'localidad_id'  => 'required|integer',
        ]);

        $cliente = Cliente::create($datosCliente);

        $nuevaEntrega = new DireccionEntrega;
        $nuevaEntrega->cliente_id = $cliente->id;
        $nuevaEntrega->lugar_entrega = $request->lugar_entrega;
        $nuevaEntrega->domicilio = $request->domicilio;
        $nuevaEntrega->provincia_id = $request->provincia_id;
        $nuevaEntrega->localidad_id = $request->localidad_id;
        $nuevaEntrega->condiciones = $request->condiciones;
        $nuevaEntrega->observaciones = $request->observaciones;
        $nuevaEntrega->save();

        $nuevoEsquema = new EsquemaPrecio;
        $nuevoEsquema->cliente_id = $cliente->id;
        $nuevoEsquema->porcentaje_1 = $request->esquema[0];
        $nuevoEsquema->porcentaje_2 = $request->esquema[1];
        $nuevoEsquema->porcentaje_3 = $request->esquema[2];
        $nuevoEsquema->porcentaje_4 = $request->esquema[3];
        $nuevoEsquema->porcentaje_5 = $request->esquema[4];
        $nuevoEsquema->save();

        $request->session()->flash('success', 'El registro de cliente se ha creado con éxito.');
        return redirect(route('administracion.clientes.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit(Cliente $cliente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cliente $cliente)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
        //
    }

    public function obtenerLocalidades(Request $request){
        if($request->ajax()){
            $localidades = DB::table('localidades')
                ->select('localidades.id AS value', 'localidades.nombre AS text')
                ->where('provincia_id', '=', $request->provincia_id)
                ->orderBy('text', 'ASC')
                ->get();
            return Response()->json($localidades);
        }
    }

    public function obtenerDde(Request $request){
        if($request->ajax()){
            $localidades = DB::table('direcciones_entrega')
                ->select('direcciones_entrega.id AS value', 'direcciones_entrega.lugar_entrega AS text')
                ->where('cliente_id', '=', $request->cliente_id)
                ->orderBy('text', 'ASC')
                ->get();
            return Response()->json($localidades);
        }
    }

    public function obtenerPuntosEntrega(Request $request){
        if($request->ajax()){
            $ptsEntrega = DB::table('direcciones_entrega')
                ->select(
                    'direcciones_entrega.lugar_entrega',
                    'direcciones_entrega.domicilio',
                    'provincias.nombre AS provincia',
                    'localidades.nombre AS localidad',
                    'direcciones_entrega.condiciones',
                    'direcciones_entrega.observaciones'
                    )
                ->join('provincias', 'provincias.id', '=', 'direcciones_entrega.provincia_id')
                ->join('localidades', 'localidades.id', '=', 'direcciones_entrega.localidad_id')
                ->where('cliente_id', '=', $request->cliente_id)
                ->orderBy('lugar_entrega', 'ASC')
                ->get();
            return Response()->json($ptsEntrega);
        }
    }
}
