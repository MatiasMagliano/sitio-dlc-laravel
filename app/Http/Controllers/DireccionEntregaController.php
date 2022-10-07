<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\DireccionEntrega;
use Illuminate\Http\Request;

class DireccionEntregaController extends Controller
{
    public function index()
    {
        $clientes = Cliente::all();
        return view('administracion.dde.index', compact('clientes'));
    }

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
