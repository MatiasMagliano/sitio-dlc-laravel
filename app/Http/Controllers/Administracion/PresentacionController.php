<?php

namespace App\Http\Controllers\Administracion;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\Presentacion;
use App\Models\Producto;
use Illuminate\Http\Request;

class PresentacionController extends Controller
{
    public function index()
    {
        $presentaciones = Presentacion::all();
        return view('administracion.presentaciones.index', compact('presentaciones'));
    }

    public function edit($idProducto, $idPresentacion)
    {
        $presentacion = Presentacion::findOrFail($idPresentacion);
        $producto = Producto::findOrFail($idProducto);
        return view('administracion.presentaciones.edit', compact('presentacion', 'producto'));
    }

}
