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
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'query'   => 'SELECT productos.droga, CONCAT(presentacions.forma, " ", presentacions.presentacion) AS "presentacion", proveedors.razon_social, lotes.precio_compra FROM lote_presentacion_producto
                INNER JOIN productos on productos.id = lote_presentacion_producto.producto_id INNER JOIN presentacions on presentacions.id = lote_presentacion_producto.presentacion_id INNER JOIN lotes ON lotes.id = lote_presentacion_producto.id INNER JOIN lista_precios on lista_precios.producto_id = lote_presentacion_producto.producto_id AND lista_precios.presentacion_id = lote_presentacion_producto.presentacion_id INNER JOIN proveedors ON proveedors.id = lista_precios.proveedor_id WHERE lotes.cantidad = 0 GROUP BY proveedors.razon_social;'
            ],
            [
                'nombre'  => 'Producto por vencimientos a largo plazo',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'query'   => 'SELECT productos.droga, CONCAT(presentacions.forma, " ", presentacions.presentacion) AS presentación, TIMESTAMPDIFF(MONTH, lotes.fecha_compra, lotes.fecha_vencimiento)AS meses FROM lote_presentacion_producto INNER JOIN productos on productos.id = lote_presentacion_producto.producto_id INNER JOIN presentacions on presentacions.id = lote_presentacion_producto.presentacion_id INNER JOIN lotes on lotes.id = lote_presentacion_producto.lote_id ORDER BY `productos`.`droga` ASC, meses DESC; '
            ],
            [
                'nombre'  => 'Proveedores por mayor volumen de productos',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'query'   => 'SELECT proveedors.razon_social, COUNT(productos.id) AS cant_productos FROM lote_presentacion_producto INNER JOIN productos on productos.id = lote_presentacion_producto.producto_id INNER JOIN presentacions on presentacions.id = lote_presentacion_producto.presentacion_id INNER JOIN lotes ON lotes.id = lote_presentacion_producto.id INNER JOIN lista_precios on lista_precios.producto_id = lote_presentacion_producto.producto_id AND lista_precios.presentacion_id = lote_presentacion_producto.presentacion_id INNER JOIN proveedors ON proveedors.id = lista_precios.proveedor_id GROUP BY proveedors.razon_social ORDER BY `cant_productos` DESC '
            ],
            [
                'nombre'  => 'Productos y sus vencimientos',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'query'   => 'SELECT productos.droga, CONCAT(presentacions.forma, " ", presentacions.presentacion) AS presentación, lotes.identificador, lotes.fecha_vencimiento FROM lote_presentacion_producto INNER JOIN productos on productos.id = lote_presentacion_producto.producto_id INNER JOIN presentacions on presentacions.id = lote_presentacion_producto.presentacion_id INNER JOIN lotes on lotes.id = lote_presentacion_producto.lote_id ORDER BY `productos`.`droga` ASC, lotes.fecha_vencimiento DESC; '
            ],
            [
                'nombre'  => 'Márgen de ganancia por producto, con precio de cotización',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'query'   => 'SELECT productos.droga, CONCAT(presentacions.forma, " ", presentacions.presentacion) AS presentación, producto_cotizados.precio-lotes.precio_compra as margen, cotizacions.identificador FROM producto_cotizados INNER JOIN lote_presentacion_producto ON lote_presentacion_producto.producto_id = producto_cotizados.producto_id AND lote_presentacion_producto.presentacion_id = producto_cotizados.presentacion_id INNER JOIN productos ON productos.id = lote_presentacion_producto.producto_id INNER JOIN presentacions ON presentacions.id = lote_presentacion_producto.presentacion_id INNER JOIN lotes on lotes.id = lote_presentacion_producto.lote_id INNER JOIN cotizacions ON cotizacions.id = producto_cotizados.cotizacion_id WHERE cotizacions.estado_id = 4 ORDER BY `productos`.`droga` ASC, margen DESC, `cotizacions`.`identificador` ASC; '
            ],
            [
                'nombre'  => 'Márgen de ganancia por producto, con precio de lista',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'query'   => 'SELECT productos.droga, CONCAT(presentacions.forma, " ", presentacions.presentacion) AS presentación, lotes.identificador, (lista_precios.costo+(lista_precios.costo*esquema_precios.porcentaje_1/100))-lotes.precio_compra as margen_descuento_1 FROM producto_cotizados INNER JOIN lote_presentacion_producto ON lote_presentacion_producto.producto_id = producto_cotizados.producto_id AND lote_presentacion_producto.presentacion_id = producto_cotizados.presentacion_id INNER JOIN lista_precios ON lista_precios.producto_id = producto_cotizados.producto_id AND lista_precios.presentacion_id = producto_cotizados.presentacion_id INNER JOIN productos ON productos.id = lote_presentacion_producto.producto_id INNER JOIN presentacions ON presentacions.id = lote_presentacion_producto.presentacion_id INNER JOIN lotes on lotes.id = lote_presentacion_producto.lote_id INNER JOIN cotizacions ON cotizacions.id = producto_cotizados.cotizacion_id INNER JOIN clientes ON clientes.id = cotizacions.cliente_id INNER JOIN esquema_precios ON esquema_precios.cliente_id = clientes.id ORDER BY `productos`.`droga` ASC;'
            ],
            [
                'nombre'  => 'Productos más vendidos',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'query'   => 'SELECT * FROM listados'
            ],
            [
                'nombre'  => 'Proveedores por volumen de lotes',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'query'   => 'SELECT * FROM listados'
            ],
            [
                'nombre'  => 'Cotizaciones brutas por usuario',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'query'   => 'SELECT users.name, COUNT(cotizacions.id) as cant_cotizaciones FROM cotizacions INNER JOIN users on users.id = cotizacions.user_id GROUP BY users.name ORDER BY `cantidad_cotizaciones` DESC '
            ],
            [
                'nombre'  => 'Cotizaciones aprobadas por usuario',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'query'   => 'SELECT * FROM listados'
            ],
            [
                'nombre'  => 'Procentual de cotizaciones por usuario',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'query'   => 'SELECT * FROM listados'
            ],
            [
                'nombre'  => 'Procentual de cotizaciones (aprobadas y rechazadas) por usuario',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'query'   => 'SELECT * FROM listados'
            ],
            [
                'nombre'  => 'Porcentual de cotizaciones (aprobada y rechazadas) valorizada por usuario',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'query'   => 'SELECT * FROM listados'
            ],
            [
                'nombre'  => 'Órdenes de trabajo generadas sin stock',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'query'   => 'SELECT * FROM listados'
            ],
            [
                'nombre'  => 'Productos por proveedor',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'query'   => 'SELECT * FROM listados'
            ],
        ];

        Listado::insert($listados);
    }
}
