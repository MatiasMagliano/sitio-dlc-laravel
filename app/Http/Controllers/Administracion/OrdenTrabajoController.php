<?php

namespace App\Http\Controllers\Administracion;
use App\Http\Controllers\Controller;
use App\Models\Cotizacion;
use App\Models\DepositoCasaCentral;
use App\Models\Estado;
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
        // Estado 4: aprobada
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

        // Se agregan los datos necesarios en el request para generar
        // la OT y los productos que dependen de ella
        $request->request->add([
            'cotizacion_id' => $productos[0]->cotizacion_id,
            'user_id'       => Auth::user()->id,
            'estado_id'     => 4,
            'en_produccion' => Carbon::now(),
        ]);

        $orden = new OrdenTrabajo($request->all());
        $orden->save(); // para crearla en la BD

        foreach($productos as $producto)
        {
            $deposito = DepositoCasaCentral::find(
                LotePresentacionProducto::getIdDeposito($producto->producto_id, $producto->presentacion_id) //devuelve un solo pivot relacionado a prod/pres
            );
            // Si hay lotes disponibles, el proceso termina con la impresión de la OT
            // de lo contrario, se deberán asignar manualmente los lotes una vez arquiridos.
            // Lógica que genera un array->tostring de lotes disponibles o asigna -1 cuando no hay lotes
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

        if(OrdenTrabajo::LotesCompletos($orden->id))
        {
            $estado = 6;
            $request->session()->flash('success', 'La orden de trabajo se creó con éxito. Estará disponible para imprimir desde el panel inferior.');
        }
        else
        {
            $estado = 7;
            $request->session()->flash('warning', 'La orden de trabajo se creó con éxito, pero con <strong>lotes incompletos</strong>. Deberá agregarlos manualmente una vez adquiridos.');
        }
        $cotizacion->estado_id = $estado;
        $orden->estado_id = $estado;
        $cotizacion->save();
        $orden->save();
        return redirect(route('administracion.ordentrabajo.index'));
    }

    public function descargapdf()
    {
        return "IMRESION DE ORDEN DE TRABAJO";
    }
}
