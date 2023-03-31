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
                'nombre'    => 'Reporte Mejor proveedor, por rango de vencimiento',
                'query'     => 'SELECT * FROM listados'
            ],
            [
                'nombre'  => 'Rendimiento (en lineas cotizadas) por usuario. acá buscamos indicadores de desempeño',
                'query'     => 'SELECT * FROM listados'
            ],
            [
                'nombre'  => 'Rendimiento (en dinero) por usuario. acá buscamos indicadores de desempeño',
                'query'     => 'SELECT * FROM listados'
            ],
            [
                'nombre'  => 'Reporte productos más vendidos por temporada',
                'query'     => 'SELECT * FROM listados'
            ],
            [
                'nombre'  => 'Reporte de cliente con mejor margen de ganancias',
                'query'     => 'SELECT * FROM listados'
            ],
            [
                'nombre'  => 'Reporte de cliente con mayor volumen de compra',
                'query'     => 'SELECT * FROM listados'
            ],
            [
                'nombre'  => 'Reporte de margen de ganancia por producto',
                'query'     => 'SELECT * FROM listados'
            ],
            [
                'nombre'  => 'Reporte de proveedores con mayor productos de interés',
                'query'     => 'SELECT * FROM listados'
            ],
            [
                'nombre'  => 'Reporte de órdenes impresas  con % de producto incompleta',
                'query'     => 'SELECT * FROM listados'
            ],
        ];

        ReporteModulos::insert($reportes);
    }
}
