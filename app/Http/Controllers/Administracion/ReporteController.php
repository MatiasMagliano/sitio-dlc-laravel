<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\CampoCuerpo;
use App\Models\Documento;
use App\Models\Encabezado;
use Illuminate\Http\Request;
use App\Models\Listado;
use App\Models\Reporte;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Cast\Object_;

class ReporteController extends Controller
{
    public function index()
    {
        $documentos = Documento::all();

        return view('administracion.reportes.index', compact('documentos'));
    }

    public function ajaxdt(Request $request)
    {
        // RESPONDE UN JSON PARA POPULAR EL SERVERSIDE-DATATABLE
        $search = $request->query('search', array('value' => '', 'regex' => false));
        $draw = $request->query('draw', 0);
        $start = $request->query('start', 0);
        $length = $request->query('length', 25);
        $order = $request->query('order', array(0, 'desc'));

        // busqueda general
        $filter = $search['value'];
        // busqueda individual
        $GLOBALS['b_columna'] = Arr::except($request->query('columns'), array('_token', '_method'));

        $sortColumns = array(
            0 => 'documentos.nombre_documento',
            1 => 'users.name',
            2 => 'documentos.dirigido_a',
            3 => 'documentos.tipo_documento',
            4 => 'documentos.updated_at'
        );

        // QUERY COMPLETA DE COTIZACIONES
        $query = Documento::select(
            'documentos.id',
            'documentos.nombre_documento',
            'users.name',
            'documentos.dirigido_a',
            'documentos.tipo_documento',
            'documentos.updated_at',
        );

        $query->join('users', 'documentos.user_id', '=', 'users.id')
            ->where(function($query) {
                $b_columna = $GLOBALS['b_columna'];
                if(isset($b_columna[2]['search']['value'])) {
                    $query->where('name', 'like', '%'.$b_columna[2]['search']['value'].'%');
                }
            });

        // filtro general
        if (!empty($filter)) {
            $query->where('documentos.nombre_documento', 'like', '%'.$filter.'%');
        }

        //filtro particular
        $b_columna = $GLOBALS['b_columna'];
        if(isset($b_columna[1]['search']['value'])) {
            $query->where('nombre_documento', 'like', '%'.$b_columna[0]['search']['value'].'%');
        }

        $recordsTotal = $query->count();

        $sortColumnName = $sortColumns[$order[0]['column']];

        $query->orderBy($sortColumnName, $order[0]['dir'])
            ->take($length)
            ->skip($start);

        $json = array(
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => [],
        );

        $documentos = $query->get();

        foreach ($documentos as $documento) {
            $json['data'][] = [
                $documento->nombre_documento,
                $documento->name,
                $documento->dirigido_a,
                view('administracion.reportes.partials.tipo-documento', ['documento' => $documento])->render(),
                $documento->updated_at,
                view('administracion.reportes.partials.acciones', ['documento' => $documento])->render(),
            ];
        }

        return $json;
    }

    public function create()
    {
        $encabezado = '<h1 class="" align="center"><font color="#000000"><span style="font-family: Arial; font-size: 1.5em">Droguería de la Ciudad</span></font></h1><h6 class="" align="center"><font color="#9C9C94">Raymundo Montenegro 2654 - Ciudad de Córdoba, Argentina</font></h6><h6 class="" align="center"><font color="#9C9C94">Tel/Fax: 0351 - 223355 - E-mail:drogciudad@example.com.ar<br></font><br></h6>';
        return view('administracion.reportes.crear', compact('encabezado'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_documento'    => 'required',
            'nombre_documento'  => 'required|max:200|unique:documentos,nombre_documento',
            'dirigido_a'        => 'required|max:200',
            'encabezado'        => 'required|min:1'
        ]);

        $documento = Documento::create($request->all());

        //redirección de acuerdo a lo solicitado en el select
        if ($request->tipo_documento === 'reporte') {
            $request->session()->flash('success', 'Documento registrado con éxito. Ahora puede agrega contenido al REPORTE.');
            return redirect()
                ->route(
                    'administracion.reportes.inter-reportes',
                    ['documento' => $documento]
                );
        } else if ($request->tipo_documento === 'listado') {
            $request->session()->flash('success', 'Documento registrado con éxito. Ahora puede agrega contenido al LISTADO.');
            return redirect()
                ->route(
                    'administracion.reportes.inter-listados',
                    ['documento' => $documento]
                );
        } else {
            return view('administracion.reportes.index');
        }
    }

