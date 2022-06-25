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

        //LÓGICA PARA EL GRÁFICO DEL TOP 10
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
        //dd($cotizAprobRechaz);

        return view('home', compact('cantOT', 'maxCotizaciones', 'vencimientos', 'cantCotiz', 'compras', 'cotizAprobRechaz')
        );
    }
}
