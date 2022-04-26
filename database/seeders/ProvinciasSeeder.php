<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinciasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $archivo = fopen(base_path('database/data/provincias.csv'), 'r');

        while(($datos = fgetcsv($archivo, 2000, ',')) !== FALSE){
            DB::table('provincias')->insert([
                'id'                => $datos['0'],
                'nombre'            => $datos['1'],
                'nombre_completo'   => $datos['2'],
                'iso_id'            => $datos['3'],
            ]);
        }

        fclose($archivo);
    }
}
