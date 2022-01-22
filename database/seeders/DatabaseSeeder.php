<?php

namespace Database\Seeders;

use App\Models\Presentacion;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        //$this->call(RolSeeder::class);

        $this->call(Proveedor::class);
        $this->call(Presentacion::class);
        $this->call(Producto::class);
    }
}
