<?php

namespace Database\Factories;

use App\Models\DepositoCasaCentral;
use App\Models\LotePresentacionProducto;
use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class ProductoCotizadoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $producto = Producto::inRandomOrder()->first(); //devuelve un objeto/modelo
        $presentacion = DB::table('lote_presentacion_producto')
            ->where('producto_id', $producto->id)
            ->pluck('presentacion_id')
            ->get('0'); //devuelve un solo valor relacionado al producto

        $deposito = DepositoCasaCentral::find(
            LotePresentacionProducto::getIdDeposito($producto->id, $presentacion) //devuelve un solo pivot relacionado a prod/pres
        );

        $cantidad = $this->faker->numberBetween(50, 500);
        $precio   = $this->faker->randomFloat(2, 10, 500);

        //actualiza el stock
        //$deposito->decrement('existencia', $cantidad);
        $deposito->increment('cotizacion', $cantidad);

        return [
            //
            'producto_id'       => $producto->id,
            'presentacion_id'   => $presentacion,
            'cantidad'          => $cantidad,
            'precio'            => $precio,
            'total'             => $cantidad * $precio,
            'no_aprobado'       => false,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now()
        ];
    }
}
