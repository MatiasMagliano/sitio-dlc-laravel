<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        
        return [
            'droga' => $this->faker->medicine(),
            'lote' => $this->faker->numberBetween($min = 1000000, $max = 9999999),
            'precio' => $this->faker->randomFloat($nbMaxDecimals = 2, $min = 10, $max = 1000),
            'vencimiento' => $this->faker->dateTimeBetween('+1 years', '+3 years')
        ];
    }
}
