<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Cotizacion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportesController extends Controller
{
    public function index()
    {
        //LÓGICA PARA EL GRÁFICO DEL TOP 10 COTIZACIONES POR CLIENTES
        $cotizaciones = DB::select(DB::raw("
            SELECT clientes.razon_social as cliente, COUNT(cotizacions.id) as cantidad
            FROM clientes
            INNER JOIN cotizacions ON cotizacions.cliente_id = clientes.id
            GROUP BY(clientes.razon_social)
            ORDER BY cantidad DESC
            LIMIT 10
        "));
        $maxCotizaciones = [];
        foreach($cotizaciones as $cotizacion)
        {
            $maxCotizaciones['label'][] = $cotizacion->cliente;
            $maxCotizaciones['data'][]  = $cotizacion->cantidad;
        }
        $maxCotizaciones = json_encode($maxCotizaciones);

        //LÓGICA PARA EL GRÁFICO DE DONA aprobadas-rechazadas
        $cotizAprobRechaz = DB::select(DB::raw("
            SELECT COUNT(cotizacions.confirmada) as aprobadas, COUNT(cotizacions.rechazada) as rechazadas
            FROM cotizacions;
        "));
        //$cotizAprobRechaz = json_encode($cotizAprobRechaz);
        $cotizAprobRechaz = array(
            'label' => [
                'Aprobadas',
                'Rechazadas'
            ],
            'data'  => [
                $cotizAprobRechaz[0]->aprobadas,
                $cotizAprobRechaz[0]->rechazadas
            ],
        );
        $cotizAprobRechaz = json_encode($cotizAprobRechaz);

        // LOGICA PARA GRAFICO TOP-10 PRODUCTOS COTIZADOS
        // SELECT pro.droga, pre.forma, pre.presentacion, COUNT(pc.producto_id) Top10 FROM producto_cotizados pc
        // INNER JOIN presentacions pre ON pc.presentacion_id = pre.id
        // INNER JOIN productos pro ON pc.producto_id = pro.id
        // group by pro.droga, pre.forma, pre.presentacion ORDER BY COUNT(pc.producto_id) desc limit 10;

        //LÓGICA PARA EL GRÁFICO DE BARRAS perdidas-vencimiento
        $perdidasPorVencimiento = DB::select(DB::raw("
            SELECT VTO.Vencimiento, SUM(VTO.Perdida) AS `Perdida`, SUM(CASE WHEN VTO.costo1 < VTO.Perdida THEN 0 ELSE (VTO.costo1 - VTO.Perdida) END) AS `Oportunidad`
            FROM `lotes` lo
                INNER JOIN(SELECT lo.id, lpp.producto_id, lpp.presentacion_id, (`cantidad` * `precio_compra`) AS `Perdida`,
                        LEFT(`fecha_vencimiento`, 7) AS Vencimiento,  (`cantidad` * lipro.costo) AS `costo1`
                    FROM `lotes` lo
                        INNER JOIN `lote_presentacion_producto` lpp ON lo.id = lpp.lote_id
                        INNER JOIN `lista_precios` lipro ON lpp.producto_id = lipro.producto_id
                        INNER JOIN `lista_precios` lipre ON lpp.presentacion_id = lipre.presentacion_id
                    WHERE lipro.costo = lipre.costo
                )VTO ON lo.id = VTO.id
            WHERE lo.fecha_vencimiento BETWEEN DATE_SUB(NOW(),INTERVAL 6 MONTH) AND DATE_ADD(NOW(),INTERVAL 7 DAY)
            GROUP BY VTO.Vencimiento ORDER BY VTO.Vencimiento ASC;
        "));
        $perdidasPorVencimiento = array(
            'label' => [
                $perdidasPorVencimiento[0]->Vencimiento,
                $perdidasPorVencimiento[1]->Vencimiento,
                $perdidasPorVencimiento[2]->Vencimiento,
                $perdidasPorVencimiento[3]->Vencimiento,
                $perdidasPorVencimiento[4]->Vencimiento,
                //$perdidasPorVencimiento[5]->Vencimiento,
            ],
            'dataOportunidad'  => [
                $perdidasPorVencimiento[0]->Oportunidad,
                $perdidasPorVencimiento[1]->Oportunidad,
                $perdidasPorVencimiento[2]->Oportunidad,
                $perdidasPorVencimiento[3]->Oportunidad,
                $perdidasPorVencimiento[4]->Oportunidad,
                //$perdidasPorVencimiento[5]->Oportunidad,
            ],
            'dataPerdida'  => [
                $perdidasPorVencimiento[0]->Perdida,
                $perdidasPorVencimiento[1]->Perdida,
                $perdidasPorVencimiento[2]->Perdida,
                $perdidasPorVencimiento[3]->Perdida,
                $perdidasPorVencimiento[4]->Perdida,
                //$perdidasPorVencimiento[5]->Perdida,
            ],

        );
        $perdidasPorVencimiento = json_encode($perdidasPorVencimiento);

        $config_desde = [
            'format'              => 'DD/MM/YYYY',
            'maxDate'             => Carbon::now(),
            'dayViewHeaderFormat' => 'MMM YYYY',
            'date'                => Carbon::now(),
        ];
        $config_hasta = [
            'format'              => 'DD/MM/YYYY',
            'dayViewHeaderFormat' => 'MMM YYYY',
            'date'                => Carbon::now(),
        ];

        return view('administracion.reportes.index', compact('config_desde', 'config_hasta', 'cantOT', 'maxCotizaciones', 'vencimientos', 'cantCotiz', 'compras', 'cotizAprobRechaz', 'perdidasPorVencimiento'));
    }

    public function lst_clientes(Request $request)
    {
        if ($request->ajax())
        {
            $clientes = Cliente::orderBy($request->orden_listado)->get();

            $json = [];

            foreach($clientes as $dato)
            {
                $json[] = [
                    'razon_social' => view('administracion.reportes.partes.lst-clientes.razon-social', ['cliente' => $dato])->render(),
                    'regimen_trib' => view('administracion.reportes.partes.lst-clientes.regimen_tribut', ['cliente' => $dato])->render(),
                    'contacto'     => view('administracion.reportes.partes.lst-clientes.contacto', ['cliente' => $dato])->render(),
                    'ultima_compra'=> $dato->ultima_compra,
                ];
            }

            return response()->json($json);
        }
    }

    public function lst_ventas(Request $request)
    {
        if ($request->ajax())
        {
            if($request->tipo_reporte == 'ventas_concretadas')
            {
                $query = Cotizacion::select('*')->where('cotizacions.estado_id', 4)
                    ->join('clientes', 'cotizacions.cliente_id', '=', 'clientes.id')
                    ->orderBy($request->orden_listado);

                $vtas_concretadas = $query->get();

                $json = [];

                foreach($vtas_concretadas as $venta)
                {
                    $json[] = [
                        'fecha_aprobacion' => $venta->confirmada,
                        'identificador'    => $venta->identificador,
                        'cliente'          => view('administracion.reportes.partes.lst-clientes.cliente', ['venta' => $venta])->render(),
                        'monto_total'      => '$'. number_format($venta->monto_total, 2, ',', '.'),
                        'punto_entrega'    => view('administracion.reportes.partes.lst-clientes.pto-entrega', ['venta' => $venta])->render(),
                    ];
                }
                return response()->json($json);
            }

            if($request->tipo_reporte == 'ventas_rechazadas')
            {

                $query = Cotizacion::select('*')->where('cotizacions.estado_id', 5)
                    ->join('clientes', 'cotizacions.cliente_id', '=', 'clientes.id')
                    ->orderBy($request->orden_listado);

                $vtas_rechazadas = $query->get();

                $json = [];

                foreach($vtas_rechazadas as $venta)
                {
                    $json[] = [
                        'fecha_aprobacion' => $venta->rechazada,
                        'identificador'    => $venta->identificador,
                        'cliente'          => view('administracion.reportes.partes.lst-clientes.cliente', ['venta' => $venta])->render(),
                        'monto_total'      => '$'. number_format($venta->monto_total, 2, ',', '.'),
                        'punto_entrega'    => $venta->motivo_rechazo,
                    ];
                }
                return response()->json($json);
            }
        }
    }
}
