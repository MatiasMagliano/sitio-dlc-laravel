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
            'identificador' => $this->faker->numberBetween(1000000, 9999999),
            'precio_compra' => $this->faker->randomFloat(2, 10, 1000),
            'fecha_elaboracion' => $this->faker->dateTimeBetween('-3 years', '-1 years'),
            'fecha_compra' => $this->faker->dateTimeBetween('-11 months','-1 months'),
            'fecha_vencimiento' => $this->faker->dateTimeBetween('+1 months', '+3 years'),
            'cantidad' => $this->faker->randomNumber(3)
        ];
    }
}
