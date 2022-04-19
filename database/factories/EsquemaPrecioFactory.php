<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EsquemaPrecioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'porcentaje_1' => $this->faker->randomFloat(2, 20, 24),
            'porcentaje_2' => $this->faker->randomFloat(2, 25, 29),
            'porcentaje_3' => $this->faker->randomFloat(2, 30, 39),
            'porcentaje_4' => $this->faker->randomFloat(2, 40, 49),
            'porcentaje_5' => $this->faker->randomFloat(2, 50, 60),
        ];
    }
}
