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
        $encabezado = '<h1 class="text-danger" align="center"><u>Droguería de la ciudad</u></h1><h5 class="" align="center">Raymundo Montenegro 2654 - CÓRDOBA<br></h5>';
        $reportes = ReporteModulos::orderby('nombre', 'ASC')->get();
        $listados = Listado::orderby('nombre', 'ASC')->get();
        return view('administracion.reportes.crear', compact('encabezado', 'reportes', 'listados'));
    }

    public function store(Request $request)
    {
        //dd($request);
        if ($request->reporte_o_listado === 'reporte') {
            // la validación con "sometimes" la hace si el campo está presente.
            // de lo contrario, no presenta validation-fail.
            $validacion = $request->validate(
                [
                    'nombre'                        => 'required|max:254',
                    'dirigido_a'                    => 'required|max:245',
                    'campo-encabezado'              => 'required|min:100',
                    'campo-adicional-encabezado'    => 'sometimes'
                ]
            );
        } elseif ($request->reporte_o_listado === 'listado') {
            // la validación con "sometimes" la hace si el campo está presente.
            // de lo contrario, no presenta validation-fail.
            $request->validate(
                [
                    'nombre'                        => 'required|max:254',
                    'dirigido_a'                    => 'required|max:245',
                    'campo-encabezado'              => 'required|min:100',
                    'campo-adicional-encabezado'    => 'sometimes'
                ]
            );
        } else {
            return view('administracion.reportes.index');
        }
    }

    // llena los selects cuando se agrega un listado anexado
    public function getListados(Request $request)
    {
        $listados = Listado::all()->sortBy('nombre');

        if ($request->ajax()) {
            return response()->json($listados);
        }
    }
}


/*
PARA DEVOLVER IMAGENES EN BASE64
$raw_image_string = base64_decode($base64_img_string);

return response($raw_image_string)->header('Content-Type', 'image/png');
*/
