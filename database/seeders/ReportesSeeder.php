<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reporte;

class ReportesSeeder extends Seeder
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
                'nombre'          => 'Mejor proveedor',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'querys'          => '["SELECT * FROM listados", "SELECT * FROM listados"]'
            ],
            [
                'nombre'          => 'Rendimiento de usuario',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'querys'          => '["SELECT * FROM listados", "SELECT * FROM listados"]'
            ],
            [
                'nombre'          => 'Ventas *',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'querys'          => '["SELECT * FROM listados", "SELECT * FROM listados"]'
            ],
            [
                'nombre'          => 'Ventas por temporada',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'querys'          => '["SELECT * FROM listados", "SELECT * FROM listados"]'
            ],
            [
                'nombre'          => 'Cliente con mejor márgen de ganancias',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'querys'          => '["SELECT * FROM listados", "SELECT * FROM listados"]'
            ],
            [
                'nombre'          => 'Rendimiento de producto',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'querys'          => '["SELECT * FROM listados", "SELECT * FROM listados"]'
            ],
            [
                'nombre'          => 'Valoración de proveedores',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'querys'          => '["SELECT * FROM listados", "SELECT * FROM listados"]'
            ],
            [
                'nombre'          => 'Órdenes de trabajo',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'querys'          => '["SELECT * FROM listados", "SELECT * FROM listados"]'
            ],
        ];

        Reporte::insert($reportes);
    }
}
