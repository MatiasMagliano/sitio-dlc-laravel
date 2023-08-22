<?php

//namespace App\Http\Controllers;
namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Cotizacion;
use App\Models\Lote;
use Illuminate\Http\Request;

class ReporteAjaxController extends Controller
{
    public function llenarAniosSelect()
    {
        $anios = Cotizacion::selectRaw(
                'YEAR(confirmada) AS anio, COUNT(*) AS total_cotizaciones'
            )
            ->whereNotNull('confirmada')
            ->groupByRaw('YEAR(confirmada)')
            ->orderByDesc('anio')
            ->get();

        return response()->json($anios);
    }
}
