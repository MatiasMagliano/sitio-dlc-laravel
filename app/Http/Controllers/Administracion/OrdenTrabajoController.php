<?php

namespace App\Http\Controllers\Administracion;
use App\Http\Controllers\Controller;
use App\Models\Cotizacion;
use App\Models\DepositoCasaCentral;
use App\Models\Lote;
use App\Models\LotePresentacionProducto;
use App\Models\OrdenTrabajo;
use App\Models\ProductoOrdenTrabajo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrdenTrabajoController extends Controller
{
    public function index()
    {
        // Estado 6: aprobada, modificando orden de trabajo
        $ordenes_potenciales = Cotizacion::where('estado_id', 4)->get();
        $ordenes = OrdenTrabajo::all();

        return view('administracion.ordenestrabajo.index', compact('ordenes_potenciales', 'ordenes'));
    }

    public function obtenerLineasCotizacion(Request $request)
    {
        if($request->ajax())
        {
            $lineas = DB::table('producto_cotizados')
                ->select('producto_cotizados.id AS cotizado_id', 'productos.droga', 'presentacions.forma', 'presentacions.presentacion')
                ->join('productos', 'producto_cotizados.producto_id', '=', 'productos.id')
                ->join('presentacions', 'producto_cotizados.presentacion_id', '=', 'presentacions.id')
                ->where('producto_cotizados.cotizacion_id', $request->cotizacion_id)
                ->get();

            return Response()->json($lineas);
        }
    }

    public function show(OrdenTrabajo $ordentrabajo)
    {
        $cotizacion = Cotizacion::find($ordentrabajo->cotizacion_id);
        return view('administracion.ordenestrabajo.show', compact('ordentrabajo', 'cotizacion'));
    }

    public function store(Request $request)
    {
        // Se buscan los productos tildados
        $productos = DB::table('producto_cotizados')
            ->whereIn('id', $request->lineasOrdenTrabajo)
            ->get();

        $cotizacion = Cotizacion::findOrFail($productos[0]->cotizacion_id);

        // elección del estado de acuerdo a la cantidad de productos
        if ($cotizacion->productos->count() == $productos->count())
        {
            $estado = 6;
        }
        else{
            $estado = 7;
        }

        // Se cambia el estado de la cotización a Aprobada + generando OT
        $cotizacion->estado_id = $estado;
        $cotizacion->save(); //se actualiza la cotización

        // Se agregan los datos necesarios en el request para generar
        // la OT y los productos que dependen de ella
        $request->request->add([
            'cotizacion_id' => $productos[0]->cotizacion_id,
            'user_id'       => Auth::user()->id,
            'estado_id'     => $estado,
            'en_produccion' => Carbon::now(),
        ]);

        $orden = new OrdenTrabajo($request->all());
        $orden->save();

        foreach($productos as $producto)
        {
            $deposito = DepositoCasaCentral::find(
                LotePresentacionProducto::getIdDeposito($producto->producto_id, $producto->presentacion_id) //devuelve un solo pivot relacionado a prod/pres
            );
            // lógica que genera un array->tostring de lotes disponibles o asigna -1 cuando no hay lotes
            if($deposito->disponible > 0)
            {
                $lotes = array();
                $lotesDeProducto = LotePresentacionProducto::getLotes($producto->producto_id, $producto->presentacion_id);
                $resto = $producto->cantidad;
                $cont  = 0;
                while($resto >= 0)
                {
                    $cantLote = Lote::find($lotesDeProducto[$cont]);
                    $resto -= $cantLote->cantidad;
                    $lotes[] = $cantLote->id; //array_push
                }

                ProductoOrdenTrabajo::create([
                    'orden_trabajo_id'  => $orden->id,
                    'producto_id'       => $producto->producto_id,
                    'presentacion_id'   => $producto->presentacion_id,
                    'lotes'             => implode(', ', $lotes),
                    'cantidad'          => $producto->cantidad,
                ]);
            }
            else
            {
                ProductoOrdenTrabajo::create([
                    'orden_trabajo_id'  => $orden->id,
                    'producto_id'       => $producto->producto_id,
                    'presentacion_id'   => $producto->presentacion_id,
                    'lotes'             => -1,
                    'cantidad'          => $producto->cantidad,
                ]);
            }
        }

        $request->session()->flash('success', 'La orden de trabajo se creó con éxito. Ahora puede asignarle lotes disponibles.');
        return redirect(route('administracion.ordentrabajo.index'));
    }
}
