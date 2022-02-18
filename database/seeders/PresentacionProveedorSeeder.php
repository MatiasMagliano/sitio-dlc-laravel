<?php

namespace Database\Seeders;

use App\Models\Presentacion;
use App\Models\Proveedor;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PresentacionProveedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $proveedores = Proveedor::all();

        Presentacion::all()->each(function($presentacion) use ($proveedores){
            $presentacion->proveedores()->attach(
                $proveedores->random(1)->pluck('id')
            );
        });
    }
}
