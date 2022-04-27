<?php

namespace Database\Seeders;

use App\Models\Lote;
use App\Models\LotePresentacionProducto;
use App\Models\Presentacion;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class LotePresentacionProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // se hace de esta manera, para evitar lotes duplicados en productos diferentes
        foreach (Producto::all() as $producto){
            $maxPresentacion = rand(1, 3);
            for($i = 1; $i <= $maxPresentacion; $i++){
                $randPresentacion = rand(1, 50);
                $presentacion = Presentacion::findOrFail($randPresentacion);

                $maxLote = rand(1, 5);
                for($i = 1; $i <= $maxLote; $i++){
                    $lote = Lote::factory()->create();

                    LotePresentacionProducto::factory()->create([
                        'producto_id' => $producto->id,
                        'presentacion_id' => $presentacion->id,
                        'lote_id' => $lote->id,
                    ]);
                }
            }
        }
    }
}
