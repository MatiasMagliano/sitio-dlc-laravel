<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\DireccionEntrega;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DireccionEntregaController extends Controller
{
    public function index()
    {
        $clientes = Cliente::orderBy('razon_social')->get();
        return view('administracion.dde.index', compact('clientes'));
    }

    public function create()
    {
        $clientes = Cliente::orderBy('razon_social')->get();
        $provincias = DB::table('provincias')->select('id', 'nombre')->get();
        $localidades = DB::table('localidades')->select('id', 'nombre')->get();
        return view('administracion.dde.create', compact('clientes', 'provincias', 'localidades'));
    }

    public function store(Request $request)
    {
        $datosEntrega = $request->validate([
            'lugar_entrega' => 'unique:direcciones_entrega|required|max:255',
            'domicilio'     => 'required|max:255',
            'provincia_id'  => 'required|integer',
            'localidad_id'  => 'required|integer',
        ]);

        $datosEntrega = array_merge($datosEntrega, array(
            'cliente_id' => $request->cliente_id,
            'mas_entregado' => 0,
            'condiciones' => $request->condiciones,
            'observaciones' => $request->observaciones
        ));

        //dd($datosEntrega);

        $dde = DireccionEntrega::create($datosEntrega);
        $dde->save();

        $request->session()->flash('success', 'La dirección de entrega se ha creado con éxito.');
        return redirect(route('administracion.dde.index'));
    }

    // envía un AJAX para popular un dt luego de seleccionar un cliente
    public function dtAjaxDde(Request $request){
        if ($request->ajax()) {
            $rta = DireccionEntrega::select(
                    'lugar_entrega',
                    'domicilio',
                    'localidades.nombre AS localidad',
                    'provincias.nombre AS provincia',
                    'condiciones', 'observaciones',
                    'mas_entregado AS cantidad_enviado'
                    )
                ->where('cliente_id', '=', $request->cliente_id)
                ->join('provincias', 'direcciones_entrega.provincia_id', '=', 'provincias.id')
                ->join('localidades', 'direcciones_entrega.localidad_id', '=', 'localidades.id')
                ->orderBy('direcciones_entrega.lugar_entrega')
                ->get();
            return response()->json($rta);
        }

        return response()->json(['droga' => 'No hay resultados']);
    }
}
