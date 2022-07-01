<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalendarioController extends Controller
{
    public function index()
    {
        return view('administracion.calendarios.vencimientos');
    }

    // LOTES PROXIMOS A VENCERSE
    public function fechasVencimiento(Request $request)
    {
        // la consulta recolecta y concatena los datos necesarios para el fullcalendar:
        // title, description (para el tooltip) y start (que lo toma como "todo el día")
        $vencimientos = DB::table('lotes')
            ->select(DB::raw('
            lotes.identificador as title,
            CONCAT(productos.droga, ", ", presentacions.forma, " - ", presentacions.presentacion) as description,
            date(lotes.fecha_vencimiento) as start
            '))
            ->join('lote_presentacion_producto', 'lotes.id', '=', 'lote_presentacion_producto.lote_id')
            ->join('productos', 'productos.id', '=', 'lote_presentacion_producto.producto_id')
            ->join('presentacions', 'presentacions.id', '=', 'lote_presentacion_producto.presentacion_id')
            ->where('lotes.fecha_vencimiento', '>', $request->start)
            ->where('lotes.fecha_vencimiento', '<', $request->end)
            ->get();

        return response()->json($vencimientos);
    }

    // COTIZACIONES INICIADAS Y NO PRESENTADAS
    public function fechasIniciadas(Request $request)
    {
        $cotizaciones = DB::table('cotizacions')
            ->select(DB::raw('
                cotizacions.identificador as title,
                DATE(cotizacions.created_at) as start
            '))
            ->where('estado_id', 1)
            ->where('cotizacions.created_at', '>', $request->start)
            ->where('cotizacions.created_at', '<', $request->end)
            ->get();

        return response()->json($cotizaciones);
    }

    // COTIZACIONES PRESENTADAS
    public function fechasPresentadas(Request $request)
    {
        $cotizaciones = DB::table('cotizacions')
            ->select(DB::raw('
                cotizacions.identificador as title,
                DATE(cotizacions.presentada) as start
            '))
            ->where('estado_id', 3)
            ->where('cotizacions.updated_at', '>', $request->start)
            ->where('cotizacions.updated_at', '<', $request->end)
            ->get();

        return response()->json($cotizaciones);
    }

    // COTIZACIONES CONFIRMADAS
    public function fechasConfirmadas(Request $request)
    {
        $cotizaciones = DB::table('cotizacions')
            ->select(DB::raw('
                cotizacions.identificador as title,
                DATE(cotizacions.confirmada) as start
            '))
            ->where('estado_id', 4)
            ->where('cotizacions.updated_at', '>', $request->start)
            ->where('cotizacions.updated_at', '<', $request->end)
            ->get();

        return response()->json($cotizaciones);
    }

    // COTIZACIONES RECHAZADAS
    public function fechasRechazadas(Request $request)
    {
        $cotizaciones = DB::table('cotizacions')
            ->select(DB::raw('
                cotizacions.identificador as title,
                DATE(cotizacions.rechazada) as start
            '))
            ->where('estado_id', 5)
            ->where('cotizacions.updated_at', '>', $request->start)
            ->where('cotizacions.updated_at', '<', $request->end)
            ->get();

        return response()->json($cotizaciones);
    }
}
