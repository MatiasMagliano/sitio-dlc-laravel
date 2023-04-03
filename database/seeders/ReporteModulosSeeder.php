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
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'query'     => 'SELECT * FROM listados'
            ],
            [
                'nombre'    => 'Reporte de rendimiento de usuario',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'query'     => 'SELECT * FROM listados'
            ],
            [
                'nombre'    => 'Reporte de ventas *',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'query'     => 'SELECT * FROM listados'
            ],
            [
                'nombre'    => 'Reporte de ventas por temporada',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'query'     => 'SELECT * FROM listados'
            ],
            [
                'nombre'    => 'Reporte de cliente con mejor margen de ganancias',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'query'     => 'SELECT * FROM listados'
            ],
            [
                'nombre'    => 'Reporte rendimiento de producto',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'query'     => 'SELECT * FROM listados'
            ],
            [
                'nombre'    => 'Reporte de valoración de proveedores',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'query'     => 'SELECT * FROM listados'
            ],
            [
                'nombre'    => 'Reporte de ordenes de trabajo',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'query'     => 'SELECT * FROM listados'
            ],
        ];

        ReporteModulos::insert($reportes);
    }
}
