<?php

namespace Database\Seeders;

use App\Models\ListaPrecio;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class ListaPrecioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(Producto::all() as $producto){
            ListaPrecio::factory()->create();
        }
    }
}
