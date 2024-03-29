<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PresentacionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $forma = array('COMPRIMIDO', 'CAPSULA BLANDA', 'GRAGEA', 'CREMA', 'SUSPENSION ORAL', 'SOLUCION INYECTABLE', 'INYECTABLE PARA PERFUSION', 'GRANULADO SOLUBLE', 'GEL');
        $presentacion = array (
            0 => array (
                'nombre' => rand(1, 10). ' AMPOLLA/S',
                'cantidad'  => ' por '. rand(1, 500),
                'medida' => 'ml.',
                'divisible' => 1
            ),
            1 => array (
                'nombre' => 'FRASCO',
                'cantidad' => ' por '. rand(1, 100),
                'medida' => 'unidades',
                'divisible' => 0
            ),
            2 => array (
                'nombre' => 'BLISTER',
                'cantidad' => ' por '. rand(1, 15),
                'medida' => 'unidades',
                'divisible' => 1
            ),
            3 => array (
                'nombre' => 'TUBO',
                'cantidad' => ' por '. rand(1, 100),
                'medida' => 'gr.',
                'divisible' => 0
            ),
        );

        $ind_pres = rand(0, 3);
        $ind_forma = rand(0, 8);

        return [
            'forma' => $this->faker->bothify($forma[$ind_forma]),
            'presentacion' => $this->faker->bothify($presentacion[$ind_pres]['nombre'] . $presentacion[$ind_pres]['cantidad'] .' '. $presentacion[$ind_pres]['medida']),
            'hospitalario' => rand(0, 1),
            'trazabilidad' => rand(0, 1),
            'divisible'    => $presentacion[$ind_pres]['divisible']
        ];
    }
}
