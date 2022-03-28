<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProveedorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'razon_social' => $this->faker->company(),
            'cuit' => $this->faker->bothify('#-########-##'),
            'contacto' => $this->faker->companyEmail(),
            'direccion' => $this->faker->bothify($this->faker->streetAddress() . ', ' . $this->faker->city(). ', ' . $this->faker->state()),
            'url' => $this->faker->url()
        ];
    }
}
