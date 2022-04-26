<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Proveedor;


class ListaPrecioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'proveedor_id' => Proveedor::inRandomOrder()->first()->id,
            'codigoProv' =>  $this->faker->numberBetween($min = 1000000, $max = 9999999),
            'costo' => $this->faker->numberBetween($min = 450.00,$max = 8200.00),
        ];
    }
}
