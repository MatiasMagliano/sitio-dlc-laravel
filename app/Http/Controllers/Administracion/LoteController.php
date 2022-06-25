<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\DepositoCasaCentral;
use App\Models\Lote;
use App\Models\LotePresentacionProducto;
use App\Models\Presentacion;
use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoteController extends Controller
{
    /**
     * Devuelve una vista para agregar lotes a un producto
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // se crea una colección de líneas específicas
        $productos = Producto::all();
        $config = [
            'format' => 'DD/MM/YYYY',
            'dayViewHeaderFormat' => 'MMM YYYY',
        ];

        $config_vencimiento = [
            'format' => 'DD/MM/YYYY',
            'dayViewHeaderFormat' => 'MMM YYYY',
            'minDate' => "js:moment().startOf('month')",
        ];

        return view('administracion.lotes.index', compact('productos', 'config', 'config_vencimiento'));
    }

    public function show($id)
    {
        $lote = Lote::find($id);
        $producto = Producto::find($lote->producto_id);
        return view('administracion.lotes.show', compact('lote', 'producto'));
    }

    /**
     * Devuelve la vista index de lotes simplificada (sin la selección del producto)
     * con el producto ya seleccionado, para agregar o quitar lotes --> NO SE PUEDE MODIFICAR UN LOTE.
     * Recibe el id de producto y lista
     */
    public function edit($id)
    {
        $producto = Producto::findOrFail($id);

        $config = [
            'format' => 'DD/MM/YYYY',
            'dayViewHeaderFormat' => 'MMM YYYY',
            'minDate' => "js:moment().startOf('month')",
        ];

        return view('administracion.lotes.edit', compact('producto', 'config'));
    }

    /**
     * FUNCIÓN DECLARADA COMO AJAX
     */
    public function store(Request $request, LotePresentacionProducto $lpp){
        // No se puede agregar un nuevo lote con el mismo identificador
        $isValid = $request->validate([
            'identificador' => 'required|unique:lotes'
        ]);

        $lote = new Lote;
        $lote->identificador = $request->identificador;
        $lote->precio_compra = $request->precio_compra;
        $lote->cantidad = $request->cantidad;
        $lote->fecha_compra = Carbon::createFromFormat('d/m/Y', $request->fecha_compra);
        $lote->fecha_elaboracion = Carbon::createFromFormat('d/m/Y', $request->fecha_elaboracion);
        $lote->fecha_vencimiento = Carbon::createFromFormat('d/m/Y', $request->fecha_vencimiento);
        $lote->save();

        // agregado en lote_presentacion_producto
        $lpp->lotes()->attach($lote, [
            'producto_id'       => $request->producto_id,
            'presentacion_id'   => $request->presentacion_id,
            'dcc_id'            => LotePresentacionProducto::getIdDeposito($request->producto_id, $request->presentacion_id),
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ]);

        // actualizo las cantidades
        $deposito = DepositoCasaCentral::find(
            LotePresentacionProducto::getIdDeposito($request->producto_id, $request->presentacion_id)
        );
        $deposito->increment('existencia', $lote->cantidad);

        return response()->json(['mensaje' => 'El lote fue guardado con éxito']);
    }

    public function destroy(Request $request){
        if($request->ajax()){
            $lote = Lote::destroy($request->id);

            return response()->json($lote);
            //['mensaje' => 'El lote ha sido eliminado correctamente']
        }
        return response()->json(['mensaje' => 'No se encuentra el identificador del lote'+$request->id]);
    }

    // FUNCIONES AJAX
    public function buscarLotes(Request $request)
    {
        if($request->ajax()){
	        $lotes = Lote::lotesPorPresentacion($request->producto_id, $request->presentacion_id);
            return response()->json($lotes);
        }
    }
}
