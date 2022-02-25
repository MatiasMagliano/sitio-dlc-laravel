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

        //tablas básicas
        $this->call(UserSeeder::class);
        $this->call(RolSeeder::class);
        $this->call(ProductoSeeder::class);
        $this->call(ProveedorSeeder::class);
        $this->call(PresentacionSeeder::class);
        $this->call(LoteSeeder::class);

        //tablas pivot
        $this->call(RolUserSeeder::class);
    }
}
