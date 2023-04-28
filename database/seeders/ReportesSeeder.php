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
                'querys'          => '{"ventas":"SELECT MONTHNAME(c.confirmada) AS mes, SUM(c.monto_total) AS ventas, COUNT(CASE WHEN p.hospitalario = 0 AND p.trazabilidad = 0 AND p.divisible = 0 THEN pc.id END) AS cant_comunes, COUNT(CASE WHEN p.hospitalario = 1 THEN pc.id END) AS cant_hosp, COUNT(CASE WHEN p.trazabilidad = 1 THEN pc.id END) AS cant_trazable, COUNT(CASE WHEN p.divisible = 1 THEN pc.id END) AS cant_divisible FROM cotizacions c INNER JOIN producto_cotizados pc ON c.id = pc.cotizacion_id INNER JOIN presentacions p ON pc.presentacion_id = p.id WHERE c.confirmada >= DATE_SUB(CURRENT_DATE(), INTERVAL 1 YEAR) AND c.confirmada < CURRENT_DATE() GROUP BY YEAR(c.confirmada), MONTH(c.confirmada);","producto_mas_vendido":"SELECT CONCAT(p.droga, \", \" , pr.forma, \" \", pr.presentacion, CASE WHEN pr.hospitalario = 1 THEN \" - HOSPITALARIO\" ELSE \"\" END, CASE WHEN pr.trazabilidad = 1 THEN \" - TRAZABLE\" ELSE \"\" END, CASE WHEN pr.divisible = 1 THEN \" - DIVISIBLE\" ELSE \"\" END) AS producto, COUNT(CONCAT(p.droga, \", \" , pr.forma, \" \", pr.presentacion)) AS cant_productos FROM cotizacions co INNER JOIN producto_cotizados pco ON co.id = pco.cotizacion_id AND co.confirmada IS NOT NULL INNER JOIN productos p ON pco.producto_id = p.id INNER JOIN presentacions pr ON pco.presentacion_id = pr.id GROUP BY producto ORDER BY cant_productos DESC LIMIT 5;}'
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
