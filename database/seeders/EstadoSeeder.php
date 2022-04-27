<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $datos = [
            ['estado' => 'Pendiente: agregando líneas'],
            ['estado' => 'Presentada el:'],
            ['estado' => 'Confirmada el:'],
            ['estado' => 'Rechazada el:'],
        ];
        DB::table('estados')->insert($datos);
    }
}
