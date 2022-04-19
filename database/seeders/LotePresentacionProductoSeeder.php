<?php

namespace Database\Seeders;

use App\Models\ListaPrecio;
use App\Models\Lote;
use App\Models\LotePresentacionProducto;
use App\Models\Presentacion;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Database\Seeder;

use function GuzzleHttp\Promise\all;

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
        foreach (Lote::all() as $lote){
            LotePresentacionProducto::factory()->create([
                'lote_id' => $lote->id,
            ]);
            $lote->touch();
        }
    }
}
