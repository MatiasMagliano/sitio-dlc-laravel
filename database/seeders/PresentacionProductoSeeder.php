<?php

namespace Database\Seeders;

use App\Models\Presentacion;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class PresentacionProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $presentaciones = Presentacion::all();

        Producto::all()->each(function ($producto) use ($presentaciones){
            $producto->presentaciones()->attach(
                $presentaciones->random(1)->pluck('id')
            );
        });
    }
}
