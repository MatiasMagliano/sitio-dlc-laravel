<?php

namespace Database\Seeders;

use App\Models\Presentacion;
use Illuminate\Database\Seeder;

class PresentacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Presentacion::factory()->times(100)->create();
    }
}
