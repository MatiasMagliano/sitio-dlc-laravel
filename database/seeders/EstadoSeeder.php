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
            ['estado' => 'Finalizada el:'],
            ['estado' => 'Presentada el:'],
            ['estado' => 'Aprobada el:'],
            ['estado' => 'Rechazada el:'],
            ['estado' => 'En producción el:'],
        ];
        DB::table('estados')->insert($datos);
    }
}
