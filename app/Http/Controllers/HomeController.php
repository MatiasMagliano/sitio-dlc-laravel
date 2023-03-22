<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cantOT = DB::select('
            SELECT COUNT(orden_trabajos.id) as cantidad
            FROM `orden_trabajos`;
        ');

        $compras = DB::select('
            SELECT COUNT(producto_orden_trabajos.id) as comprar
            FROM `producto_orden_trabajos`
            WHERE producto_orden_trabajos.lotes = -1;
        ');

        $vencimientos = DB::select('
            SELECT COUNT(lotes.id) as proximos
            FROM `lotes`
            WHERE lotes.fecha_vencimiento < NOW() + INTERVAL 30 DAY;
        ');

        $cantCotiz = DB::select('
            SELECT COUNT(cotizacions.id) as cantidad
            FROM `cotizacions`
            WHERE cotizacions.estado_id = 1;
        ');

        return view('home', compact('cantOT', 'compras', 'vencimientos', 'cantCotiz'));
    }
}
