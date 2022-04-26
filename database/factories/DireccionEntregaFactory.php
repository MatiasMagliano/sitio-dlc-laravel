<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class DireccionEntregaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $localidades = DB::table('localidades')->where('provincia_id', '=', 14)->pluck('id');
        //dd($localidades->random(1)->get('0'));
        return [
            //
            'lugar_entrega' => strtoupper($this->faker->word()),
            'domicilio'     => $this->faker->streetAddress(),
            'provincia_id'  => 14,
            'localidad_id'  => $localidades->random(1)->get('0'),
            'condiciones'   => $this->faker->sentence($this->faker->randomDigit(), true),
            'observaciones' => $this->faker->sentence($this->faker->randomDigit(), true),
        ];
    }
}
