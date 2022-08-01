<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $cantOT = DB::select('
            SELECT COUNT(orden_trabajos.id) as cantidad
            FROM `orden_trabajos`;
        ');

        $compras = DB::select('
            SELECT COUNT(producto_orden_trabajos.id) as comprar
            FROM `producto_orden_trabajos`
            WHERE producto_orden_trabajos.lotes = -1;
        ');

        $vencimientos = DB::select('
            SELECT COUNT(lotes.id) as proximos
            FROM `lotes`
            WHERE lotes.fecha_vencimiento < NOW() + INTERVAL 30 DAY;
        ');

        $cantCotiz = DB::select('
            SELECT COUNT(cotizacions.id) as cantidad
            FROM `cotizacions`
            WHERE cotizacions.estado_id = 1;
        ');

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

        return view('home', compact('cantOT', 'maxCotizaciones', 'vencimientos', 'cantCotiz', 'compras', 'cotizAprobRechaz', 'perdidasPorVencimiento'));
    }
}
