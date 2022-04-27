<?php

namespace Database\Factories;

use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class ProductoCotizadoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $producto = Producto::inRandomOrder()->first();
        $cantidad = $this->faker->numberBetween(50, 1000);
        $precio   = $this->faker->randomFloat(2, 10, 500);

        return [
            //
            'producto_id'       => $producto->id,
            'presentacion_id'   => DB::table('lote_presentacion_producto')->where('producto_id', $producto->id)->pluck('presentacion_id')->get('0'),
            'cantidad'          => $cantidad,
            'precio'            => $precio,
            'total'             => $cantidad * $precio,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ];
    }
}
