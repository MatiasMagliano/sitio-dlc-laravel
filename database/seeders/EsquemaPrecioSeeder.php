<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\EsquemaPrecio;
use Illuminate\Database\Seeder;

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
    }
}
