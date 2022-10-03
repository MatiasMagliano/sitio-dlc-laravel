<?php

namespace Database\Seeders;

use App\Models\Provincia;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

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
        $provincias = json_decode(File::get('database/data/provincias.json'), true);

        $data = [];

        foreach($provincias as $provincia)
        {
            $data[] = [
                'id' => $provincia['id'],
                'nombre' => $provincia['nombre'],
                'nombre_completo' => $provincia['nombre_completo'],
                'iso_id' => $provincia['iso_id'],
            ];
        }

        Provincia::insert($data);
    }
}
