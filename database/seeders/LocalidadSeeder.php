<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocalidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $archivo = fopen(base_path('database/data/localidades.csv'), 'r');

        while(($datos = fgetcsv($archivo, 2000, ',')) !== FALSE){
            DB::table('localidades')->insert([
                'id'           => $datos['0'],
                'provincia_id' => $datos['1'],
                'nombre'       => $datos['2'],
                'departamento' => $datos['3'],
            ]);
        }

        fclose($archivo);
    }
}
