<?php

namespace Database\Seeders;

use App\Models\Presentacion;
use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PresentacionProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $presentaciones = Presentacion::all('id');

        Producto::all()->each(function($producto) use ($presentaciones){
            $producto->presentaciones()->sync(
                $presentaciones->random(3)->pluck('id')
            );
        });
    }
}
