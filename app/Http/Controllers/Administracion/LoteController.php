<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Lote;
use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LoteController extends Controller
{
    /**
     * Devuelve una vista para agregar lotes a un producto
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productos = Producto::all();
        $config = [
            'format' => 'DD/MM/YYYY',
            'dayViewHeaderFormat' => 'MMM YYYY',
            'minDate' => "js:moment().startOf('month')",
        ];

        return view('administracion.lotes.index', compact('productos', 'config'));
    }

    public function show($id)
    {
        $lote = Lote::find($id);
        $producto = Producto::find($lote->producto_id);
        return view('administracion.lotes.show', compact('lote', 'producto'));
    }

    public function edit($id)
    {

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

        return response()->json('');
    }

    public function buscarLotes(Request $request)
    {
        if($request->ajax()){
            $where = array('producto_id' => $request->idProducto);
	        $lotes = Lote::where($where)->get();
            return Response()->json($lotes);
        }
    }
}