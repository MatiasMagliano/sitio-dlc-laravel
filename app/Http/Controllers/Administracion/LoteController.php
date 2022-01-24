<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Lote;
use App\Models\Producto;
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
        return view('administracion.lotes.index', compact('productos'));
    }

    public function show($id)
    {
        $lote = Lote::find($id);
        $producto = Producto::find($lote->producto_id);
        return view('administracion.lotes.show', compact('lote', 'producto'));
    }
}
