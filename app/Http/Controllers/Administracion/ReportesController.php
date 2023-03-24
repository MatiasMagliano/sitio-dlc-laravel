<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\User;
use App\Models\Cotizacion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportesController extends Controller
{
    public function index()
    {


        return view('administracion.reportes.index');
    }

    public function create()
    {
        $usuarios = User::all();
        $modulos = collect([
            (object)[
                "value" => 0,
                "text"  => "Productos"
            ],
            (object)[
                "value" => 1,
                "text"  => "Proveedores"
            ],
            (object)[
                "value" => 2,
                "text"  => "Clientes"
            ],
            (object)[
                "value" => 3,
                "text"  => "Lotes"
            ],
            (object)[
                "value" => 4,
                "text"  => "Cotizaciones/ventas"
            ],
        ]);

        $submodulos = collect([
            (object)[
                "value" => 0,
                "text"  => "Reporte Mejor proveedor, por rango de vencimiento"
            ],
            (object)[
                "value" => 1,
                "text"  => " - - Clientes mejor porcentaje de margen"
            ],
            (object)[
                "value" => 2,
                "text"  => " - - Clientes con mayor volumen de compra"
            ],
            (object)[
                "value" => 3,
                "text"  => " - - Clientes por puntos de entrega"
            ],
            (object)[
                "value" => 4,
                "text"  => " - - Clientes por por mayoría"
            ],
        ]);

        return view('administracion.reportes.crear', compact('modulos', 'usuarios', 'submodulos'));
    }
}
