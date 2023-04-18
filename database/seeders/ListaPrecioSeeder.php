<?php

namespace Database\Seeders;

use App\Models\ListaPrecio;
use App\Models\Lote;
use Illuminate\Database\Seeder;
use App\Models\LotePresentacionProducto;
use App\Models\Proveedor;
use Faker\Factory as Faker;

class ListaPrecioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //se genera el faker
        $this->faker = Faker::create();
        $productos = LotePresentacionProducto::select('producto_id', 'presentacion_id')->groupBy(['producto_id', 'presentacion_id'])->get();

        foreach($productos as $lpp)
        {
            // traer el lote correcpondiente y crear UNA lista de precios para ese lote. Luego semillar dos o tres listas de precios.

            
            $max_proveedor = rand(1, 5);
            for($i = 0; $i < $max_proveedor; $i++)
            {
                $codigoProv =  $this->faker->numberBetween(1000000, 9999999);
                $proveedor = Proveedor::inRandomOrder()->first()->id;
                // se calcula entre un 10 y un 30% más de costo con respecto al precio_compra del lote
                $promedio = Lote::promedioPrecioLotes($lpp->producto_id, $lpp->presentacion_id);
                $precio = $promedio + $promedio * rand(10, 30) / 100;

                ListaPrecio::create([
                    'codigoProv' => $codigoProv,
                    'producto_id' => $lpp->producto_id,
                    'presentacion_id' => $lpp->presentacion_id,
                    'proveedor_id' => $proveedor,
                    'costo' => $precio
                ]);
            }
        }
    }
}
