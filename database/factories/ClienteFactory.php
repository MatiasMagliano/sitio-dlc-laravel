<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use \Illuminate\Support\Str;

class ClienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $nombre = $this->faker->company();
        $afip = ['cuit', 'cuil'];

        return [
            'nombre_corto' => Str::words($nombre, 1, ''),
            'razon_social' => $nombre,
            'tipo_afip' => $afip[rand(0,1)],
            'afip' => $this->faker->bothify('##-########-#'),
            'telefono' => $this->faker->bothify('### #### ###'),
            'email' => $this->faker->safeEmail(),
            'contacto' => $this->faker->name(),
        ];
    }
}
