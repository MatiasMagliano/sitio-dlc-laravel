<?php

namespace Database\Seeders;

use App\Models\DepositoCasaCentral;
use App\Models\ListaPrecio;
use App\Models\Lote;
use App\Models\LotePresentacionProducto;
use App\Models\Presentacion;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class LotePresentacionProductoSeeder extends Seeder
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

        // se hace de esta manera, para evitar lotes duplicados en productos diferentes
        foreach (Producto::all() as $producto){
            $maxPresentacion = rand(1, 3);
            for($i = 1; $i <= $maxPresentacion; $i++){
                $presentacion = Presentacion::factory()->create();
                $deposito = DepositoCasaCentral::factory()->create();

                $codigoProv =  $this->faker->numberBetween(1000000, 9999999);

                $maxLote = rand(1, 10);
                for($j = 1; $j <= $maxLote; $j++){
                    $lote = Lote::factory()->create();

                    $lpp = LotePresentacionProducto::create([
                        'producto_id' => $producto->id,
                        'presentacion_id' => $presentacion->id,
                        'lote_id' => $lote->id,
                        'dcc_id' => $deposito->id,
                    ]);
                    $deposito->increment('existencia', $lote->cantidad);
                }
                $masProveedor = rand(1, 3);
                for($k = 1; $k <= $masProveedor; $k++)
                {
                    $proveedor = Proveedor::inRandomOrder()->first()->id;
                    ListaPrecio::create([
                        'codigoProv' => $codigoProv,
                        'producto_id' => $producto->id,
                        'presentacion_id' => $presentacion->id,
                        'proveedor_id' => $proveedor,
                        'costo' => $this->faker->randomFloat(2, 50, 1000),
                    ]);
                }
            }
        }
    }
}
