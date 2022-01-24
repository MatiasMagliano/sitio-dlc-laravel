<?php

namespace Database\Seeders;

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

        $this->call(RolSeeder::class);
        $this->call(ProductoSeeder::class);
        $this->call(ProveedorSeeder::class);
        $this->call(PresentacionSeeder::class);
        $this->call(PresentacionProductoSeeder::class);
        $this->call(ProductoProveedorSeeder::class);
        $this->call(LoteSeeder::class);
    }
}
