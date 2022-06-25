<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\DireccionEntrega;
use Illuminate\Database\Seeder;

class DireccionEntregaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        foreach(Cliente::all() as $cliente){
            $maxDirecciones = rand(3, 5);
            for($i = 1; $i <= $maxDirecciones; $i++){
                DireccionEntrega::factory()->create([
                    'cliente_id' => $cliente->id,
                ]);
            }
        }
    }
}
