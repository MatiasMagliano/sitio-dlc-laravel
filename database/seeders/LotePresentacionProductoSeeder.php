<?php

namespace Database\Seeders;

use App\Models\Lote;
use App\Models\LotePresentacionProducto;
use App\Models\Presentacion;
use App\Models\Producto;
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

        foreach (Lote::all() as $lote){
            LotePresentacionProducto::factory()->create([
                'lote_id' => $lote->id
            ]);
            $lote->touch();
        }
    }
}
