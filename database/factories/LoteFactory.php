<?php

namespace Database\Factories;

use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'identificador' => $this->faker->numberBetween($min = 1000000, $max = 9999999),
            'precioCompra' => $this->faker->randomFloat($nbMaxDecimals = 2, $min = 10, $max = 1000),
            'producto_id' => Producto::inRandomOrder()->first()->id,
            'desde' => Carbon::now()->format('Y-m-d H:i:s'),
            'hasta' => $this->faker->dateTimeBetween('+1 years', '+3 years'),
            'cantidad' => $this->faker->randomNumber($min = 10, $max = 1000),
        ];
    }
}
