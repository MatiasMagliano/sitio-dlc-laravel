<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Cotizacion;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportesController extends Controller
{
    public function index()
    {
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

        return view('administracion.reportes.index', compact('config_desde', 'config_hasta'));
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
