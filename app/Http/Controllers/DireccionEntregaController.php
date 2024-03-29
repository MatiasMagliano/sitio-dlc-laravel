<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Administracion\CotizacionController;
use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\DireccionEntrega;
use App\Models\Localidad;
use App\Models\Provincia;
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

        $dde = DireccionEntrega::create($datosEntrega);
        $dde->save();

        $request->session()->flash('success', 'La dirección de entrega se ha creado con éxito.');
        return redirect(route('administracion.dde.index'));
    }

    public function edit(DireccionEntrega $dde)
    {
        $provincias = Provincia::all();
        $localidades = Localidad::all();
        return view('administracion.dde.edit', compact('dde', 'provincias', 'localidades'));
    }

    public function update(Request $request, DireccionEntrega $dde)
    {
        $datosEntrega = $request->validate([
            'lugar_entrega' => 'required|max:255',
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

        $dde->update($datosEntrega);

        $request->session()->flash('success', 'La dirección de entrega se ha actualizado con éxito.');
        return redirect(route('administracion.dde.index'));
    }

    public function destroy(DireccionEntrega $dde, Request $request)
    {
        // se debe tener en cuenta borrar las cotizaciones PENDIENTES, que estén asociadas a esta dde
        $cotizaciones = Cotizacion::where('id', $dde->id)->get();
        foreach($cotizaciones as $cotizacione)
        {
            if($cotizacione->estado_id == 1)
            {
                // TENER EN CUENTA QUE REDIRECCIONA --> REVISARRRRRRR!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                (new CotizacionController)->destroy($cotizacione, $request);
            }
        }

        $dde->delete();

        $request->session()->flash('success', 'La dirección de entrega se ha actualizado con éxito.');
        return redirect(route('administracion.dde.index'));
    }

    public function restaurar($dde, Request $request)
    {
        DireccionEntrega::where('id', $dde)->withTrashed()->restore();

        $request->session()->flash('success', 'La dirección de entrega se ha recuperado con éxito.');
        return redirect(route('administracion.dde.index'));
    }

    // envía un AJAX para popular un dt luego de seleccionar un cliente
    public function dtAjaxDde(Request $request){
        if ($request->ajax()) {
            $rta = DireccionEntrega::select(
                    'direcciones_entrega.id',
                    'direcciones_entrega.lugar_entrega',
                    'direcciones_entrega.domicilio',
                    'localidades.nombre AS localidad',
                    'provincias.nombre AS provincia',
                    'direcciones_entrega.condiciones', 'observaciones',
                    'direcciones_entrega.deleted_at',
                    )
                ->where('cliente_id', '=', $request->cliente_id)
                ->join('provincias', 'direcciones_entrega.provincia_id', '=', 'provincias.id')
                ->join('localidades', 'direcciones_entrega.localidad_id', '=', 'localidades.id')
                ->orderBy('direcciones_entrega.lugar_entrega')
                ->withTrashed()
                ->get();

            $json = array();

            foreach($rta as $direcciones)
            {
                $json[] = [
                    'borrado'           => view('administracion.dde.partials.borrada', ['dde' => $direcciones->id, 'borrada' => $direcciones->deleted_at])->render(),
                    'lugar_entrega'     => $direcciones->lugar_entrega,
                    'cantidad_enviado'  => $direcciones->cantidad_enviado,
                    'domicilio'         => $direcciones->domicilio,
                    'localidad'         => $direcciones->localidad,
                    'provincia'         => $direcciones->provincia,
                    'condiciones'       => $direcciones->condiciones,
                    'observaciones'     => $direcciones->observaciones,
                    'acciones'          => view('administracion.dde.partials.acciones', ['dde' => $direcciones->id, 'borrada' => $direcciones->deleted_at])->render(),
                ];
            }
            return response()->json($json);
        }
    }
}
