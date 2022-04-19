<?php

namespace Database\Seeders;

use App\Models\ListaPrecio;
use Illuminate\Database\Seeder;
use App\Models\LotePresentacionProducto;

class ListaPrecioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(LotePresentacionProducto::all() as $producto){
            ListaPrecio::factory()->create();
        }
    }
}
