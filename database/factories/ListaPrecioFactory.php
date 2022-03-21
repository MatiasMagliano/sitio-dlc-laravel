<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Presentacion;
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
            'presentacion_id' => Presentacion::inRandomOrder()->first()->id,
            'costo' => $this->faker->numberBetween($min = 450.00,$max = 8200.00),
        ];
    }
}
