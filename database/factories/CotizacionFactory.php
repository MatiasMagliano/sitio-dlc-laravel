<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CotizacionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'identificador' => $this->faker->unique()->bothify('COTIZ#####'),
            'user_id'       => User::inRandomOrder()->first()->id,
            'plazo_entrega' => rand(1, 10). ' días hábiles posteriores a la aprobación de la cotización.',
            'estado_id'        => 1,
            'created_at'    => $this->faker->dateTimeBetween('-2 months', '-5 days'),
            'updated_at'    => $this->faker->dateTimeBetween('-1 months', '-3 days'),
        ];
    }
}
