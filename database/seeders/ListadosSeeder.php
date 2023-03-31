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
                'query'   => 'SELECT productos.droga, CONCAT(presentacions.forma, " ", presentacions.presentacion) AS "presentacion", proveedors.razon_social, lotes.precio_compra FROM lote_presentacion_producto
                INNER JOIN productos on productos.id = lote_presentacion_producto.producto_id INNER JOIN presentacions on presentacions.id = lote_presentacion_producto.presentacion_id INNER JOIN lotes ON lotes.id = lote_presentacion_producto.id INNER JOIN lista_precios on lista_precios.producto_id = lote_presentacion_producto.producto_id AND lista_precios.presentacion_id = lote_presentacion_producto.presentacion_id INNER JOIN proveedors ON proveedors.id = lista_precios.proveedor_id WHERE lotes.cantidad = 0 GROUP BY proveedors.razon_social;'
            ],
            [
                'nombre'  => 'Producto por vencimientos a largo plazo',
                'query'   => 'SELECT productos.droga, CONCAT(presentacions.forma, " ", presentacions.presentacion) AS presentación, TIMESTAMPDIFF(MONTH, lotes.fecha_vencimiento, lotes.fecha_compra)AS días FROM lote_presentacion_producto INNER JOIN productos on productos.id = lote_presentacion_producto.producto_id INNER JOIN presentacions on presentacions.id = lote_presentacion_producto.presentacion_id INNER JOIN lotes on lotes.id = lote_presentacion_producto.lote_id ORDER BY días DESC;'
            ],
            [
                'nombre'  => 'Proveedores por mayor volumen de productos',
                'query'   => 'SELECT proveedors.razon_social, COUNT(productos.id) AS cant_productos FROM lote_presentacion_producto INNER JOIN productos on productos.id = lote_presentacion_producto.producto_id INNER JOIN presentacions on presentacions.id = lote_presentacion_producto.presentacion_id INNER JOIN lotes ON lotes.id = lote_presentacion_producto.id INNER JOIN lista_precios on lista_precios.producto_id = lote_presentacion_producto.producto_id AND lista_precios.presentacion_id = lote_presentacion_producto.presentacion_id INNER JOIN proveedors ON proveedors.id = lista_precios.proveedor_id GROUP BY proveedors.razon_social ORDER BY `cant_productos` DESC '
            ],
            [
                'nombre'  => 'Productos y sus vencimientos',
                'query'   => 'SELECT * FROM listados'
            ],
            [
                'nombre'  => 'Márgenes de ganancias por producto',
                'query'   => 'SELECT * FROM listados'
            ],
            [
                'nombre'  => 'Prooductos más vendidos',
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
            [
                'nombre'  => 'Productos por proveedor',
                'query'   => 'SELECT * FROM listados'
            ],
        ];

        Listado::insert($listados);
    }
}
