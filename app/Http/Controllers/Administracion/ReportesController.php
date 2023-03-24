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
        $reportes = collect([
            (object)[
                "value" => 0,
                "text"  => "Mejor proveedor, por rango de vencimiento"
            ],
            (object)[
                "value" => 1,
                "text"  => "Rendimiento por usuario"
            ],
            (object)[
                "value" => 2,
                "text"  => "Productos más vendido por temporada"
            ],
            (object)[
                "value" => 3,
                "text"  => "Clientes mejor porcentaje de margen"
            ],
            (object)[
                "value" => 4,
                "text"  => "Clientes con mayor volumen de compra"
            ],
            (object)[
                "value" => 5,
                "text"  => "Margen de ganancia historico por producto"
            ],
            (object)[
                "value" => 6,
                "text"  => "Proveedores con mayor productos de interés"
            ],
        ]);

        return view('administracion.reportes.crear', compact('reportes', 'usuarios'));
    }
}
