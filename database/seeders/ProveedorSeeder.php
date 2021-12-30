<?php

namespace Database\Seeders;

use App\Models\Proveedors;
use Illuminate\Database\Seeder;

class ProveedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Proveedors::factory()->times(30)->create();
    }
}
