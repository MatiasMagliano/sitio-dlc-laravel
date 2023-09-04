<?php

namespace Database\Seeders;

use App\Models\DepositoCasaCentral;
use App\Models\ListaPrecio;
use App\Models\Lote;
use App\Models\LotePresentacionProducto;
use App\Models\Presentacion;
use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LotePresentacionProductoSeeder extends Seeder
{
    public function run()
    {
        // se crea un lote dummy para cuando se crean productos sin lote
        $fecha_fake = Carbon::create(1970, 1, 1, 12);
        Lote::create([
            'id'                => 1,
            'identificador'     => 'LOTE DUMMY',
            'precio_compra'     => 0,
            'fecha_elaboracion' => $fecha_fake,
            'fecha_compra'      => $fecha_fake,
            'fecha_vencimiento' => $fecha_fake,
            'cantidad'          => 0,
            'deleted_at'        => $fecha_fake
        ]);

        // se hace de esta manera, para evitar lotes duplicados en productos diferentes
        foreach (Producto::all() as $producto){
            $maxPresentacion = rand(1, 3);
            for($i = 1; $i <= $maxPresentacion; $i++){
                $presentacion = Presentacion::factory()->create();
                $deposito = DepositoCasaCentral::factory()->create();

                $maxLote = rand(1, 5);
                for($j = 1; $j <= $maxLote; $j++){
                    $lote = Lote::factory()->create();

                    LotePresentacionProducto::create([
                        'producto_id' => $producto->id,
                        'presentacion_id' => $presentacion->id,
                        'lote_id' => $lote->id,
                        'dcc_id' => $deposito->id,
                    ]);
                    $deposito->increment('existencia', $lote->cantidad);
                }
            }
        }
    }
}
