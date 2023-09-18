<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Cotizacion;
use App\Models\Lote;
use App\Models\OrdenTrabajo;
use App\Models\ProductoCotizado;
use App\Models\ProductoOrdenTrabajo;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
            'plazo_entrega' => Carbon::createFromFormat('d/m/Y H:i', $request->plazo_entrega)->format('Y-m-d H:i:s'),
            'observaciones' => $request->observacion,
            'en_produccion' => Carbon::now(),
            'estado_id'     => 4 //estado provisorio
        ]);

        $orden_trabajo->save();

        $productos_ot = [];
        $ot_con_lotes_incompletos = false;

        foreach ($productos as $producto) {
            $producto_ot = [];
            $lotes_asignados = [];

            $producto_ot['orden_trabajo_id'] = $orden_trabajo->id;
            $producto_ot['producto_id'] = $producto->producto_id;
            $producto_ot['presentacion_id'] = $producto->presentacion_id;

            $cantidad_requerida = $producto->cantidad;
            $lotes = Lote::lotesPorPresentacion($producto->producto_id, $producto->presentacion_id);
            $lotesIndex = 0;

            while ($cantidad_requerida > 0 && $lotesIndex < count($lotes)) {
                $lote = $lotes[$lotesIndex];
                $cantidad_disponible = $lote->cantidad;

                if($cantidad_disponible > 0)
                {
                    if ($cantidad_requerida >= $cantidad_disponible) {
                        // Se toma todo el lote
                        $lotes_asignados[] = [
                            'id' => $lote->id,
                            'identificador' => $lote->identificador,
                            'cantidad' => $cantidad_disponible,
                        ];

                        Lote::restarCantidad($lote->id, $cantidad_disponible); // Restar cantidad en la base de datos

                        $cantidad_requerida -= $cantidad_disponible;
                    } else {
                        // Se toma parte del lote
                        $lotes_asignados[] = [
                            'id' => $lote->id,
                            'identificador' => $lote->identificador,
                            'cantidad' => $cantidad_requerida,
                        ];

                        Lote::restarCantidad($lote->id, $cantidad_requerida); // Restar cantidad en la base de datos

                        $cantidad_requerida = 0;
                    }
                }

                $lotesIndex++;
            }
            $producto_ot['lotes'] = json_encode($lotes_asignados);
            $producto_ot['l_incompleto'] = $cantidad_requerida > 0;

            if($cantidad_requerida > 0)
            {
                $ot_con_lotes_incompletos = true;
            }

            // Agregar el producto al arreglo de productos
            $productos_ot[] = $producto_ot;
        }

        // se limpia todo y se guardan los registros
        $orden_trabajo->lotes_completos = $cantidad_requerida > 0;

        if($ot_con_lotes_incompletos)
        {
            $orden_trabajo->estado_id = $cotizacion->estado_id = 7; // EN PRODUCCIÓN CON LOTES INCOMPLETOS
        }
        else
        {
            $orden_trabajo->estado_id = $cotizacion->estado_id = 6; // EN PRODUCCIÓN CON LOTES COMPLETOS
        }
        $orden_trabajo->save();
        $cotizacion->save(); // se cambia el estado de la cotización, para que no aparezca en la lista de potenciales OTs

        try {
            ProductoOrdenTrabajo::insert($productos_ot);

            if ($orden_trabajo->estado_id == 6)
            {
                $request->session()->flash('success', 'La orden de trabajo se creó con éxito. Estará disponible para imprimir desde el panel inferior.');
            }
            elseif ($$orden_trabajo->estado_id == 7)
            {
                $request->session()->flash('warning', 'La orden de trabajo se creó con éxito, pero con <strong>lotes incompletos</strong>. Deberá agregarlos manualmente una vez adquiridos y cargado en el sistema.');
            }

            return redirect(route('administracion.ordentrabajo.index'));
        } catch (\Illuminate\Database\QueryException $e) {
            dd($e->getMessage());
        }
    }

    public function generarPickingList(OrdenTrabajo $ordentrabajo)
    {
        $productos = $ordentrabajo->productos;
        $prod_ordentrabajo = [];

        foreach ($productos as $producto) {
            $arrlotes = json_decode($producto->lotes, true);

            // Verificar si hay lotes asignados para este producto
            if (empty($arrlotes)) {
                // No hay lotes asignados para este producto, en este caso se sólo se listan los proveedores del producto
                $query = '
                SELECT
                    lp.codigoProv AS COD_PROV,
                    p.razon_social AS PROVEEDOR,
                    CONCAT(pro.droga, " - ", pre.forma, ", ", pre.presentacion) AS PRODUCTO,
                    pot.l_incompleto AS L_INCOMP
                FROM lote_presentacion_producto lpp
                INNER JOIN productos pro ON pro.id = lpp.producto_id
                INNER JOIN presentacions pre ON pre.id = lpp.presentacion_id
                INNER JOIN lotes l ON l.id = lpp.lote_id
                INNER JOIN lista_precios lp ON lp.producto_id = lpp.producto_id AND lp.presentacion_id = lpp.presentacion_id
                INNER JOIN producto_orden_trabajos pot ON pot.producto_id = lpp.producto_id AND pot.presentacion_id = lpp.presentacion_id
                INNER JOIN proveedors p ON lp.proveedor_id = p.id';
                $reordenados = DB::select($query);

                foreach ($reordenados as $prod) {
                    $prod_key = $prod->PRODUCTO;

                    if (!isset($prod_ordentrabajo[$prod_key])) {
                        $prod_ordentrabajo[$prod_key] = [];
                    }

                    $prod_ordentrabajo[$prod_key]['PROVEEDORES'][] = [
                        'COD_PROV' => $prod->COD_PROV,
                        'PROVEEDOR' => $prod->PROVEEDOR,
                    ];

                    $prod_ordentrabajo[$prod_key]['LOTES'] = [
                        'identificador' => 'SIN LOTES DISPONIBLES',
                        'cantidad'      => 'N/A'
                    ];
                    $prod_ordentrabajo[$prod_key]['L_INCOMP'] = true;
                }

                continue; // Saltar al siguiente producto
            }



            $parametros = array_map(function ($elemento) {
                return $elemento['identificador'];
            }, $arrlotes);
            $cant_param = implode(',', array_fill(0, count($arrlotes), '?'));

            $query = '
                SELECT
                    lp.codigoProv AS COD_PROV,
                    p.razon_social AS PROVEEDOR,
                    CONCAT(pro.droga, " - ", pre.forma, ", ", pre.presentacion) AS PRODUCTO,
                    pot.l_incompleto AS L_INCOMP
                FROM lote_presentacion_producto lpp
                INNER JOIN productos pro ON pro.id = lpp.producto_id
                INNER JOIN presentacions pre ON pre.id = lpp.presentacion_id
                INNER JOIN lotes l ON l.id = lpp.lote_id
                INNER JOIN lista_precios lp ON lp.producto_id = lpp.producto_id AND lp.presentacion_id = lpp.presentacion_id
                INNER JOIN producto_orden_trabajos pot ON pot.producto_id = lpp.producto_id AND pot.presentacion_id = lpp.presentacion_id
                INNER JOIN proveedors p ON lp.proveedor_id = p.id
                WHERE l.identificador IN (' . $cant_param . ')
            ';

            $reordenados = DB::select($query, $parametros);

            foreach ($reordenados as $prod) {
                $prod_key = $prod->PRODUCTO;

                if (!isset($prod_ordentrabajo[$prod_key])) {
                    $prod_ordentrabajo[$prod_key] = [];
                }

                $prod_ordentrabajo[$prod_key]['PROVEEDORES'][] = [
                    'COD_PROV' => $prod->COD_PROV,
                    'PROVEEDOR' => $prod->PROVEEDOR,
                ];

                $prod_ordentrabajo[$prod_key]['LOTES'] = $arrlotes;
                $prod_ordentrabajo[$prod_key]['L_INCOMP'] = $prod->L_INCOMP;
            }
        }
        $cant_aprob = count($ordentrabajo->cotizacion->productos->where('no_aprobado', 0));
        $observaciones = $ordentrabajo->observaciones;

        return view('administracion.ordenestrabajo.ordenTrabajo-layout', compact('ordentrabajo', 'prod_ordentrabajo', 'cant_aprob', 'observaciones'));

        $pdf = PDF::loadView('administracion.ordenestrabajo.ordenTrabajo-layout', compact('ordentrabajo', 'prod_ordentrabajo', 'cant_aprob', 'observaciones'));
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(270, 820, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 8, array(0, 0, 0));

        return $pdf->download('ordentrabajo_' . $ordentrabajo->cotizacion->identificador . '.pdf');
    }
}
