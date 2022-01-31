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
        $presentaciones = Presentacion::all();
        return view('administracion.presentaciones.index', compact('presentaciones'));
    }

}
