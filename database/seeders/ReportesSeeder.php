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
                'querys'          =>
                                    json_encode([
                                        'query1' => 'SELECT * FROM reportes;'
                                    ])
            ],
            [
                'nombre'          => 'Rendimiento de usuario',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'querys'          =>
                                    json_encode([
                                        'query1' => 'SELECT * FROM reportes;'
                                    ])
            ],
            [
                'nombre'          => 'Ventas *',
                'estructura_html' => '@php foreach ($ventas as $item) { $linea[] = (array) $item; } $mes_anterior = null; foreach ($mas_vendidos as $item) { $badge[] = (array) $item; } @endphp <h3 class="mb-3">Estadísticas de ventas en los últimos 12 meses</h2> <table id="prueba" class="table table-sm table-striped table-bordered" width="100%"> <thead class=" bg-gradient-lightblue"> <th class="text-center">MES</th> <th class="text-center">VENTAS</th> <th class="text-center">CRECIMIENTO</th> <th class="text-center">CREC. PORCENTUAL</th> <th class="text-center">PROD.COMUNES</th> <th class="text-center">HOSPITALARIOS</th> <th class="text-center">TRAZABLES</th> <th class="text-center">DIVISIBLES</th> </thead> <tbody> @foreach ($linea as $item) <tr> <td> {{-- MES --}} {{ ucfirst(trans($item["mes"])) }} </td> <td class="text-center"> {{-- VENTAS, se retiene el mes anterior --}} @php if (!is_null($mes_anterior)) { $crecimiento = $item["ventas"] - $mes_anterior; } $mes_anterior = $item["ventas"]; @endphp $ {{ number_format($item["ventas"], 2, ",", ".") }} </td> <td class="text-center"> {{-- CRECIMIENTO --}} @if ($loop->first) - @else @if ($crecimiento < 0) <span class="text-danger">$ {{ number_format($crecimiento, 2, ",", ".") }}</span> @else <span class="text-success">$ {{ number_format($crecimiento, 2, ",", ".") }}</span> @endif @endif </td> <td class="text-center"> {{-- CREC. PORCENTUAL --}} @if ($loop->first) - @else  @if ($crecimiento < 0) <span class="text-danger"> {{ number_format($crecimiento * 100 / $mes_anterior, 2, ",", ".") }}% </span> @else <span class="text-success"> {{ number_format($crecimiento * 100 / $mes_anterior, 2, ",", ".") }}% </span> @endif @endif </td> <td class="text-center"> {{-- COMUNES --}} {{ $item["cant_comunes"] }} </td> <td class="text-center"> {{-- HOSPITALARIOS --}} {{ $item["cant_hosp"] }} </td> <td class="text-center"> {{-- TRAZABLES --}} {{ $item["cant_trazable"] }} </td> <td class="text-center"> {{-- DIVISIBLES --}} {{ $item["cant_divisible"] }} </td> </tr> @endforeach </tbody> </table> <br> <br> <h3 class="mb-3">Los 5 productos más vendidos</h2> <ul class="list-group"> @foreach ($badge as $item) <li class="list-group-item d-flex justify-content-between align-items-center"> {{ $item["producto"] }} <span class="badge badge-success badge-pill"> {{ $item["cant_productos"] }} </span> </li> @endforeach </ul>',
                'querys'          =>
                                    json_encode([
                                        'ventas' => 'SELECT MONTHNAME(c.confirmada) AS mes, SUM(c.monto_total) AS ventas, COUNT(CASE WHEN p.hospitalario = 0 AND p.trazabilidad = 0 AND p.divisible = 0 THEN pc.id END) AS cant_comunes, COUNT(CASE WHEN p.hospitalario = 1 THEN pc.id END) AS cant_hosp, COUNT(CASE WHEN p.trazabilidad = 1 THEN pc.id END) AS cant_trazable, COUNT(CASE WHEN p.divisible = 1 THEN pc.id END) AS cant_divisible FROM cotizacions c INNER JOIN producto_cotizados pc ON c.id = pc.cotizacion_id INNER JOIN presentacions p ON pc.presentacion_id = p.id WHERE c.confirmada >= DATE_SUB(CURRENT_DATE(), INTERVAL 1 YEAR) AND c.confirmada < CURRENT_DATE() GROUP BY YEAR(c.confirmada), MONTH(c.confirmada);',
                                        'producto_mas_vendido' => 'SELECT CONCAT(p.droga, ", " , pr.forma, " ", pr.presentacion, CASE WHEN pr.hospitalario = 1 THEN " - HOSPITALARIO" ELSE "" END, CASE WHEN pr.trazabilidad = 1 THEN " - TRAZABLE" ELSE "" END, CASE WHEN pr.divisible = 1 THEN " - DIVISIBLE" ELSE "" END) AS producto, COUNT(CONCAT(p.droga, ", " , pr.forma, " ", pr.presentacion)) AS cant_productos FROM cotizacions co INNER JOIN producto_cotizados pco ON co.id = pco.cotizacion_id AND co.confirmada IS NOT NULL INNER JOIN productos p ON pco.producto_id = p.id INNER JOIN presentacions pr ON pco.presentacion_id = pr.id GROUP BY producto ORDER BY cant_productos DESC LIMIT 5;'
                                    ])
            ],
            [
                'nombre'          => 'Ventas por temporada',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'querys'          =>
                                    json_encode([
                                        'query1' => 'SELECT * FROM reportes;'
                                    ])
            ],
            [
                'nombre'          => 'Cliente con mejor márgen de ganancias',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'querys'          =>
                                    json_encode([
                                        'query1' => 'SELECT * FROM reportes;'
                                    ])
            ],
            [
                'nombre'          => 'Rendimiento de producto',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'querys'          =>
                                    json_encode([
                                        'query1' => 'SELECT * FROM reportes;'
                                    ])
            ],
            [
                'nombre'          => 'Valoración de proveedores',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'querys'          =>
                                    json_encode([
                                        'query1' => 'SELECT * FROM reportes;'
                                    ])
            ],
            [
                'nombre'          => 'Órdenes de trabajo',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'querys'          =>
                                    json_encode([
                                        'query1' => 'SELECT * FROM reportes;'
                                    ])
            ],
        ];

        Reporte::insert($reportes);
    }
}
