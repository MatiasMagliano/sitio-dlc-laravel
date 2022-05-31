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
            [
                'id'     => 1,
                'estado' => 'PENDIENTE'
            ],
            [
                'id'     => 2,
                'estado' => 'FINALIZADA'
            ],
            [
                'id'     => 3,
                'estado' => 'PRESENTADA'
            ],
            [
                'id'     => 4,
                'estado' => 'APROBADA'
            ],
            [
                'id'     => 5,
                'estado' => 'RECHAZADA'
            ],
            [
                'id'     => 6,
                'estado' => 'GENERANDO OT COMPLETA'
            ],
            [
                'id'     => 7,
                'estado' => 'GENERANDO OT PARCIAL'
            ],
            [
                'id'     => 8,
                'estado' => 'ASIGNANDO LOTES'
            ],
            [
                'id'     => 9,
                'estado' => 'EN PRODUCCIÓN'
            ],
        ];

        DB::table('estados')->insert($datos);
    }
}
