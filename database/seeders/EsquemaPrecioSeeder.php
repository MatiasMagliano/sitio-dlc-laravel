<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\EsquemaPrecio;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EsquemaPrecioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        foreach (Cliente::all() as $cliente){
            EsquemaPrecio::factory()->create([
                'cliente_id' => $cliente->id,
            ]);
        }

        DB::table('esquema_precios')
            ->whereRaw('esquema_precios.id %2 = 0')
            ->update([
                'porcentaje_3' => 0,
                'porcentaje_4' => 0,
                'porcentaje_5' => 0
            ]);
    }
}
