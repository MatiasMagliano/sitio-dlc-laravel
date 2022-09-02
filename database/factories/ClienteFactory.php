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
        $afip = ['cuit', 'cuil'];

        return [
            'nombre_corto'  => $this->faker->word(),
            'razon_social'  => $this->faker->company(),
            'tipo_afip'     => $afip[rand(0,1)],
            'afip'          => $this->faker->bothify('##-########-#'),
            'telefono'      => $this->faker->bothify('0351-#######'),
            'email'         => $this->faker->safeEmail(),
            'contacto'      => $this->faker->name(),
            'ultima_cotizacion' => $this->faker->dateTimeBetween('-2 month', 'now'),
            'ultima_compra' => $this->faker->dateTimeBetween('-2 years', '-2 month'),
        ];
    }
}
