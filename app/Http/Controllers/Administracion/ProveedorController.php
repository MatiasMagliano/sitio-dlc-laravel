<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedores = Proveedor::all();
        return view('administracion.proveedores.index', compact('proveedores'));
    }
    public function show($id)
    {
        $proveedor = Proveedor::find($id);
        return view('administracion.proveedores.show', compact('proveedor'));
    }
    public function edit()
    {
        //
    }
    public function create()
    {
        //
    }
    public function destroy()
    {
        //
    }
}
