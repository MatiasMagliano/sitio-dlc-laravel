<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

        return view('administracion.reportes.crear');
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

    public function loadView(Request $request)
    {
        $encabezado = '<h1 class="text-danger" align="center"><u>Droguería de la ciudad</u></h1><h5 class="" align="center">Raymundo Montenegro 2654 - CÓRDOBA<br></h5>';
        $reportes = ReporteModulos::all();

        if ($request->ajax())
        {
            if($request->seleccion == 'reporte')
            {
                return view('administracion.reportes.partials.nuevo-reporte')
                    ->with('encabezado', $encabezado)
                    ->with("reportes", $reportes)
                    ->render();
            }
            else
            {
                return view('administracion.reportes.partials.nuevo-listado')
                    ->with('encabezado', $encabezado)
                    ->with("reportes", $reportes)
                    ->render();
            }
        }
    }
}


/*
PARA DEVOLVER IMAGENES EN BASE64
$raw_image_string = base64_decode($base64_img_string);

return response($raw_image_string)->header('Content-Type', 'image/png');
*/
