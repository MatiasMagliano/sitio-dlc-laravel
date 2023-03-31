<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\User;
use App\Models\Cotizacion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ReporteModulos;
use App\Models\Listado;

class ReporteController extends Controller
{
    public function index()
    {


        return view('administracion.reportes.index');
    }

    public function create()
    {
        $encabezado = '<h1 class="text-danger" align="center"><u>Droguería de la ciudad</u></h1><h5 class="" align="center">Raymundo Montenegro 2654 - CÓRDOBA<br></h5>';
        $reportes = ReporteModulos::all();

        return view('administracion.reportes.crear', compact('encabezado', 'reportes'));
    }

    public function store(Request $request)
    {
        dd($request);
    }

    public function getListados(Request $request)
    {
        $listados = Listado::all();

        if ($request->ajax())
        {
            return response()->json($listados);
        }

    }
}


/*
PARA DEVOLVER IMAGENES EN BASE64
$raw_image_string = base64_decode($base64_img_string);

return response($raw_image_string)->header('Content-Type', 'image/png');
*/
