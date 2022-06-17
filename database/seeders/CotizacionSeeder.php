<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\DepositoCasaCentral;
use App\Models\LotePresentacionProducto;
use App\Models\Producto;
use App\Models\ProductoCotizado;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CotizacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        foreach(Cliente::all() as $cliente){
            $dirEntrega = DB::table('direcciones_entrega')->where('cliente_id', $cliente->id)->pluck('id');
            $maxCotizaciones = rand(1, 5);
            for($i = 1; $i <= $maxCotizaciones; $i++){
                $cotizacion = Cotizacion::factory()->create([
                    'cliente_id' => $cliente->id,
                    'dde_id' => $dirEntrega->random(1)->get('0'),
                ]);
                $maxProductos = rand(2, 100);
                for($i = 1; $i <= $maxProductos; $i++){
                    ProductoCotizado::factory()->create([
                        'cotizacion_id' => $cotizacion->id,
                    ]);
                }
            }
        }

        //actualiza el stock para todos los productos...
        //Se podría hacer con un observer, pero se carece de modelo DepositoCasaCentral relacionado
        //se lo busca a través de una DBQUERY
        foreach(Producto::all() as $producto){
            $presentaciones = DB::table('lote_presentacion_producto')
            ->where('producto_id', $producto->id)
            ->get('presentacion_id')
            ->unique();
            //dd($presentaciones);
            foreach($presentaciones as $presentacion){
                $deposito = DepositoCasaCentral::find(
                    LotePresentacionProducto::getIdDeposito($producto->id, $presentacion->presentacion_id) //devuelve un solo pivot relacionado a prod/pres
                );
                $deposito->increment('disponible', ($deposito->existencia - $deposito->cotizacion));
            }
        }
    }
}
