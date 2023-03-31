<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReporteModulos;

class ReporteModulosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reportes = [
            [
                'nombre'    => 'Reporte mejor proveedor',
                'query'     => 'SELECT * FROM listados'
            ],
            [
                'nombre'    => 'Reporte de rendimiento de usuario',
                'query'     => 'SELECT * FROM listados'
            ],
            [
                'nombre'    => 'Reporte de ventas *',
                'query'     => 'SELECT * FROM listados'
            ],
            [
                'nombre'    => 'Reporte de ventas por temporada',
                'query'     => 'SELECT * FROM listados'
            ],
            [
                'nombre'    => 'Reporte de cliente con mejor margen de ganancias',
                'query'     => 'SELECT * FROM listados'
            ],
            [
                'nombre'    => 'Reporte rendimiento de producto',
                'query'     => 'SELECT * FROM listados'
            ],
            [
                'nombre'    => 'Reporte de valoración de proveedores',
                'query'     => 'SELECT * FROM listados'
            ],
            [
                'nombre'    => 'Reporte de ordenes de trabajo',
                'query'     => 'SELECT * FROM listados'
            ],
        ];

        ReporteModulos::insert($reportes);
    }
}
