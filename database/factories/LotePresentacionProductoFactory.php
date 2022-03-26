<?php

namespace Database\Factories;

use App\Models\Lote;
use App\Models\Presentacion;
use App\Models\Producto;
use App\Models\Proveedor;
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
            'proveedor_id' => Proveedor::inRandomOrder()->first()->id,
        ];
    }
}
