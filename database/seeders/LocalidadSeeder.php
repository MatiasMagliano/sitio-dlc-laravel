<?php

namespace Database\Seeders;

use App\Models\Localidad;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class LocalidadSeeder extends Seeder
{
    public function run()
    {
        //
        $localidades = json_decode(File::get('database/data/localidades.json'), true);

        $data = [];

        foreach($localidades as $localidad)
        {
            $data[] = [
                'id' => $localidad['id'],
                'provincia_id' => $localidad['provincia_id'],
                'nombre' => $localidad['nombre'],
                'departamento' => $localidad['departamento'],
            ];
        }

        Localidad::insert($data);
    }
}
