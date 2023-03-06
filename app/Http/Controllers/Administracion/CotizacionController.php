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
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CotizacionController extends Controller
{
    public function index() // EN DESUSO
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

        // busqueda general
        $filter = $search['value'];
        // busqueda individual
        $GLOBALS['b_columna'] = Arr::except($request->query('columns'), array('_token', '_method'));

        $sortColumns = array(
            0 => 'cotizacions.updated_at',
            1 => 'cotizacions.identificador',
            2 => 'clientes.razon_social',
            3 => 'users.name',
            4 => 'estados.estado'
        );

        // QUERY COMPLETA DE COTIZACIONES
        $query = Cotizacion::select(
            'cotizacions.id',
            'cotizacions.identificador',
            'cotizacions.user_id',
            'cotizacions.cliente_id',
            'cotizacions.dde_id',
            'cotizacions.monto_total',
            'cotizacions.finalizada',
            'cotizacions.presentada',
            'cotizacions.confirmada',
            'cotizacions.rechazada',
            'cotizacions.created_at',
            'cotizacions.updated_at',
            'cotizacions.estado_id',
            'clientes.razon_social',
            'clientes.tipo_afip',
            'clientes.afip',
            'users.name',
            'estados.estado'
        )->whereIn('cotizacions.estado_id', [1, 2, 3]);

        $query->join('clientes', function($join){
            $join->on('cotizacions.cliente_id', '=', 'clientes.id')
                ->where(function($query){
                    $b_columna = $GLOBALS['b_columna'];
                    if(isset($b_columna[2]['search']['value'])) {
                        $query = $query->where('clientes.razon_social', 'like', '%'.$b_columna[2]['search']['value'].'%');
                    }
                });
        });

        $query->join('users', function($join){
            $join->on('cotizacions.user_id', '=', 'users.id')
                ->where(function($query){
                    $b_columna = $GLOBALS['b_columna'];
                    $query->select('*');
                    if(isset($b_columna[3]['search']['value'])) {
                        $query->where('name', 'like', '%'.$b_columna[3]['search']['value'].'%');
                    }
                });
        });

        $query->join('estados', function($join){
            $join->on('cotizacions.estado_id', '=', 'estados.id')
                ->where(function($query){
                    $b_columna = $GLOBALS['b_columna'];
                    $query->select('*');
                    if(isset($b_columna[4]['search']['value'])) {
                        $query->where('estado', 'like', '%'.$b_columna[4]['search']['value'].'%');
                    }
                });
        });

        // filtro general
        if (!empty($filter)) {
            $query->where('cotizacions.identificador', 'like', '%'.$filter.'%');
        }

        //filtro particular
        $b_columna = $GLOBALS['b_columna'];
        if(isset($b_columna[1]['search']['value'])) {
            $query->where('identificador', 'like', '%'.$b_columna[1]['search']['value'].'%');
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

    public function loadProdCotiz(Request $request)
    {
        // carga el dt en el show de la cotización
        if ($request->ajax())
        {
            $datos = ProductoCotizado::select(
                'producto_cotizados.id',
                'producto_cotizados.cotizacion_id',
                'productos.droga',
                DB::raw('CONCAT(presentacions.forma, " ", presentacions.presentacion) AS presentacion'),
                'producto_cotizados.cantidad',
                'producto_cotizados.precio',
                'producto_cotizados.total',
                'cotizacions.estado_id'
            )
                ->join('productos', 'producto_cotizados.producto_id', '=', 'productos.id')
                ->join('presentacions', 'producto_cotizados.presentacion_id', '=', 'presentacions.id')
                ->join('cotizacions', 'producto_cotizados.cotizacion_id', '=', 'cotizacions.id')
                ->where('producto_cotizados.cotizacion_id', '=', $request->cotizacion)
                ->get();

            $json = array(
                'data' => []
            );
            $indice = 1;

            foreach($datos as $dato)
            {
                $json['data'][] = [
                    'linea' => $indice,
                    'producto' => $dato->droga. ', ' .$dato->presentacion,
                    'cantidad' => $dato->cantidad,
                    'precio' => '$ '. $dato->precio,
                    'total' => '$ '. $dato->total,
                    'acciones' => view('administracion.cotizaciones.partials.acciones-show', ['cotizacion' => $dato->cotizacion_id, 'productoCotizado' => $dato->id, 'estado' => $dato->estado_id])->render(),
                ];
                $indice++;
            }

            return response()->json($json);
        }
    }

    public function edit(Cotizacion $cotizacion)
    {
        //
    }

    public function update(Request $request, Cotizacion $cotizacion)
    {
        //
    }

    public function destroy(Cotizacion $cotizacione, Request $request)
    {
        // elimina en cascada (en el método boot del modelo Cotizacion) todos los productos asociados
        $cotizacione->dde()->decrement('mas_entregado');

        // BORRA todos los productos relacionados
        foreach ($cotizacione->productos as $producto)
        {
            //dd($producto->producto->deposito($producto->presentacion_id));
            $producto->delete;
        }

        $cotizacione->delete;

        $request->session()->flash('success', 'La cotización fue borrada con éxito');
        return redirect()->route('administracion.cotizaciones.index');
    }


    // MÉTODOS ESPECIALES
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

    public function editarProductoCotizado(Request $request)
    {
        //EDICION DE LA PRESENTACION COTIZADA --> devuelve un ajax para rellenar el modal-formulario
        if($request->ajax())
        {
            $producto_cotizado = ProductoCotizado::find($request->producto);
            $respuesta = array(
                'producto_cotizado' => $producto_cotizado,
                'producto'  => Producto::find($producto_cotizado->producto_id),
                'presentacion' => Presentacion::find($producto_cotizado->producto_id),
            );

            return response()->json($respuesta);
        }
    }

    public function actualizarProductoCotizado(Request $request)
    {
        //ACTUALILZACION DEL PRODUCTO COTIZADO
        $prodCotiz = ProductoCotizado::find($request->prodCotiz_id);
        $datos = $request->validate([
            'cantidad'  => 'required|numeric|min:0',
            'precio'    => 'required|numeric|regex:/^\d*[0-9]+(?:\.[0-9]{1,2})?$/',
        ]);

        $prodCotiz->update($datos);
        $prodCotiz->update([
            'total' => $request->get('precio') * $request->get('cantidad')
        ]);

        return response()->json(['success' => 'El producto se ha modificado con éxito.']);
        //return redirect(route('administracion.cotizaciones.show', ['cotizacione' => $prodCotiz->cotizacion_id]));
    }

    public function borrarProductoCotizado(Request $request, Cotizacion $cotizacion, ProductoCotizado $productoCotizado)
    {
        //BORRADO DEL PRODUCTO COTIZADO
        $productoCotizado->delete();

        $request->session()->flash('success', 'Producto eliminado con éxito.');
        return redirect(route('administracion.cotizaciones.show', ['cotizacione' => $cotizacion]));
    }

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

    // SEPARAR LAS ACCIONES DE GENERACIÓN DE PDF Y PRESENTACIÓN DE COTIZACIÓN
    public function presentarCotizacion(Cotizacion $cotizacion)
    {

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

            return redirect(route('administracion.cotizaciones.index'));
        }
    }

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
