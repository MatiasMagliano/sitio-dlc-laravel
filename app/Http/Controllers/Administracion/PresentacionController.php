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

    public function obtenerProductos(Request $request){
        if($request->ajax()){
            $productos = DB::table('productos')
                ->select('productos.id', 'productos.droga')
                ->join('lote_presentacion_producto', 'lote_presentacion_producto.producto_id', '=', 'productos.id')
                ->where('lote_presentacion_producto.presentacion_id', $request->presentacion_id)
                ->distinct()
                ->get();
            return Response()->json($productos);
        }
    }

    public function obtenerProveedores(Request $request){
        if($request->ajax()){
            $proveedores = DB::table('proveedors')
                ->select('razon_social', 'contacto', 'direccion')
                ->join('lista_precios', 'lista_precios.proveedor_id', '=', 'proveedors.id')
                ->join('lote_presentacion_producto', 'lote_presentacion_producto.id', '=', 'lista_precios.lpp_id')
                ->where('lote_presentacion_producto.producto_id', $request->producto_id)
                ->where('lote_presentacion_producto.presentacion_id', $request->presentacion_id)
                ->distinct()
                ->get();
        }
        return Response()->json($proveedores);
    }
}
