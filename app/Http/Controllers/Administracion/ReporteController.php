<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\Proveedor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index()
    {

        return view('administracion.reportes.index');
    }

    // REPORTE Nº 1 - PEDIDOS PROCESADOS POR VENDEDOR
    public function pedProcxVendedor(Request $request)
    {
        $rango = explode(' - ', $request->sel_fecha);
        $desde = Carbon::parse($rango[0]);
        $hasta = Carbon::parse($rango[1]);

        // DATOS BÁSICOS DEL MEMBRETE
        $carbon = Carbon::now();
        $carbon->timezone('America/Argentina/Cordoba');
        $datos_membrete = collect(
            [
                [
                    'rango'          => 'Reporte desde: ' . $rango[0] . ' hasta el ' . $rango[1],
                    'nombre_reporte' => 'Pedidos procesados por vendedor',
                    'fecha_emision'  => $carbon->format('d/m/Y'),
                    'hora_emision'   => $carbon->format('H:i')
                ]
            ]
        );

        $datos = DB::table(function ($query) {
            $query->select(
                'us.name',
                'co.identificador',
                DB::raw("CASE WHEN co.confirmada IS NOT NULL THEN co.confirmada ELSE co.rechazada END AS fecha"),
                'cl.razon_social',
                DB::raw('COUNT(pc.id) AS lineas'),
                'es.estado AS ESTADO'
            )
                ->from('cotizacions as co')
                ->join('estados as es', 'co.estado_id', '=', 'es.id')
                ->join('users as us', 'co.user_id', '=', 'us.id')
                ->join('clientes as cl', 'co.cliente_id', '=', 'cl.id')
                ->leftJoin('producto_cotizados as pc', 'co.id', '=', 'pc.cotizacion_id')
                ->where(function ($query) {
                    $query->whereNotNull('co.confirmada')
                        ->orWhereNotNull('co.rechazada');
                })
                ->groupBy('co.identificador', 'us.name', 'fecha', 'cl.razon_social');
        }, 'q')
            ->whereBetween('q.fecha', [$desde, $hasta])
            ->select(
                'q.name as VENDEDOR',
                'q.identificador as IDENT_COTIZ',
                'q.fecha as FECHA_DE_PEDIDO',
                'q.razon_social as CLIENTE',
                'q.lineas as CANT_LINEAS',
                'q.ESTADO'
            )
            ->orderBy('q.name')
            ->get();

        // reorganizar los datos para que queden "VENDEDOR" -> "VENTAS"
        $datosReorganizados = [];
        foreach ($datos as $registro) {
            $vendedor = $registro->VENDEDOR;
            unset($registro->VENDEDOR);

            if (!isset($datosReorganizados[$vendedor])) {
                $datosReorganizados[$vendedor] = [];
            }

            $datosReorganizados[$vendedor][] = $registro;
        }

        //dd($datosReorganizados);
        return view('administracion.reportes.reportes.pedProcxVendedor', compact('datos_membrete', 'datosReorganizados'));
    }

    // REPORTE Nº 2 - PEDIDOS RECHAZADOS
    public function pedidosRechazados(Request $request)
    {
        $rango = explode(' - ', $request->sel_fecha_rechazados);
        $desde = Carbon::parse($rango[0]);
        $hasta = Carbon::parse($rango[1]);

        // DATOS BÁSICOS DEL MEMBRETE
        $carbon = Carbon::now();
        $carbon->timezone('America/Argentina/Cordoba');
        $datos_membrete = collect(
            [
                [
                    'rango'          => 'Reporte desde: ' . $rango[0] . ' hasta el ' . $rango[1],
                    'nombre_reporte' => 'Pedidos rechazados',
                    'fecha_emision'  => $carbon->format('d/m/Y'),
                    'hora_emision'   => $carbon->format('H:i')
                ]
            ]
        );

        $datos = DB::select(
            'SELECT
                us.name AS VENDEDOR,
                co.identificador AS "IDENT_COTIZ",
                co.rechazada AS "FECHA_PEDIDO",
                cl.razon_social AS "CLIENTE",
                COUNT(pc.id) AS "CANT_LINEAS",
                dde.lugar_entrega AS "DESTINO"
            FROM cotizacions co
            INNER JOIN clientes cl ON co.cliente_id = cl.id AND co.rechazada IS NOT NULL
            INNER JOIN producto_cotizados pc ON co.id = pc.cotizacion_id
            INNER JOIN direcciones_entrega dde ON co.dde_id = dde.id
            INNER JOIN users us ON co.user_id = us.id
            WHERE co.rechazada >= ? AND co.rechazada <= ?
            GROUP BY co.identificador, co.rechazada;',
            [$desde, $hasta]
        );

        // reorganizar los datos para que queden "VENDEDOR" -> "VENTAS"
        $datosReorganizados = [];
        foreach ($datos as $registro) {
            $vendedor = $registro->VENDEDOR;
            unset($registro->VENDEDOR);

            if (!isset($datosReorganizados[$vendedor])) {
                $datosReorganizados[$vendedor] = [];
            }

            $datosReorganizados[$vendedor][] = $registro;
        }

        return view('administracion.reportes.reportes.pedidosRechazados', compact('datos_membrete', 'datosReorganizados'));
    }

    // REPORTE Nº 3 - CUOTA DE VENTAS POR VENDEDOR
    public function cuotaVtasPorVendedor(Request $request)
    {
        $rango = explode(' - ', $request->sel_fecha_pedidos);
        $desde = Carbon::parse($rango[0]);
        $hasta = Carbon::parse($rango[1]);

        // DATOS BÁSICOS DEL MEMBRETE
        $carbon = Carbon::now();
        $carbon->timezone('America/Argentina/Cordoba');
        $datos_membrete = collect(
            [
                [
                    'rango'          => 'Reporte desde: ' . $rango[0] . ' hasta el ' . $rango[1],
                    'nombre_reporte' => 'Cuota de ventas por vendedor',
                    'fecha_emision'  => $carbon->format('d/m/Y'),
                    'hora_emision'   => $carbon->format('H:i')
                ]
            ]
        );

        $datos = DB::select(
            'SELECT
                q.VENDEDOR,
                q.APROBADAS,
                q.RECHAZADAS,
                q.TOTAL,
                IFNULL(ROUND(q.APROBADAS / q.TOTAL * 100, 2), 0) AS "RENDIMIENTO"
                FROM(
                    SELECT
                        us.name AS "VENDEDOR",
                        SUM(IFNULL(aprov.total, 0)) AS "APROBADAS",
                        SUM(IFNULL(recha.total, 0)) AS "RECHAZADAS",
                        SUM(IFNULL(aprov.total, 0)) + SUM(IFNULL(recha.total, 0)) AS "TOTAL"
                    FROM cotizacions co
                    INNER JOIN users us ON co.user_id = us.id
                    INNER JOIN producto_cotizados pc ON co.id = pc.cotizacion_id
                    LEFT JOIN(
                        SELECT
                            pc.id,
                            pc.total
                        FROM cotizacions co
                        INNER JOIN producto_cotizados pc ON co.id = pc.cotizacion_id
                        WHERE co.confirmada IS NOT NULL AND pc.no_aprobado = 0 AND (co.confirmada >= ? AND co.confirmada <= ?)
                    )aprov ON pc.id = aprov.id
                    LEFT JOIN(
                        SELECT
                            pc.id,
                            pc.total
                            FROM cotizacions co
                            INNER JOIN producto_cotizados pc ON co.id = pc.cotizacion_id
                            WHERE co.rechazada IS NOT NULL AND (co.rechazada >= ? AND co.rechazada <= ?)
                            UNION SELECT
                                pc.id,
                                pc.total
                                FROM cotizacions co
                                INNER JOIN producto_cotizados pc ON co.id = pc.cotizacion_id
                                WHERE co.confirmada IS NOT NULL AND pc.no_aprobado = 1 AND (co.confirmada >= ? AND co.confirmada <= ?)
                    )recha ON pc.id = recha.id
                    GROUP BY us.name
                )q;',
            [$desde, $hasta, $desde, $hasta, $desde, $hasta]
        );

        //dd($datos);

        return view('administracion.reportes.reportes.cuotavtasxvendedor', compact('datos_membrete', 'datos'));
    }

    // REPORTE Nº 4 - VENTAS POR RANGO DE FECHAS
    public function vtasPorRangoFechas(Request $request)
    {
        $rango = explode(' - ', $request->sel_fecha_ventas);
        $desde = Carbon::parse($rango[0]);
        $hasta = Carbon::parse($rango[1]);

        // DATOS BÁSICOS DEL MEMBRETE
        $carbon = Carbon::now();
        $carbon->timezone('America/Argentina/Cordoba');
        $datos_membrete = collect(
            [
                [
                    'rango'          => 'Reporte desde: ' . $rango[0] . ' hasta el ' . $rango[1],
                    'nombre_reporte' => 'Ventas por rango de fechas',
                    'fecha_emision'  => $carbon->format('d/m/Y'),
                    'hora_emision'   => $carbon->format('H:i')
                ]
            ]
        );

        $datos = DB::select(
            'SELECT
                co.confirmada AS "FECHA_DE_APROBACION",
                cl.razon_social AS "CLIENTE",
                COUNT(pc.id) AS "CANT_LINEAS",
                ROUND(SUM(pc.total), 2) AS "IMPORTE"
            FROM cotizacions co
            INNER JOIN clientes cl ON co.cliente_id = cl.id AND co.confirmada IS NOT NULL
            LEFT JOIN producto_cotizados pc ON co.id = pc.cotizacion_id
            WHERE pc.no_aprobado = 0 AND (co.confirmada >= ? AND co.confirmada <= ?)
            GROUP BY co.id, co.confirmada, cl.razon_social
            ORDER BY co.confirmada DESC;',
            [$desde, $hasta]
        );

        return view('administracion.reportes.reportes.vtasxrangodefechas', compact('datos_membrete', 'datos'));
    }

    // REPORTE Nº 5 - PRODUCTOS VENDIDOS POR CLIENTE
    public function prodVendPorCliente(Request $request)
    {
        $cliente = Cliente::where('razon_social', $request->sel_cliente)->first();

        // DATOS BÁSICOS DEL MEMBRETE
        $carbon = Carbon::now();
        $carbon->timezone('America/Argentina/Cordoba');
        $datos_membrete = collect(
            [
                [
                    'nombre_reporte' => 'Productos vendidos por cliente',
                    'fecha_emision'  => $carbon->format('d/m/Y'),
                    'hora_emision'   => $carbon->format('H:i')
                ]
            ]
        );

        $datos = DB::select(
            'SELECT
                pro.droga AS "PRODUCTO",
                CONCAT(pre.forma, ", ", pre.presentacion,
                CASE WHEN (pre.hospitalario = 1 AND pre.trazabilidad = 1 AND pre.divisible = 1) THEN " | HOSPITALARIO - TRAZABLE - DIVISIBLE"
                    ELSE (
                        CASE WHEN pre.hospitalario = 1 THEN CONCAT(" | HOSPITALARIO", CASE WHEN pre.trazabilidad = 1 THEN " - TRAZABLE" ELSE "" END, CASE WHEN pre.divisible = 1 THEN " - DVISIBLE" ELSE "" END
                    )
                    ELSE (
                        CASE WHEN pre.trazabilidad = 1 THEN CONCAT(" | TRAZABLE", CASE WHEN pre.divisible = 1 THEN " - DIVISIBLE" ELSE "" END
                    )
                    ELSE (
                        CASE WHEN pre.divisible = 1 THEN " | DIVISIBLE" ELSE " | COMUN" END) END) END) END) AS "FORM_PRES",
                SUM(pc.cantidad) AS "CANTIDAD",
                SUM(pc.total) AS "IMPORTE"
            FROM cotizacions co
            INNER JOIN clientes cl ON co.cliente_id = cl.id AND cl.razon_social = ? INNER JOIN producto_cotizados pc ON co.id = pc.cotizacion_id AND co.confirmada IS NOT NULL AND pc.no_aprobado = 0
            INNER JOIN productos pro ON pc.producto_id = pro.id
            INNER JOIN presentacions pre ON pc.presentacion_id = pre.id
            GROUP BY pro.droga, pre.forma, pre.presentacion, pre.hospitalario, pre.trazabilidad, pre.divisible
            ORDER BY pro.droga, CANTIDAD;',
            [$cliente->razon_social]
        );

        return view('administracion.reportes.reportes.prodVendPorCliente', compact('datos_membrete', 'datos', 'cliente'));
    }

    // REPORTE Nº 6 - VENTAS POR TIPO DE PRODUCTO
    public function vtasPorTipoProd(Request $request)
    {
        $rango = explode(' - ', $request->sel_fecha_ventas_por_tipo);
        $desde = Carbon::parse($rango[0]);
        $hasta = Carbon::parse($rango[1]);

        // DATOS BÁSICOS DEL MEMBRETE
        $carbon = Carbon::now();
        $carbon->timezone('America/Argentina/Cordoba');
        $datos_membrete = collect(
            [
                [
                    'rango'          => 'Reporte desde: ' . $rango[0] . ' hasta el ' . $rango[1],
                    'nombre_reporte' => 'Ventas por tipo de producto',
                    'fecha_emision'  => $carbon->format('d/m/Y'),
                    'hora_emision'   => $carbon->format('H:i')
                ]
            ]
        );

        $tipo = $request->sel_tipo_prod;

        $datos = DB::table('cotizacions as co')
            ->select('pro.droga as PRODUCTO', DB::raw("CONCAT(pre.forma, ', ', pre.presentacion) AS 'FORM_PRES'"))
            ->selectRaw('SUM(pc.cantidad) AS CANTIDAD')
            ->selectRaw('MAX(co.confirmada) AS ULTIMA_VENTA')
            ->join('clientes as cl', 'co.cliente_id', '=', 'cl.id')
            ->join('producto_cotizados as pc', function ($join) {
                $join->on('co.id', '=', 'pc.cotizacion_id')
                    ->where('pc.no_aprobado', '=', 0);
            })
            ->join('productos as pro', 'pc.producto_id', '=', 'pro.id')
            ->join('presentacions as pre', 'pc.presentacion_id', '=', 'pre.id')
            ->where('co.confirmada', '>=', $desde)
            ->where('co.confirmada', '<=', $hasta)
            ->when($tipo, function ($query, $tipo) {
                return $query->where(function ($query) use ($tipo) {
                    if ($tipo == 'hospitalario') {
                        $query->where('pre.hospitalario', '=', 1);
                    } elseif ($tipo == 'trazable') {
                        $query->where('pre.trazabilidad', '=', 1);
                    } elseif ($tipo == 'divisible') {
                        $query->where('pre.divisible', '=', 1);
                    } elseif ($tipo == 'comun') {
                        $query->where('pre.hospitalario', '=', 0)
                            ->where('pre.trazabilidad', '=', 0);
                    }
                });
            })
            ->groupBy('pro.droga', 'pre.forma', 'pre.presentacion', 'pre.hospitalario', 'pre.trazabilidad', 'pre.divisible')
            ->orderBy('pro.droga', 'DESC')
            ->orderBy('ULTIMA_VENTA', 'DESC')
            ->get();

        //dd($datos->toSql());
        return view('administracion.reportes.reportes.vtasportipoprod', compact('datos_membrete', 'datos', 'tipo'));
    }

    // REPORTE Nº 7 - PRODUCTO MÁS VENDIDO
    public function prodMasVendido(Request $request)
    {
        $rango = explode(' - ', $request->sel_fecha_mas_vendido);
        $desde = Carbon::parse($rango[0]);
        $hasta = Carbon::parse($rango[1]);

        // DATOS BÁSICOS DEL MEMBRETE
        $carbon = Carbon::now();
        $carbon->timezone('America/Argentina/Cordoba');
        $datos_membrete = collect(
            [
                [
                    'rango'          => 'Reporte desde: ' . $rango[0] . ' hasta el ' . $rango[1],
                    'nombre_reporte' => 'Producto más vendido',
                    'fecha_emision'  => $carbon->format('d/m/Y'),
                    'hora_emision'   => $carbon->format('H:i')
                ]
            ]
        );

        $datos = DB::select(
            'SELECT
                pro.droga AS "PRODUCTO",
                CONCAT(pre.forma, ", ", pre.presentacion, CASE WHEN (pre.hospitalario = 1 AND pre.trazabilidad = 1 AND pre.divisible = 1) THEN " | HOSPITALARIO - TRAZABLE - DIVISIBLE" ELSE (CASE WHEN pre.hospitalario = 1 THEN CONCAT(" | HOSPITALARIO", CASE WHEN pre.trazabilidad = 1 THEN " - TRAZABLE" ELSE "" END, CASE WHEN pre.divisible = 1 THEN " - DIVISIBLE" ELSE "" END) ELSE (CASE WHEN pre.trazabilidad = 1 THEN CONCAT(" | TRAZABLE", CASE WHEN pre.divisible = 1 THEN " - DIVISIBLE" ELSE "" END) ELSE (CASE WHEN pre.divisible = 1 THEN " | DIVISIBLE" ELSE " | COMUN" END) END) END) END) AS "FORM_PRES",
                COUNT(pc.id) AS "CANTIDAD",
                MAX(co.confirmada) AS "ULTIMA_VENTA"
            FROM producto_cotizados pc
            INNER JOIN cotizacions co ON pc.cotizacion_id = co.id AND co.confirmada IS NOT NULL AND pc.no_aprobado = 0 AND (co.confirmada >= ? AND co.confirmada <= ?)
            INNER JOIN productos pro ON pro.id = pc.producto_id
            INNER JOIN presentacions pre ON pre.id = pc.producto_id
            GROUP BY pro.droga, pre.forma, pre.presentacion, pre.hospitalario, pre.trazabilidad, pre.divisible
            ORDER BY COUNT(pc.id) DESC;
            ',
            [$desde, $hasta]
        );

        //dd($datos);
        return view('administracion.reportes.reportes.prodMasVendido', compact('datos_membrete', 'datos'));
    }

    // REPORTE Nº 8 - PRODUCTOS MENOS VENDIDOS
    public function prodMenosVendido(Request $request)
    {
        $rango = explode(' - ', $request->sel_fecha_menos_vendido);
        $desde = Carbon::parse($rango[0]);
        $hasta = Carbon::parse($rango[1]);

        // DATOS BÁSICOS DEL MEMBRETE
        $carbon = Carbon::now();
        $carbon->timezone('America/Argentina/Cordoba');
        $datos_membrete = collect(
            [
                [
                    'rango'          => 'Reporte desde: ' . $rango[0] . ' hasta el ' . $rango[1],
                    'nombre_reporte' => 'Producto menos vendido',
                    'fecha_emision'  => $carbon->format('d/m/Y'),
                    'hora_emision'   => $carbon->format('H:i')
                ]
            ]
        );

        $datos = DB::select(
            'SELECT
                pro.droga AS "PRODUCTO",
                CONCAT(pre.forma, ", ", pre.presentacion, CASE WHEN (pre.hospitalario = 1 AND pre.trazabilidad = 1 AND pre.divisible = 1) THEN " | HOSPITALARIO - TRAZABLE - DIVISIBLE" ELSE (CASE WHEN pre.hospitalario = 1 THEN CONCAT(" | HOSPITALARIO", CASE WHEN pre.trazabilidad = 1 THEN " - TRAZABLE" ELSE "" END, CASE WHEN pre.divisible = 1 THEN " - DIVISIBLE" ELSE "" END) ELSE (CASE WHEN pre.trazabilidad = 1 THEN CONCAT(" | TRAZABLE", CASE WHEN pre.divisible = 1 THEN " - DIVISIBLE" ELSE "" END) ELSE (CASE WHEN pre.divisible = 1 THEN " | DIVISIBLE" ELSE " | COMUN" END) END) END) END) AS "FORM_PRES",
                COUNT(pc.id) AS "CANTIDAD",
                MAX(co.confirmada) AS "ULTIMA_VENTA"
            FROM producto_cotizados pc
            INNER JOIN cotizacions co ON pc.cotizacion_id = co.id AND co.confirmada IS NOT NULL AND pc.no_aprobado = 0 AND (co.confirmada >= ? AND co.confirmada <= ?)
            INNER JOIN productos pro ON pro.id = pc.producto_id
            INNER JOIN presentacions pre ON pre.id = pc.producto_id
            GROUP BY pro.droga, pre.forma, pre.presentacion, pre.hospitalario, pre.trazabilidad, pre.divisible
            ORDER BY COUNT(pc.id) ASC;',
            [$desde, $hasta]
        );

        return view('administracion.reportes.reportes.prodMenosVendido', compact('datos_membrete', 'datos'));
    }

    // REPORTE Nº 9 - LOTES y STOCK
    public function lotesystock()
    {
        // DATOS BÁSICOS DEL MEMBRETE
        $carbon = Carbon::now();
        $carbon->timezone('America/Argentina/Cordoba');
        $datos_membrete = collect(
            [
                [
                    'nombre_reporte' => 'Lotes y Stock',
                    'fecha_emision' => $carbon->format('d/m/Y'),
                    'hora_emision' => $carbon->format('H:i')
                ]
            ]
        );

        $datos = DB::table('lotes as lo')
            ->select('lo.identificador as IDENTIFICADOR', DB::raw('CONCAT(pro.droga, " - ", pre.forma, ", ", pre.presentacion) AS PRODUCTO_PRESENTACION'))
            ->selectRaw('lo.cantidad as CANTIDAD')
            ->selectRaw('CASE WHEN (pre.hospitalario = 1 AND pre.trazabilidad = 1 AND pre.divisible = 1) THEN "HOSPITALARIO, TRAZABLE, DIVISIBLE" ELSE (CASE WHEN pre.hospitalario = 1 THEN CONCAT("HOSPITALARIO", CASE WHEN pre.trazabilidad = 1 THEN ", TRAZABLE" ELSE "" END, CASE WHEN pre.divisible = 1 THEN ", DIVISIBLE" ELSE "" END) ELSE (CASE WHEN pre.trazabilidad = 1 THEN CONCAT("TRAZABLE", CASE WHEN pre.divisible = 1 THEN ", DIVISIBLE" ELSE "" END) ELSE (CASE WHEN pre.divisible = 1 THEN "DIVISIBLE" ELSE "COMUN" END) END) END) END AS TIPO_PROD')
            ->selectRaw('lo.fecha_vencimiento as VENCIMIENTO')
            ->join('lote_presentacion_producto as lpp', 'lo.id', '=', 'lpp.lote_id')
            ->join('productos as pro', 'lpp.producto_id', '=', 'pro.id')
            ->join('presentacions as pre', 'lpp.presentacion_id', '=', 'pre.id')
            ->orderBy('lo.identificador')
            ->orderBy('lo.cantidad', 'desc')
            ->orderBy('lo.fecha_vencimiento', 'desc')
            ->get();

        $datosReorganizados = [];

        foreach ($datos as $dato) {
            $identificador = $dato->IDENTIFICADOR;

            if (!array_key_exists($identificador, $datosReorganizados)) {
                $datosReorganizados[$identificador] = [];
            }

            $datosReorganizados[$identificador][] = [
                'PRODUCTO_PRESENTACION' => $dato->{'PRODUCTO_PRESENTACION'},
                'CANTIDAD' => $dato->CANTIDAD,
                'TIPO_PROD' => $dato->TIPO_PROD,
                'VENCIMIENTO' => $dato->VENCIMIENTO,
            ];
        }

        //dd($datosReorganizados);
        return view('administracion.reportes.reportes.lotesystock', compact('datos_membrete', 'datosReorganizados'));
    }

    // REPORTE Nº 10 - PRODUCTOS MÁS COTIZADOS
    public function prodMasCotizado(Request $request)
    {
        $rango = explode(' - ', $request->sel_fecha_mas_cotizados);
        $desde = Carbon::parse($rango[0]);
        $hasta = Carbon::parse($rango[1]);

        // DATOS BÁSICOS DEL MEMBRETE
        $carbon = Carbon::now();
        $carbon->timezone('America/Argentina/Cordoba');
        $datos_membrete = collect(
            [
                [
                    'rango'          => 'Reporte desde: ' . $rango[0] . ' hasta el ' . $rango[1],
                    'nombre_reporte' => 'Productos más cotizados',
                    'fecha_emision'  => $carbon->format('d/m/Y'),
                    'hora_emision'   => $carbon->format('H:i')
                ]
            ]
        );

        $datos = DB::select(
            'SELECT
                CONCAT(pro.droga, " - ", pre.forma, ", ", pre.presentacion) AS "PROD_PRES",
                CASE WHEN (pre.hospitalario = 1 AND pre.trazabilidad = 1 AND pre.divisible = 1) THEN "HOSPITALARIO, TRAZABLE, DIVISIBLE" ELSE (CASE WHEN pre.hospitalario = 1 THEN CONCAT("HOSPITALARIO", CASE WHEN pre.trazabilidad = 1 THEN ", TRAZABLE" ELSE "" END, CASE WHEN pre.divisible = 1 THEN ", DIVISIBLE" ELSE "" END) ELSE (CASE WHEN pre.trazabilidad = 1 THEN CONCAT("TRAZABLE", CASE WHEN pre.divisible = 1 THEN ", DIVISIBLE" ELSE "" END) ELSE (CASE WHEN pre.divisible = 1 THEN "DIVISIBLE" ELSE "COMUN" END) END) END) END AS "TIPO_PROD",
                COUNT(aprov.aprovId) + COUNT(recha.rechaId) AS "COTIZ",
                COUNT(aprov.aprovId) AS "APROB",
                COUNT(recha.rechaId) AS "RECHA"
            FROM producto_cotizados pc
                INNER JOIN productos pro ON pc.producto_id = pro.id
                INNER JOIN presentacions pre ON pc.presentacion_id = pre.id
                LEFT JOIN(
                    SELECT
                        pc.id AS "aprovId"
                        FROM cotizacions co
                        INNER JOIN producto_cotizados pc ON co.id = pc.cotizacion_id
                        WHERE co.confirmada IS NOT NULL AND pc.no_aprobado = 0 AND (co.confirmada >= ? AND co.confirmada <= ?)
                    )aprov ON pc.id = aprov.aprovId
                    LEFT JOIN(
                        SELECT pc.id AS "rechaId" FROM cotizacions co
                        INNER JOIN producto_cotizados pc ON co.id = pc.cotizacion_id
                        WHERE co.rechazada IS NOT NULL AND (co.rechazada >= ? AND co.rechazada <= ?)
                        UNION SELECT
                            pc.id AS "rechaId"
                            FROM cotizacions co
                            INNER JOIN producto_cotizados pc ON co.id = pc.cotizacion_id
                            WHERE co.confirmada IS NOT NULL AND pc.no_aprobado = 1 AND (co.confirmada >= ? AND co.confirmada <= ?)
                    ) recha ON pc.id = recha.rechaId
                GROUP BY pro.id, pre.id
            ORDER BY (COUNT(aprov.aprovId) + COUNT(recha.rechaId))DESC;',
            [$desde, $hasta, $desde, $hasta, $desde, $hasta]
        );

        return view('administracion.reportes.reportes.prodMasCotizado', compact('datos_membrete', 'datos'));
    }

    // REPORTE Nº 11 - PRODUCTOS POR TEMPORADA
    public function repProdxTemporada(Request $request)
    {
        $anio_seleccionado = $request->anio;

        // DATOS BÁSICOS DEL MEMBRETE
        $carbon = Carbon::now();
        $carbon->timezone('America/Argentina/Cordoba');
        $datos_membrete = collect(
            [
                [
                    'rango'          => 'Año seleccionado: ' . $anio_seleccionado,
                    'nombre_reporte' => 'Reporte de Productos por temporada',
                    'fecha_emision'  => $carbon->format('d/m/Y'),
                    'hora_emision'   => $carbon->format('H:i')
                ]
            ]
        );

        // CONSULTA MYSQL
        $periodos = ['Enero-Marzo', 'Abril-Junio', 'Julio-Septiembre', 'Octubre-Diciembre'];

        $datos = Cotizacion::whereYear('confirmada', $anio_seleccionado)
            ->whereIn(DB::raw("CASE WHEN MONTH(confirmada) < 4 THEN 'Enero-Marzo'
                               WHEN MONTH(confirmada) < 7 THEN 'Abril-Junio'
                               WHEN MONTH(confirmada) < 10 THEN 'Julio-Septiembre'
                               ELSE 'Octubre-Diciembre' END"), $periodos)
            ->join('producto_cotizados as pc', 'cotizacions.id', '=', 'pc.cotizacion_id')
            ->join('productos as pro', 'pc.producto_id', '=', 'pro.id')
            ->join('presentacions as pre', 'pc.presentacion_id', '=', 'pre.id')
            ->selectRaw("CASE WHEN MONTH(confirmada) < 4 THEN 'Enero-Marzo'
                               WHEN MONTH(confirmada) < 7 THEN 'Abril-Junio'
                               WHEN MONTH(confirmada) < 10 THEN 'Julio-Septiembre'
                               ELSE 'Octubre-Diciembre' END AS PERIODO,
                     pc.id as LINEA, pro.droga as PRODUCTO, CONCAT(pre.forma, ', ', pre.presentacion) as PRESENTACION,
                     COUNT(pc.id) as CANTIDAD")
            ->groupBy('PERIODO', 'PRODUCTO', 'PRESENTACION')
            ->orderBy('PERIODO')
            ->orderByDesc('CANTIDAD')
            ->get();

        // Reorganizar los datos para un fácil acceso en la vista
        $datosReorganizados = [];
        foreach ($datos as $dato) {
            $datosReorganizados[$dato->PERIODO][] = [
                'PRODUCTO' => $dato->PRODUCTO,
                'PRESENTACION' => $dato->PRESENTACION,
                'CANTIDAD' => $dato->CANTIDAD,
            ];
        }

        //dd($datos->toSql());
        return view('administracion.reportes.reportes.repProdxTemporada', compact('datos_membrete', 'datosReorganizados'));
    }

    // REPORTE Nº 12 - CLIENTES
    public function repClientes()
    {
        // DATOS BÁSICOS DEL MEMBRETE
        $carbon = Carbon::now();
        $carbon->timezone('America/Argentina/Cordoba');
        $datos_membrete = collect(
            [
                [
                    'nombre_reporte' => 'Reporte de Clientes',
                    'fecha_emision' => $carbon->format('d/m/Y'),
                    'hora_emision' => $carbon->format('H:i')
                ]
            ]
        );

        $clientes = Cliente::with(array('dde' => function ($query) {
            $query->orderBy('mas_entregado', 'desc');
        }))
            ->get();
        return view('administracion.reportes.reportes.repClientes', compact('clientes', 'datos_membrete'));
    }

    // REPORTE Nº 13 - CLIENTES MÁS COTIZADOS
    public function clientesMasCotizados()
    {
        // DATOS BÁSICOS DEL MEMBRETE
        $carbon = Carbon::now();
        $carbon->timezone('America/Argentina/Cordoba');
        $datos_membrete = collect(
            [
                [
                    'nombre_reporte' => 'Clientes más cotizados',
                    'fecha_emision' => $carbon->format('d/m/Y'),
                    'hora_emision' => $carbon->format('H:i')
                ]
            ]
        );

        $datos = DB::select(
            'SELECT
                cl.razon_social AS "RAZON_SOCIAL",
                COUNT(co.identificador) AS "CANTIDAD",
                cl.ultima_cotizacion AS "ULTIMA_COTIZACION",
                cl.ultima_compra AS "ULTIMA_COMPRA"
            FROM clientes cl
            INNER JOIN cotizacions co ON cl.id = co.cliente_id
            GROUP BY cl.razon_social, cl.ultima_cotizacion, cl.ultima_compra;'
        );

        return view('administracion.reportes.reportes.clientesMasCotizados', compact('datos_membrete', 'datos'));
    }

    // REPORTE Nº 14 - ÓRDENES DE TRABAJO
    public function ordDeTrabajo()
    {
        // DATOS BÁSICOS DEL MEMBRETE
        $carbon = Carbon::now();
        $carbon->timezone('America/Argentina/Cordoba');
        $datos_membrete = collect(
            [
                [
                    'nombre_reporte' => 'Órdenes de trabajo',
                    'fecha_emision' => $carbon->format('d/m/Y'),
                    'hora_emision' => $carbon->format('H:i')
                ]
            ]
        );

        $datos = DB::select(
            'SELECT
                co.identificador AS "IDENT_ORDEN",
                ot.en_produccion AS "F_APROBACION",
                es.estado AS "ESTADO"
            FROM `orden_trabajos` ot
            INNER JOIN cotizacions co ON co.id = ot.cotizacion_id
            INNER JOIN estados es ON es.id = ot.estado_id
            ORDER BY ot.en_produccion;'
        );

        return view('administracion.reportes.reportes.ordDeTrabajo', compact('datos_membrete', 'datos'));
    }

    // REPORTE Nº 16 - PRODUCTOS POR PROVEEDOR
    public function prodPorProveedor(Request $request)
    {
        $proveedor = $request->sel_proveedor;
        $datos_proveedor = Proveedor::where('razon_social', $proveedor)->first();

        // DATOS BÁSICOS DEL MEMBRETE
        $carbon = Carbon::now();
        $carbon->timezone('America/Argentina/Cordoba');
        $datos_membrete = collect(
            [
                [
                    'nombre_reporte' => 'Reporte de Productos por temporada',
                    'fecha_emision'  => $carbon->format('d/m/Y'),
                    'hora_emision'   => $carbon->format('H:i')
                ]
            ]
        );

        $datos = DB::select(
            'SELECT
                pv.razon_social AS "PROVEEDOR",
                lp.codigoProv AS "COD_PROVEEDOR",
                CONCAT(pro.droga, " - ", pre.forma, ", ", pre.presentacion) AS "PROD_PRES",
                CASE WHEN (pre.hospitalario = 1 AND pre.trazabilidad = 1 AND pre.divisible = 1) THEN "HOSPITALARIO - TRAZABLE - DIVISIBLE" ELSE (CASE WHEN pre.hospitalario = 1 THEN CONCAT("HOSPITALARIO", CASE WHEN pre.trazabilidad = 1 THEN " - TRAZABLE" ELSE "" END, CASE WHEN pre.divisible = 1 THEN " - DIVISIBLE" ELSE "" END) ELSE (CASE WHEN pre.trazabilidad = 1 THEN CONCAT("TRAZABLE", CASE WHEN pre.divisible = 1 THEN " - DIVISIBLE" ELSE "" END) ELSE (CASE WHEN pre.divisible = 1 THEN "DIVISIBLE" ELSE "COMUN" END) END) END) END AS "TIPO_PROD",
                lp.costo AS "COSTO"
            FROM lista_precios lp
                INNER JOIN proveedors pv ON lp.proveedor_id = pv.id AND pv.razon_social = ?
                INNER JOIN productos pro ON lp.producto_id = pro.id
                INNER JOIN presentacions pre ON lp.presentacion_id = pre.id',
                [$proveedor]
        );

        return view('administracion.reportes.reportes.prodPorProveedor', compact('datos_membrete', 'datos', 'datos_proveedor'));
    }

    // REPORTE Nº 15 - PROVEEDORES
    public function repProveedores()
    {
        // DATOS BÁSICOS DEL MEMBRETE
        $carbon = Carbon::now();
        $carbon->timezone('America/Argentina/Cordoba');
        $datos_membrete = collect(
            [
                [
                    'nombre_reporte' => 'Reporte de Clientes',
                    'fecha_emision' => $carbon->format('d/m/Y'),
                    'hora_emision' => $carbon->format('H:i')
                ]
            ]
        );


        $proveedores = Proveedor::orderby('razon_social')->get();

        return view('administracion.reportes.reportes.repoProveedores', compact('proveedores', 'datos_membrete'));
    }

    // REPORTE Nº 17 - PRODUCTOS AL MENOR COSTO
    public function prodAlMenorCosto()
    {
        // DATOS BÁSICOS DEL MEMBRETE
        $carbon = Carbon::now();
        $carbon->timezone('America/Argentina/Cordoba');
        $datos_membrete = collect(
            [
                [
                    'nombre_reporte' => 'Productos al menor ',
                    'fecha_emision' => $carbon->format('d/m/Y'),
                    'hora_emision' => $carbon->format('H:i')
                ]
            ]
        );

        $datos = DB::table('lista_precios as lp')
            ->join(DB::raw('(SELECT lp.id, pro.id AS proId, pre.id AS preId, MIN(lp.costo) AS costo
                        FROM lista_precios lp
                            INNER JOIN productos pro ON lp.producto_id = pro.id
                            INNER JOIN presentacions pre ON lp.presentacion_id = pre.id
                        GROUP BY pro.droga, pre.forma, pre.presentacion) AS lpq'), function ($join) {
                $join->on('lp.costo', '=', 'lpq.costo')
                    ->on(function ($query) {
                        $query->whereColumn('lp.producto_id', '=', 'lpq.proId')
                            ->whereColumn('lp.presentacion_id', '=', 'lpq.preId');
                    });
            })
            ->join('proveedors as pv', 'lp.proveedor_id', '=', 'pv.id')
            ->join('productos as pro', 'lp.producto_id', '=', 'pro.id')
            ->join('presentacions as pre', 'lp.presentacion_id', '=', 'pre.id')
            ->select(
                'pro.droga as PRODUCTO',
                DB::raw("CONCAT(pre.forma, ', ', pre.presentacion) as PRESENTACION"),
                DB::raw("CASE
                        WHEN (pre.hospitalario = 1 AND pre.trazabilidad = 1 AND pre.divisible = 1) THEN 'H - T - D'
                        ELSE (CASE
                                WHEN pre.hospitalario = 1 THEN CONCAT('H', CASE WHEN pre.trazabilidad = 1 THEN ' - T' ELSE '' END, CASE WHEN pre.divisible = 1 THEN ' - D' ELSE '' END)
                                ELSE (CASE
                                        WHEN pre.trazabilidad = 1 THEN CONCAT('T', CASE WHEN pre.divisible = 1 THEN ' - D' ELSE '' END)
                                        ELSE (CASE
                                                WHEN pre.divisible = 1 THEN 'D'
                                                ELSE ''
                                              END)
                                      END)
                              END)
                      END AS TIPO"),
                'pv.razon_social as PROVEEDOR',
                'lp.codigoProv as CODIGOPROV',
                'lp.costo as COSTO'
            )
            ->orderBy('pro.droga')
            ->orderBy('pre.forma')
            ->orderBy('pre.presentacion')
            ->get();

        return view('administracion.reportes.reportes.prodAlMenorCosto', compact('datos_membrete', 'datos'));
    }
}
