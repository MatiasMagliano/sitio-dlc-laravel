<?php

namespace Database\Seeders;

use App\Models\Lote;
use App\Models\Presentacion;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Rol;
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

        $this->call(Rol::class);
        $this->call(Producto::class);
        $this->call(Proveedor::class);
        $this->call(Presentacion::class);
        $this->call(Lote::class);
    }
}
