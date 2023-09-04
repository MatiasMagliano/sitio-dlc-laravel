<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Cotizacion;
use App\Models\DepositoCasaCentral;
use App\Models\Lote;
use App\Models\LotePresentacionProducto;
use App\Models\OrdenTrabajo;
use App\Models\Producto;
use App\Models\ProductoCotizado;
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
        if ($request->ajax()) {
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
        $productos = ProductoCotizado::where('cotizacion_id', $request->cotizacion_id)
            ->where('no_aprobado', 0)
            ->get();
        $cotizacion = Cotizacion::findOrFail($request->cotizacion_id);

        $orden_trabajo = new OrdenTrabajo([
            'cotizacion_id' => $request->cotizacion_id,
            'user_id'       => Auth()->user()->id,
            'plazo_entrega' => Carbon::parse($request->plazo_entrega),
            'observaciones' => $request->observaciones,
            'en_produccion' => Carbon::now(),
            'estado_id'     => 4 //estado provisorio
        ]);

        $orden_trabajo->save();

        $productos_ot = [];

        $lotes_completos = false;
        $estado = 6;

        foreach ($productos as $producto) {
            $producto_ot = [];
            $lotes_asignados = [];

            $producto_ot['orden_trabajo_id'] = $orden_trabajo->id;
            $producto_ot['producto_id'] = $producto->producto_id;
            $producto_ot['presentacion_id'] = $producto->presentacion_id;

            $cantidad = $producto->cantidad;
            $lotes = Lote::lotesPorPresentacion($producto->producto_id, $producto->presentacion_id);
            $i_lote = 0;

            while ($cantidad > 0 && $i_lote < count($lotes)) {
                if ($lotes[$i_lote]->cantidad >= $cantidad) {
                    // cuando el lote llega a cubrir la cantidad requerida
                    $lotes_asignados[] = [
                        'indentificador' => $lotes[$i_lote]->identificador,
                        'cantidad'       => $cantidad
                    ];
                    $cantidad -= $lotes[$i_lote]->cantidad;
                    $lotes_completos = true;
                    $estado = 6;
                    // aquí va rutina que descuenta las cantidades en la tabla real LOTES
                } else {
                    // cuando el lote no llega a cubrir la cantidad requerida
                    $lotes_completos = false;
                    $estado = 7;
                }
                $i_lote += 1;
            }

            $producto_ot['lotes'] = json_encode($lotes_asignados);

            // Agregar el producto al arreglo de productos
            $productos_ot[] = $producto_ot;
        }

        // se limpia todo y se guardan los registros
        $orden_trabajo->lotes_completos = $lotes_completos;
        $orden_trabajo->estado_id = $estado;
        $orden_trabajo->save();

        try
        {
            ProductoOrdenTrabajo::insert($productos_ot);

            if($estado == 6)
            {
                $request->session()->flash('success', 'La orden de trabajo se creó con éxito. Estará disponible para imprimir desde el panel inferior.');
            }
            elseif($estado == 7)
            {
                $request->session()->flash('warning', 'La orden de trabajo se creó con éxito, pero con <strong>lotes incompletos</strong>. Deberá agregarlos manualmente una vez adquiridos y cargado en el sistema.');
            }

            return redirect(route('administracion.ordentrabajo.index'));

        }
        catch (\Illuminate\Database\QueryException $e) {
            dd($e->getMessage());
        }
    }

    public function descargapdf()
    {
        return "IMRESION DE ORDEN DE TRABAJO";
    }
}