    public function editReporte(Documento $documento, Request $request)
    {
        return view('administracion.reportes.edit-reporte', compact('documento'));
    }

    public function editListado(Documento $documento, Request $request)
    {
        return view('administracion.reportes.edit-listado', compact('documento'));
    }

    public function showReporte(Documento $documento)
    {
        // compila los encabezados
        $encabezados = array();
        array_push(
            $encabezados,
            view(
                'administracion.reportes.partials.summernote',
                ['texto' => $documento->encabezado]
            )
        );
        foreach($documento->encabezados as $encabezado)
        {
            array_push(
                $encabezados,
                view(
                        'administracion.reportes.partials.summernote',
                        ['texto' => $encabezado->texto]
                    )->render()
                );
        }

        // compila el reporte
        foreach($documento->reportes as $reporte)
        {
            $querys = json_decode($reporte->querys, true);
        }
        //dd(collect($querys));
        $reportes = view(
            'administracion.reportes.partials.listado',
            [
                'ventas' => DB::select($querys['ventas']),
                'mas_vendidos' => DB::select($querys['producto_mas_vendido']),
            ]
        )->render();

        // compila los campos de texto
        $campos_cuerpo = array();
        foreach($documento->camposCuerpo as $campo)
        {
            array_push(
                $campos_cuerpo,
                view(
                    'administracion.reportes.partials.summernote',
                    ['texto' => $campo->texto]
                )->render()
            );
        }

        // compila los listados
        $listados = array();
        foreach($documento->listados as $listado)
        {
            array_push(
                $listados,
                Blade::render(
                    $listado->estructura_html,
                    [
                        'titulo' => $listado->nombre,
                        'dataset' => DB::select($listado->query)
                    ],
                    deleteCachedView: true
                )
            );
        }
        //dd($listados);

        return view('administracion.reportes.show-reporte')
            ->with('documento', $documento)
            ->with('encabezados', $encabezados)
            ->with('reportes', $reportes)
            ->with('campos_cuerpo', $campos_cuerpo)
            ->with('listados', $listados);
    }

    public function showListado(Documento $documento)
    {
        // code
    }

    public function interReporte(Documento $documento, Request $request)
    {
        $max_encabezados = 2;
        $max_texto_cuerpo = 3;
        $max_listados = 3;
        $reportes = Reporte::orderby('nombre', 'ASC')->get();
        $listados = Listado::orderby('nombre', 'ASC')->get();

        return view(
            'administracion.reportes.inter-reportes',
            compact(
                'documento',
                'reportes',
                'listados',
                'max_encabezados',
                'max_texto_cuerpo',
                'max_listados'
            )
        );
    }

    public function interListados(Documento $documento, Request $request)
    {
        $listados = Listado::orderby('nombre', 'ASC')->get();

        return view('administracion.reportes.inter-listados', compact('documento', 'listados'));
    }

    public function guardarDocumento(Request $request)
    {
        //dd($request);
        if ($request->tipo_documento === 'reporte') {
            $request->validate([
                'reporte'             => 'required',
                'campo_encabezado.*'  => 'sometimes|required|min:1',
                'campo_cuerpo.*'      => 'sometimes|required|min:1',
                'listado_adicional.*'   => 'sometimes|required|min:1'
            ]);

            // se busca el documento correspondiente
            $documento = Documento::findOrFail($request->documento);

            // se crean los encabezados si los hubiere
            if($request->campo_encabezado != null){
                foreach($request->campo_encabezado as $texto_encabezado)
                {
                    Encabezado::create([
                        'documento_id' => $documento->id,
                        'texto'        => $texto_encabezado
                    ]);
                }
            }

            // se crea y se asocia el reporte
            $documento->reportes()->attach($request->reporte);

            // se crean los campos de texto del cuerpo
            if($request->campo_cuerpo != null){
                foreach($request->campo_cuerpo as $texto_cuerpo)
                {
                    CampoCuerpo::create([
                        'documento_id' => $documento->id,
                        'texto'        => $texto_cuerpo
                    ]);
                }
            }

            // se crean y se asocian los listados
            if($request->listado_adicional != null){
                foreach($request->listado_adicional as $listado)
                {
                    $documento->listados()->attach($listado);
                }
            }

            $request->session()->flash('success', 'Reporte creado con éxito');
            return redirect()->route('administracion.reportes.index');
        }
    }

    // llena los selects cuando se agrega un listado anexado
    public function getListados(Request $request)
    {
        $listados = Listado::orderby('nombre', 'ASC')->get();

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
