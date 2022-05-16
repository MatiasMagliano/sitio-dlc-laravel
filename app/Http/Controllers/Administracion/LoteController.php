<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Lote;
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
        $productos = Producto::with('presentaciones')->get();
        $lotes = Lote::all();
        $config = [
            'format' => 'DD/MM/YYYY',
            'dayViewHeaderFormat' => 'MMM YYYY',
            'minDate' => "js:moment().startOf('month')",
        ];

        return view('administracion.lotes.index', compact('productos', 'lotes', 'config'));
    }

    public function buscarLotes(Request $request)
    {
        if($request->ajax()){
	        $lotes = Lote::lotesPorPresentacion($request->producto_id, $request->presentacion_id);
            return Response()->json($lotes);
        }
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
    public function store(Request $request){
        // No se puede agregar un nuevo lote con el mismo identificador
        $isValid = $request->validate([
            'identificador' => 'required|unique:lotes'
        ]);

        $lote = new Lote;

        $lote->identificador = $request->identificador;
        $lote->precioCompra = $request->precio;
        $lote->cantidad = $request->cantidad;
        $lote->desde = Carbon::now()->format('Y-m-d H:i:s');
        $lote->hasta = Carbon::createFromFormat('d/m/Y', $request->vencimiento);
        $lote->producto_id = $request->producto_id;

        $lote->save();

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
}
