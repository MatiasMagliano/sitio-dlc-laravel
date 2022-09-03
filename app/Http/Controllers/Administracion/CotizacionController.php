<?php

namespace App\Http\Controllers\Administracion;

use App\Models\Cotizacion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ArchivoCotizacion;
use App\Models\Cliente;
use App\Models\ListaPrecio;
use App\Models\Presentacion;
use App\Models\Producto;
use App\Models\ProductoCotizado;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class CotizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cotizaciones = Cotizacion::with('user', 'cliente', 'estado')
            ->limit(100)
            ->get();
        $config = [
            'format' => 'DD/MM/YYYY',
            'dayViewHeaderFormat' => 'MMM YYYY',
        ];

        return view('administracion.cotizaciones.index', compact('cotizaciones', 'config'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clientes = Cliente::all();
        return view('administracion.cotizaciones.create', compact('clientes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // se valida que los campos estén presentes
        $request -> validate([
            'identificador' => 'required|max:50',
            'cliente_id' => 'required'
        ]);
        // Se valida que no haya una misma validación con el mismo identificador al mismo cliente
        $existente = Cotizacion::where('cliente_id', $request->get('cliente'))
            ->where('identificador', $request->get('identificador'))
            ->where('finalizada', null)->get();

        if($existente->count())
        {
            $request->session()->flash('error', 'Ya existe este identificador en una cotización sin finalizar. <a href="'.route('administracion.cotizaciones.show', $existente->first()).'">Haga click aquí para verla.</a>');
            return redirect()->route('administracion.cotizaciones.index');
        }

        $request->request->add(['estado_id' => 1]);
        $cotizacion = new Cotizacion($request->all());
        $cotizacion->save();

        $request->session()->flash('success', 'Cotización registrada con éxito. Agregue líneas a la cotización.');
        return redirect()->route('administracion.cotizaciones.show', ['cotizacione' => $cotizacion->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cotizacion  $cotizacion
     * @return \Illuminate\Http\Response
     */
    public function show(Cotizacion $cotizacione)
    {
        $cotizacion = $cotizacione;
        return view('administracion.cotizaciones.show', compact('cotizacion'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cotizacion  $cotizacion
     * @return \Illuminate\Http\Response
     */
    public function edit(Cotizacion $cotizacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cotizacion  $cotizacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cotizacion $cotizacion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cotizacion  $cotizacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cotizacion $cotizacion)
    {
        //
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
        if($request->hasFile('archivo')){
            $ruta = $request->file('archivo')->storeAs(
                'licitaciones-aprobadas', $cotizacion->identificador.'.pdf'
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
        if($request->hasFile('archivo')){
            $ruta = $request->file('archivo')->storeAs(
                'licitaciones-rechazadas', $cotizacion->identificador.'.pdf'
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

    public function agregarProducto(Cotizacion $cotizacion)
    {
        //AGREGAR PRESENTACION A COTIZACION
        $productos = Producto::orderby('droga', 'ASC')->get();
        $porcentajes = Cotizacion::select('clientes.razon_social','porcentaje_1', 'porcentaje_2', 'porcentaje_3', 'porcentaje_4', 'porcentaje_5')
        ->join('clientes', 'cotizacions.cliente_id','=','clientes.id')
        ->join('esquema_precios', 'clientes.id','=','esquema_precios.cliente_id')
        ->where('cotizacions.id','=', $cotizacion->id)
        ->get();


        return view('administracion.cotizaciones.agregarProducto', compact('cotizacion', 'productos', 'porcentajes'));
    }

    // AJAX QUE OBTIENE LOS PRECIOS SUGERIDOS
    public function preciosSugeridos(Request $request)
    {

        if($request->ajax()){
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
        $request -> validate([
            'producto' => 'required',
            'precio' => 'required',
            'cantidad' => 'required'
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

    public function generarpdf(Cotizacion $cotizacion, Request $request){
        if($cotizacion->finalizada){
            $presentaciones = Presentacion::all();
            $pdf = PDF::loadView('administracion.cotizaciones.pdfLayout', compact('cotizacion', 'presentaciones'));
            $dom_pdf = $pdf->getDomPDF();

            $canvas = $dom_pdf ->get_canvas();
            $canvas->page_text(500, 820, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0, 0, 0));
            if(!$cotizacion->presentada){
                // estado-3 --> "Presentada el:"
                $cotizacion->estado_id = 3;
                $cotizacion->presentada = Carbon::now();
                $cotizacion->save();
            }

            return $pdf->download('cotizacion_'.$cotizacion->identificador.'.pdf');
        }
        else{
            $request->session()->flash('error', 'La cotización aún no ha terminado de agregar líneas. Por favor finalice la cotización para descargar el PDF.');
            return redirect('/administracion/cotizaciones/');
        }
        //return view('administracion.cotizaciones.pdfLayout', compact('cotizacion'));
    }

    public function descargapdf(Cotizacion $cotizacion, $doc, Request $request){
        switch($doc){
            case 'cotizacion':
                return redirect(route('administracion.cotizaciones.generarpdf', $cotizacion));
                break;
            case 'provision':
                return Storage::download('licitaciones-aprobadas/'.$cotizacion->identificador .'.pdf', 'provision_'.$cotizacion->identificador.'.pdf');
                break;
            case 'rechazo':
                return Storage::download('licitaciones-rechazadas/'.$cotizacion->identificador .'.pdf', 'comparativo_'.$cotizacion->identificador.'.pdf');
                break;
            default:
                $request->session()->flash('error', 'La acción que desea ejecutar no es posible de procesar.');
                return redirect(route('home'));
                break;
        }
    }
}
