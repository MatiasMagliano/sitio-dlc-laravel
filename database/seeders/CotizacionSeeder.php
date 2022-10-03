<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\DepositoCasaCentral;
use App\Models\DireccionEntrega;
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

            $maxCotizaciones = rand(1, 3);
            for($i = 1; $i <= $maxCotizaciones; $i++){
                $dirEntrega = DireccionEntrega::where('cliente_id', '=', $cliente->id)
                    ->inRandomOrder()
                    ->first();
                $cotizacion = Cotizacion::factory()->create([
                    'cliente_id' => $cliente->id,
                    'dde_id' => $dirEntrega->id,
                ]);

                // se incrementa el ranking de puntos de entrega: "más entregado"
                $dirEntrega->increment('mas_entregado');

                $maxProductos = rand(2, 70);
                for($j = 1; $j <= $maxProductos; $j++){
                    ProductoCotizado::factory()->create([
                        'cotizacion_id' => $cotizacion->id
                    ]);
                }
            }
        }

        //actualiza el stock en el factory de ProductoCotizado...
    }
}
