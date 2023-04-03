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

    public function create()
    {
        //
        $provincias = DB::table('provincias')->select('id', 'nombre')->get();
        $localidades = DB::table('localidades')->select('id', 'nombre')->get();
        return view('administracion.clientes.create', compact('provincias', 'localidades'));
    }

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
        $nuevaEntrega->mas_entregado = 0;
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

    public function show(Cliente $cliente)
    {
        //
    }

    public function edit(Cliente $cliente)
    {
        return view('administracion.clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $datosCliente = $request->validate([
            'nombre_corto'  => 'required|max:30',
            'razon_social'  => 'required|max:255',
            'contacto'      => 'required|max:50',
            'telefono'      => 'required|string|max:20',
            'email'         => 'required|email|max:50',
        ]);
        $datosEsquema = $request->validate([
            'esquema[0]'    => 'regex:/^\d*[0-9]+(?:\.[0-9]{1,2})?$/',
            'esquema[1]'    => 'regex:/^\d*[0-9]+(?:\.[0-9]{1,2})?$/',
            'esquema[2]'    => 'regex:/^\d*[0-9]+(?:\.[0-9]{1,2})?$/',
            'esquema[3]'    => 'regex:/^\d*[0-9]+(?:\.[0-9]{1,2})?$/',
            'esquema[4]'    => 'regex:/^\d*[0-9]+(?:\.[0-9]{1,2})?$/'
        ]);

        $cliente->update($datosCliente);
        $cliente->esquemaPrecio->porcentaje_1 = $request->esquema[0];
        $cliente->esquemaPrecio->porcentaje_2 = $request->esquema[1];
        $cliente->esquemaPrecio->porcentaje_3 = $request->esquema[2];
        $cliente->esquemaPrecio->porcentaje_4 = $request->esquema[3];
        $cliente->esquemaPrecio->porcentaje_5 = $request->esquema[4];

        $cliente->save();
        $cliente->esquemaPrecio->save();

        $request->session()->flash('success', 'El registro de cliente se ha modificado con éxito.');
        return redirect(route('administracion.clientes.index'));
    }

    public function destroy(Request $request, Cliente $cliente)
    {
        $cliente->delete();

        $request->session()->flash('success', 'El registro de cliente y todas sus cotizaciones pendientes se han borrado con éxito.');
        return redirect(route('administracion.clientes.index'));
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
            $dde = DB::table('direcciones_entrega')
                ->select('direcciones_entrega.id AS value', 'direcciones_entrega.lugar_entrega AS text')
                ->where('cliente_id', '=', $request->cliente_id)
                ->orderBy('text', 'ASC')
                ->get();
            return Response()->json($dde);
        }
    }
}
