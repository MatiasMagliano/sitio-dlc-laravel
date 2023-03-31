<?php

namespace Database\Seeders;

use App\Models\Listado;
use Illuminate\Database\Seeder;

class ListadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $listados =
        [
            [
                'nombre'  => 'Productos sin stock por proveedor',
                'query'   => 'SELECT * FROM listados'
            ],
            [
                'nombre'  => 'Producto por vencimientos a largo plazo',
                'query'   => 'SELECT * FROM listados'
            ],
            [
                'nombre'  => 'Proveedores por volumen mayor de productos',
                'query'   => 'SELECT * FROM listados'
            ],
            [
                'nombre'  => 'Proveedores por volumen de lotes',
                'query'   => 'SELECT * FROM listados'
            ],
            [
                'nombre'  => 'Cotizaciones brutas por usuario',
                'query'   => 'SELECT * FROM listados'
            ],
            [
                'nombre'  => 'Cotizaciones aprobadas por usuario',
                'query'   => 'SELECT * FROM listados'
            ],
            [
                'nombre'  => 'Procentual de cotizaciones por usuario',
                'query'   => 'SELECT * FROM listados'
            ],
            [
                'nombre'  => 'Procentual de cotizaciones (aprobadas y rechazadas) por usuario',
                'query'   => 'SELECT * FROM listados'
            ],
            [
                'nombre'  => 'Porcentual de cotizaciones (aprobada y rechazadas) valorizada por usuario',
                'query'   => 'SELECT * FROM listados'
            ],
            [
                'nombre'  => 'Órdenes de trabajo generadas sin stock',
                'query'   => 'SELECT * FROM listados'
            ],
        ];

        Listado::insert($listados);
    }
}
