<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Presentacion;
use App\Models\Producto;
use Illuminate\Http\Request;

class PresentacionController extends Controller
{
    public function index()
    {
        $productos = Producto::all();
        return view('administracion.presentaciones.index', compact('productos'));
    }

    public function edit($idProducto, $idPresentacion)
    {
        $presentacion = Presentacion::findOrFail($idPresentacion);
        return view('administracion.presentaciones.edit', compact('presentacion'));
    }

}
