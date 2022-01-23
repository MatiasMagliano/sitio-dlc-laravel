<?php

namespace Database\Factories;

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
            'desde' => $this->faker->dateTimeBetween('now', '+1 months'),
            'hasta' => $this->faker->dateTimeBetween('+1 years', '+3 years')
        ];
    }
}
