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

class LoteVencidosSeeder extends Seeder
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

        // Se ejecuta lógica de lotes próximos a vencer
        // se hace de esta manera, para evitar lotes duplicados en productos diferentes
        foreach (Producto::all() as $producto){
            $maxPresentacion = rand(1, 2);
            for($i = 1; $i <= $maxPresentacion; $i++){
                $presentacion = Producto::presentaciones($producto->id)->random(1)->pluck('id')->get(0);
                $deposito = DepositoCasaCentral::find(
                    LotePresentacionProducto::getIdDeposito($producto->id, $presentacion) //devuelve un solo pivot relacionado a prod/pres
                );

                $codigoProv =  $this->faker->numberBetween(1000000, 9999999);

                $maxLote = rand(1, 2);
                for($j = 1; $j <= $maxLote; $j++){
                    $lote = Lote::factory()->create([
                        'identificador' => $this->faker->numberBetween(1000000, 9999999),
                        'precio_compra' => $this->faker->randomFloat(2, 10, 1000),
                        'fecha_elaboracion' => $this->faker->dateTimeBetween('-3 years', '-1 years'),
                        'fecha_compra' => $this->faker->dateTimeBetween('-11 months','-1 months'),
                        'fecha_vencimiento' => $this->faker->dateTimeBetween('-6 months', '-1 months'),
                        'cantidad' => $this->faker->randomNumber(4)
                    ]);

                    LotePresentacionProducto::create([
                        'producto_id' => $producto->id,
                        'presentacion_id' => $presentacion,
                        'lote_id' => $lote->id,
                        'dcc_id' => $deposito->id,
                    ]);
                    //$deposito->increment('existencia', $lote->cantidad);
                }
                $masProveedor = rand(1, 2);
                for($k = 1; $k <= $masProveedor; $k++)
                {
                    $proveedor = Proveedor::inRandomOrder()->first()->id;
                    ListaPrecio::create([
                        'codigoProv' => $codigoProv,
                        'producto_id' => $producto->id,
                        'presentacion_id' => $presentacion,
                        'proveedor_id' => $proveedor,
                        'costo' => $this->faker->randomFloat(2, 50, 1000),
                    ]);
                }
            }
        }
    }
}
