<?php

namespace Database\Seeders;

use App\Models\Proveedors;
use App\Models\Presentacion;
use App\Models\Producto;
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

        $this->call(Proveedors::class);
        $this->call(Presentacion::class);
        $this->call(Producto::class);
    }
}
