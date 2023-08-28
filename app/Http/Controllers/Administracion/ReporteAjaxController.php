<?php

//namespace App\Http\Controllers;
namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\Lote;
use App\Models\Proveedor;
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

    public function llenarClienteSelect()
    {
        $clientes = Cliente::select('razon_social')
            ->orderBy('razon_social', 'desc')
            ->get();

        return response()->json($clientes);
    }

    public function llenarProveedorSelect()
    {
        $proveedores = Proveedor::select('razon_social')
            ->orderBy('razon_social', 'desc')
            ->get();

        return response()->json($proveedores);
    }
}
