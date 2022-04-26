<?php

namespace Database\Factories;

use App\Models\Lote;
use App\Models\Presentacion;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

class LotePresentacionProductoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'producto_id' => Producto::inRandomOrder()->first()->id,
            'presentacion_id' => Presentacion::inRandomOrder()->first()->id,
            'lote_id' => Lote::inRandomOrder()->first()->id,
        ];
    }
}
