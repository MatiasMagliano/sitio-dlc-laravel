<?php

namespace Database\Seeders;

use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Database\Seeder;

class ProductoProveedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $proveedores = Proveedor::all();

        Producto::all()->each(function ($producto) use ($proveedores){
            $producto->proveedores()->sync(
                $proveedores->random(2)->pluck('id')
            );
        });
    }
}
