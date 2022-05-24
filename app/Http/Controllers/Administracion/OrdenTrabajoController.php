<?php

namespace App\Http\Controllers\Administracion;
use App\Http\Controllers\Controller;
use App\Models\Cotizacion;
use App\Models\DepositoCasaCentral;
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

    public function store(Request $request, ProductoOrdenTrabajo $productoOrdenTrabajo)
    {
        $productos = DB::table('producto_cotizados')
            ->whereIn('id', $request->lineasOrdenTrabajo)
            ->get();

        // se agregan los datos necesarios en el request para generar la orden de trabajo
        $request->request->add([
            'cotizacion_id' => $productos[0]->cotizacion_id,
            'user_id'       => Auth::user()->id,
            'estado_id'     => 6,
            'en_produccion' => Carbon::now(),
        ]);

        $orden = new OrdenTrabajo($request->all());
        $orden->save();

        foreach($productos as $producto)
        {
            $deposito = DepositoCasaCentral::find(
                LotePresentacionProducto::getIdDeposito($producto->producto_id, $producto->presentacion_id) //devuelve un solo pivot relacionado a prod/pres
            );

            if($deposito->cotizado > 0)
            {
                $productoOrdenTrabajo->create([
                    'orden_trabajo_id'  => $orden->id,
                    'producto_id'       => $producto->producto_id,
                    'presentacion_id'   => $producto->presentacion_id,
                    'lotes'             => '', // lógica necesaria que asignaría los lotes disponibles
                ]);
            }
            else
            {
                $productoOrdenTrabajo->create([
                    'orden_trabajo_id'  => $orden->id,
                    'producto_id'       => $producto->producto_id,
                    'presentacion_id'   => $producto->presentacion_id,
                    'lotes'             => -1
                ]);
            }
        }
    }
}
