<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\ProductoCotizado;
use Illuminate\Database\Seeder;

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
            $maxCotizaciones = rand(1, 5);
            for($i = 1; $i <= $maxCotizaciones; $i++){
                $cotizacion = Cotizacion::factory()->create([
                    'cliente_id' => $cliente->id,
                ]);
                $maxProductos = rand(2, 7);
                for($i = 1; $i <= $maxProductos; $i++){
                    ProductoCotizado::factory()->create([
                        'cotizacion_id' => $cotizacion->id,
                    ]);
                }
            }
        }
    }
}
