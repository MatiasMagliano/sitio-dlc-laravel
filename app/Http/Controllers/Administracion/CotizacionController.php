<?php

namespace App\Http\Controllers\Administracion;

use App\Models\Cotizacion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ArchivoCotizacion;
use App\Models\Cliente;
use App\Models\DireccionEntrega;
use App\Models\ListaPrecio;
use App\Models\Presentacion;
use App\Models\Producto;
use App\Models\ProductoCotizado;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class CotizacionController extends Controller
{
    public function index()
    {
        $config = [
            'format' => 'DD/MM/YYYY',
            'dayViewHeaderFormat' => 'MMM YYYY',
        ];

        return view('administracion.cotizaciones.index-dt', compact('config'));
    }

    public function ajaxdt(Request $request)
    {
        // RESPONDE UN JSON PARA POPULAR EL SERVERSIDE-DATATABLE
        $search = $request->query('search', array('value' => '', 'regex' => false));
        $draw = $request->query('draw', 0);
        $start = $request->query('start', 0);
        $length = $request->query('length', 25);
        $order = $request->query('order', array(0, 'desc'));

        $filter = $search['value'];

        $sortColumns = array(
            0 => 'cotizacions.updated_at',
            1 => 'cotizacions.modificacion',
            2 => 'clientes.razon_social',
            3 => 'users.name',
            4 => 'estados.estado'
        );

        // QUERY COMPLETA DE COTIZACIONES
        $query = Cotizacion::select('*')
            ->with(
                array(
                    'cliente' => function($query)
                    {
                        $query->select('*');
                    },
                    'user' => function($query)
                    {
                        $query->select('*');
                    },
                    'estado' => function($query)
                    {
                        $query->select('*');
                    }
                )
            )
            ->whereIn('cotizacions.estado_id', [1, 2, 3]);

        if (!empty($filter)) {
            $query->where('cotizacions.identificador', 'like', '%' . $filter . '%');
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

        $cotizaciones = $query->get();

        foreach ($cotizaciones as $cotizacion) {

            $json['data'][] = [
                $cotizacion->updated_at,
                $cotizacion->identificador,
                view('administracion.cotizaciones.partials.cliente', ['cotizacion' => $cotizacion])->render(),
                view('administracion.cotizaciones.partials.usuario', ['cotizacion' => $cotizacion])->render(),
                view('administracion.cotizaciones.partials.estados', ['cotizacion' => $cotizacion])->render(),
                view('administracion.cotizaciones.partials.acciones', ['cotizacion' => $cotizacion])->render(),
            ];
        }

        return $json;
    }

    public function historico()
    {
        return view('administracion.cotizaciones.historico');
    }

    public function historicoCotizaciones(Request $request)
    {
        $search = $request->query('search', array('value' => '', 'regex' => false));
        $draw = $request->query('draw', 0);
        $start = $request->query('start', 0);
        $length = $request->query('length', 25);
        $order = $request->query('order', array(2, 'asc'));

        $filter = $search['value'];

        $sortColumns = array(
            0 => 'creacion',
            1 => 'modificacion',
            2 => 'cotizacions.identificador',
            3 => 'clientes.razon_social',
            4 => 'estados.estado'
        );

        // QUERY COMPLETA DE COTIZACIONES
        $query = Cotizacion::join('clientes', 'clientes.id', '=', 'cotizacions.cliente_id')
            ->join('estados', 'estados.id', '=', 'cotizacions.estado_id')
            ->whereIn('cotizacions.estado_id', [4, 5])
            ->select(
                'cotizacions.*',
                'clientes.razon_social',
                'cotizacions.created_at as creacion',
                'cotizacions.updated_at as modificacion',
                'estados.estado',
            );

        if (!empty($filter)) {
            $query->where('cotizacions.identificador', 'like', '%' . $filter . '%');
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

        $cotizaciones = $query->get();

        foreach ($cotizaciones as $cotizacion) {

            $json['data'][] = [
                $cotizacion->created_at,
                $cotizacion->updated_at,
                $cotizacion->identificador,
                $cotizacion->cliente->razon_social,
                '<span class="badge badge-secondary">'. $cotizacion->estado .'</span>',
                view('administracion.cotizaciones.partials.acciones', ['cotizacion' => $cotizacion])->render(),
            ];
        }

        return $json;
    }

    public function create()
    {
        $clientes = Cliente::all();
        return view('administracion.cotizaciones.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        // se valida que los campos estén presentes
        $request->validate([
            'identificador' => 'required|unique:cotizacions,identificador|max:50',
            'cliente_id' => 'required'
        ]);
        // Se valida que no haya una misma cotización con el mismo identificador al mismo cliente
        $existente = Cotizacion::where('cliente_id', $request->get('cliente'))
            ->where('identificador', $request->get('identificador'))
            ->where('finalizada', null)->get();

        if ($existente->count()) {
            $request->session()->flash('error', 'Ya existe este identificador en una cotización sin finalizar. <a href="' . route('administracion.cotizaciones.show', $existente->first()) . '">Haga click aquí para verla.</a>');
            return redirect()->route('administracion.cotizaciones.index');
        }

        $request->request->add(['estado_id' => 1]);
        $cotizacion = new Cotizacion($request->all());

        // traer el punto de entrega sumarle uno al campo nuevo y guardarlo
        $dirEntrega = DireccionEntrega::findOrFail($request->dde_id);
        $dirEntrega->increment('mas_entregado');

        // se actualizan las fechas: updated_at
        $dirEntrega->save();
        $cotizacion->save();

        $request->session()->flash('success', 'Cotización registrada con éxito. Ahora puede agregar líneas a la cotización.');
        return redirect()->route('administracion.cotizaciones.show', ['cotizacione' => $cotizacion->id]);
    }

    public function show(Cotizacion $cotizacione)
    {
        $cotizacion = $cotizacione;
        return view('administracion.cotizaciones.show', compact('cotizacion'));
    }

    public function edit(Cotizacion $cotizacion)
    {
        //
    }

    public function update(Request $request, Cotizacion $cotizacion)
    {
        //
    }

    public function destroy(Cotizacion $cotizacion)
    {
        //
    }

    public function agregarProducto(Cotizacion $cotizacion)
    {
        //AGREGAR PRESENTACION A COTIZACION
        $productos = Producto::orderby('droga', 'ASC')->get();
        $porcentajes = Cotizacion::select('clientes.razon_social', 'porcentaje_1', 'porcentaje_2', 'porcentaje_3', 'porcentaje_4', 'porcentaje_5')
            ->join('clientes', 'cotizacions.cliente_id', '=', 'clientes.id')
            ->join('esquema_precios', 'clientes.id', '=', 'esquema_precios.cliente_id')
            ->where('cotizacions.id', '=', $cotizacion->id)
            ->get();


        return view('administracion.cotizaciones.agregarProducto', compact('cotizacion', 'productos', 'porcentajes'));
    }

    // MÉTODOS ESPECIALES
    public function finalizar(Cotizacion $cotizacion, Request $request)
    {
        //FINALIZAR COTIZACION
        $cotizacion->monto_total = $cotizacion->productos->sum('total');

        //GENERAR foreach que descuente y sume en disponible y cotizado respectivamente
        // foreach($cotizacion->productos as $producto_cotizado){

        // }

        $cotizacion->finalizada = Carbon::now();
        $cotizacion->cliente->ultima_cotizacion = Carbon::now();
        $cotizacion->estado_id = 2;
        $cotizacion->save();
        $request->session()->flash('success', 'La cotización se finalizó con éxito.\nSe habilita la descarga de la cotización para presentar al cliente.');
        return redirect(route('administracion.cotizaciones.index'));
    }

    public function aprobarCotizacion(Cotizacion $cotizacion, Request $request)
    {
        if ($request->hasFile('archivo')) {
            $ruta = $request->file('archivo')->storeAs(
                'licitaciones-aprobadas',
                $cotizacion->identificador . '.pdf'
            );
            $archivo = new ArchivoCotizacion();
            $archivo->cotizacion_id = $cotizacion->id;
            $archivo->ruta = $ruta;
            $archivo->nombre_archivo = $cotizacion->identificador;
            $archivo->causa_subida = $request->causa_subida;
            $archivo->save();
        }
        $cotizacion->confirmada = Carbon::createFromFormat('d/m/Y', $request->confirmada);
        // estado 4: Aceptada el: ...
        $cotizacion->estado_id = 4;
        $cotizacion->save();

        $request->session()->flash('success', 'La cotización se aprobó con éxito.\nAhora podrá generar la orden de trabajo para esta licitación.');
        return redirect(route('administracion.cotizaciones.index'));
    }

    public function rechazarCotizacion(Cotizacion $cotizacion, Request $request)
    {
        if ($request->hasFile('archivo')) {
            $ruta = $request->file('archivo')->storeAs(
                'licitaciones-rechazadas',
                $cotizacion->identificador . '.pdf'
            );
            $archivo = new ArchivoCotizacion();
            $archivo->cotizacion_id = $cotizacion->id;
            $archivo->ruta = $ruta;
            $archivo->nombre_archivo = $cotizacion->identificador;
            $archivo->causa_subida = $request->causa_subida;
            $archivo->save();
        }
        $cotizacion->rechazada = Carbon::createFromFormat('d/m/Y', $request->rechazada);
        // estado 5: Rechazada el: ...
        $cotizacion->estado_id = 5;
        $cotizacion->motivo_rechazo = $request->motivo_rechazo;
        $cotizacion->save();

        $request->session()->flash('success', 'La cotización se rechazó con éxito.');
        return redirect(route('administracion.cotizaciones.index'));
    }

    // AJAX QUE OBTIENE LOS PRECIOS SUGERIDOS
    public function preciosSugeridos(Request $request)
    {

        if ($request->ajax()) {
            // la lógica de la función sería obtener todos los precios para esa presentación
            // por ahora, se envía la misma lista para todos los productos
            //$sugerencias = [];
            $sugerencias = ListaPrecio::listarDescuentos($request->producto_id, $request->presentacion_id, $request->id);

            // AQUÍ DEBERÍA IR LA LÓGICA DE REARMADO DEL ARRAY

            return response()->json($sugerencias);
        }
    }

    public function editarProductoCotizado(Cotizacion $cotizacion, ProductoCotizado $productoCotizado)
    {
        //EDICION DE LA PRESENTACION COTIZADA
    }

    public function guardarProductoCotizado(Request $request, Cotizacion $cotizacion, ProductoCotizado $productoCotizado)
    {
        //GUARDADO DEL PRODUCTO COTIZADO
        $request->validate([
            'producto' => 'required',
            'precio' => 'required|numeric|min:0',
            'cantidad' => 'required|integer|min:0'
        ]);

        // el request->producto se desdobla en dos:
        // $producto_ids[0]: producto_id
        // $producto_ids[1]: presentacion_id
        $producto = $request->get('producto');
        $producto_ids = explode('|', $producto); //esta función separa y guarda en un array

        //se prepara el request para guardarlo completo en la bbdd
        $request->request->add([
            'producto_id' => $producto_ids[0],
            'presentacion_id' => $producto_ids[1]
        ]);
        $request->merge([
            'total' => $request->get('precio') * $request->get('cantidad')
        ]);

        //dd($request);
        $productoCotizado->create($request->all());

        $request->session()->flash('success', 'Producto agregado con éxito.');
        return redirect()
            ->route('administracion.cotizaciones.show', ['cotizacione' => $cotizacion]);
    }

    public function actualizarProductoCotizado(Request $request, Cotizacion $cotizacion, ProductoCotizado $productoCotizado)
    {
        //ACTUALIZADO DEL PRODUCTO COTIZADO
    }

    public function borrarProductoCotizado(Request $request, Cotizacion $cotizacion, ProductoCotizado $productoCotizado)
    {
        //BORRADO DEL PRODUCTO COTIZADO
        $productoCotizado->delete();

        $request->session()->flash('success', 'Cotización eliminada con éxito.');
        return response()->json();
    }

    public function generarpdf(Cotizacion $cotizacion, Request $request)
    {
        if ($cotizacion->finalizada) {

            $presentaciones = Presentacion::all();
            $pdf = PDF::loadView('administracion.cotizaciones.pdfLayout', compact('cotizacion', 'presentaciones'));

            $dom_pdf = $pdf->getDomPDF();
            $canvas = $dom_pdf->get_canvas();
            $canvas->page_text(270, 820, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 8, array(0, 0, 0));
            if (!$cotizacion->presentada) {
                // estado-3 --> "Presentada el:"
                $cotizacion->estado_id = 3;
                $cotizacion->presentada = Carbon::now();
                $cotizacion->save();
            }

            return $pdf->download('cotizacion_' . $cotizacion->identificador . '.pdf');
        } else {
            $request->session()->flash('error', 'La cotización aún no ha terminado de agregar líneas. Por favor finalice la cotización para descargar el PDF.');
            return redirect('/administracion/cotizaciones/');
        }

        //return view('administracion.cotizaciones.pdfLayout');
    }

    // public function generapdf(Cotizacion $cotizacion, Request $request)
    // {
    //     if ($cotizacion->finalizada) {
    //         $presentaciones = Presentacion::all();
    //         $pdf = PDF::loadView('administracion.cotizaciones.pdfLayout', compact('cotizacion', 'presentaciones'));
    //         $dom_pdf = $pdf->getDomPDF();

    //         $canvas = $dom_pdf->get_canvas();
    //         $canvas->page_text(500, 820, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));
    //         if (!$cotizacion->presentada) {
    //             // estado-3 --> "Presentada el:"
    //             $cotizacion->estado_id = 3;
    //             $cotizacion->presentada = Carbon::now();
    //             $cotizacion->save();
    //         }

    //         return $pdf->download('cotizacion_' . $cotizacion->identificador . '.pdf');
    //     } else {
    //         $request->session()->flash('error', 'La cotización aún no ha terminado de agregar líneas. Por favor finalice la cotización para descargar el PDF.');
    //         return redirect('/administracion/cotizaciones/');
    //     }
    //     //return view('administracion.cotizaciones.pdfLayout', compact('cotizacion'));
    // }

    public function descargapdf(Cotizacion $cotizacion, $doc, Request $request)
    {
        switch ($doc) {
            case 'cotizacion':
                return redirect(route('administracion.cotizaciones.generarpdf', $cotizacion));
                break;
            case 'provision':
                return Storage::download('licitaciones-aprobadas/' . $cotizacion->identificador . '.pdf', 'provision_' . $cotizacion->identificador . '.pdf');
                break;
            case 'rechazo':
                return Storage::download('licitaciones-rechazadas/' . $cotizacion->identificador . '.pdf', 'comparativo_' . $cotizacion->identificador . '.pdf');
                break;
            default:
                $request->session()->flash('error', 'La acción que desea ejecutar no es posible de procesar.');
                return redirect(route('home'));
                break;
        }
    }
}
