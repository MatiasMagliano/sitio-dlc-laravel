<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\User;
use App\Models\Cotizacion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index()
    {


        return view('administracion.reportes.index');
    }

    public function create()
    {
        $encabezado = '<h1 class="text-danger" align="center"><u>Droguería de la ciudad</u></h1><h5 class="" align="center">Raymundo Montenegro 2654 - CÓRDOBA<br></h5>';
        $reportes = collect([
            (object)[
                "value" => 0,
                "text"  => "Reporte Mejor proveedor, por rango de vencimiento"
            ],
            (object)[
                "value" => 1,
                "text"  => "Rendimiento (en lineas cotizadas) por usuario. acá buscamos indicadores de desempeño"
            ],
            (object)[
                "value" => 2,
                "text"  => "Rendimiento (en dinero) por usuario. acá buscamos indicadores de desempeño"
            ],
            (object)[
                "value" => 3,
                "text"  => "Reporte productos más vendidos por temporada"
            ],
            (object)[
                "value" => 4,
                "text"  => "Reporte de cliente con mejor margen de ganancias"
            ],
            (object)[
                "value" => 4,
                "text"  => "Reporte de cliente con mayor volumen de compra"
            ],
            (object)[
                "value" => 4,
                "text"  => "Reporte de margen de ganancia por producto"
            ],
            (object)[
                "value" => 4,
                "text"  => "Reporte de proveedores con mayor productos de interés"
            ],
            (object)[
                "value" => 4,
                "text"  => "Reporte de órdenes impresas  con % de producto incompleta"
            ],
        ]);

        return view('administracion.reportes.crear', compact('encabezado', 'reportes'));
    }

    public function store(Request $request)
    {
        dd($request);
    }
}
