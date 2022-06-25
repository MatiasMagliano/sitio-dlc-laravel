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
        $vencimientos = DB::table('lotes')
            ->select(
                'lotes.identificador as title',
                'lotes.fecha_vencimiento as start',
                'lotes.fecha_vencimiento as end',
                )
            ->where('lotes.fecha_vencimiento', '>', $request->start)
            ->where('lotes.fecha_vencimiento', '<', $request->end)
            ->get();

        return response()->json($vencimientos);
    }

    // COTIZACIONES INICIADAS Y NO PRESENTADAS
    public function fechasIniciadas(Request $request)
    {
        $cotizaciones = DB::table('cotizacions')
            ->select(
                'cotizacions.identificador as title',
                'cotizacions.created_at as start',
                'cotizacions.created_at as end',
            )
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
            ->select(
                'cotizacions.identificador as title',
                'cotizacions.presentada as start',
                'cotizacions.presentada as end'
            )
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
            ->select(DB::raw("
                CONCAT('cotizacions.identificador ', 'cotizacions.estado') as title,
                cotizacions.confirmada as start,
                cotizacions.confirmada as end
            "))
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
            ->select(DB::raw("
                CONCAT('cotizacions.identificador ', 'cotizacions.estado') as title,
                cotizacions.rechazada as start,
                cotizacions.rechazada as end
            "))
            ->where('estado_id', 5)
            ->where('cotizacions.updated_at', '>', $request->start)
            ->where('cotizacions.updated_at', '<', $request->end)
            ->get();

        return response()->json($cotizaciones);
    }
}
