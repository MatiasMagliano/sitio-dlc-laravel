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
                'query'   => 'SELECT CONCAT(productos.droga, ", ", presentacions.forma, " ", presentacions.presentacion) AS droga_presentacion, proveedors.razon_social AS proveido_por, lotes.precio_compra AS ultimo_precio FROM lote_presentacion_producto INNER JOIN productos on productos.id = lote_presentacion_producto.producto_id INNER JOIN presentacions on presentacions.id = lote_presentacion_producto.presentacion_id INNER JOIN lotes ON lotes.id = lote_presentacion_producto.id INNER JOIN lista_precios on lista_precios.producto_id = lote_presentacion_producto.producto_id AND lista_precios.presentacion_id = lote_presentacion_producto.presentacion_id INNER JOIN proveedors ON proveedors.id = lista_precios.proveedor_id WHERE lotes.cantidad = 0 ORDER BY droga_presentacion, proveedors.razon_social;'
            ],
            [
                'nombre'  => 'Producto por vencimientos a largo plazo',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'query'   => 'SELECT CONCAT(productos.droga, ", ", presentacions.forma, " ", presentacions.presentacion) AS droga_presentacion, DATE_FORMAT(lotes.fecha_vencimiento, "%d/%m/%Y") AS fecha_vencimiento, TIMESTAMPDIFF(MONTH, NOW(), lotes.fecha_vencimiento) AS meses_al_vencimiento FROM lote_presentacion_producto INNER JOIN productos on productos.id = lote_presentacion_producto.producto_id INNER JOIN presentacions on presentacions.id = lote_presentacion_producto.presentacion_id INNER JOIN lotes on lotes.id = lote_presentacion_producto.lote_id ORDER BY meses_al_vencimiento DESC;'
            ],
            [
                'nombre'  => 'Proveedores por mayor volumen de productos',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'query'   => 'SELECT proveedors.razon_social, COUNT(productos.id) AS cant_productos FROM lote_presentacion_producto INNER JOIN productos on productos.id = lote_presentacion_producto.producto_id INNER JOIN presentacions on presentacions.id = lote_presentacion_producto.presentacion_id INNER JOIN lotes ON lotes.id = lote_presentacion_producto.id INNER JOIN lista_precios on lista_precios.producto_id = lote_presentacion_producto.producto_id AND lista_precios.presentacion_id = lote_presentacion_producto.presentacion_id INNER JOIN proveedors ON proveedors.id = lista_precios.proveedor_id GROUP BY proveedors.razon_social ORDER BY `cant_productos` DESC '
            ],
            [
                'nombre'  => 'Productos y sus vencimientos',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'query'   => 'SELECT CONCAT(productos.droga, ", ", presentacions.forma, " ", presentacions.presentacion) AS droga_presentacion, lotes.identificador, DATE_FORMAT(lotes.fecha_vencimiento, "%d/%m/%Y") AS fecha_vencimiento FROM lote_presentacion_producto INNER JOIN productos on productos.id = lote_presentacion_producto.producto_id INNER JOIN presentacions on presentacions.id = lote_presentacion_producto.presentacion_id INNER JOIN lotes on lotes.id = lote_presentacion_producto.lote_id ORDER BY droga_presentacion ASC, fecha_vencimiento DESC;'
            ],
            [
                'nombre'  => 'Márgen de ganancia por producto, con precio de cotización',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'query'   => 'SELECT productos.droga, CONCAT(presentacions.forma, " ", presentacions.presentacion) AS presentación, producto_cotizados.precio-lotes.precio_compra as margen, cotizacions.identificador FROM producto_cotizados INNER JOIN lote_presentacion_producto ON lote_presentacion_producto.producto_id = producto_cotizados.producto_id AND lote_presentacion_producto.presentacion_id = producto_cotizados.presentacion_id INNER JOIN productos ON productos.id = lote_presentacion_producto.producto_id INNER JOIN presentacions ON presentacions.id = lote_presentacion_producto.presentacion_id INNER JOIN lotes on lotes.id = lote_presentacion_producto.lote_id INNER JOIN cotizacions ON cotizacions.id = producto_cotizados.cotizacion_id WHERE cotizacions.estado_id = 4 ORDER BY `productos`.`droga` ASC, margen DESC, `cotizacions`.`identificador` ASC; '
            ],
            [
                'nombre'  => 'Márgen de ganancia por producto, con precio de lista',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'query'   => 'SELECT CONCAT(productos.droga, ", ", presentacions.forma, " ", presentacions.presentacion) AS droga_presentacion, proveedors.razon_social AS proveedor, ROUND(lista_precios.costo, 2) AS precio_proveedor, ROUND((lista_precios.costo + lista_precios.costo * 30 / 100), 2) AS precio_de_lista, ROUND((lista_precios.costo + lista_precios.costo * 30 / 100) - (lista_precios.costo + lista_precios.costo * 30 / 100) * (esquema_precios.porcentaje_1 / 100), 2) AS precio_con_descuento_1, ROUND((lista_precios.costo + lista_precios.costo * 30 / 100) - (lista_precios.costo + lista_precios.costo * 30 / 100) * (esquema_precios.porcentaje_1 / 100) - AVG(lotes.precio_compra), 2) AS margen_ganancia_1, ROUND((lista_precios.costo + lista_precios.costo * 30 / 100) - (lista_precios.costo + lista_precios.costo * 30 / 100) * (esquema_precios.porcentaje_2 / 100), 2) AS precio_con_descuento_2, ROUND((lista_precios.costo + lista_precios.costo * 30 / 100) - (lista_precios.costo + lista_precios.costo * 30 / 100) * (esquema_precios.porcentaje_2 / 100) - AVG(lotes.precio_compra), 2) AS margen_ganancia_2, ROUND((lista_precios.costo + lista_precios.costo * 30 / 100) - (lista_precios.costo + lista_precios.costo * 30 / 100) * (esquema_precios.porcentaje_3 / 100), 2) AS precio_con_descuento_3, ROUND((lista_precios.costo + lista_precios.costo * 30 / 100) - (lista_precios.costo + lista_precios.costo * 30 / 100) * (esquema_precios.porcentaje_3 / 100) - AVG(lotes.precio_compra), 2) AS margen_ganancia_3, ROUND((lista_precios.costo + lista_precios.costo * 30 / 100) - (lista_precios.costo + lista_precios.costo * 30 / 100) * (esquema_precios.porcentaje_4 / 100), 2) AS precio_con_descuento_4, ROUND((lista_precios.costo + lista_precios.costo * 30 / 100) - (lista_precios.costo + lista_precios.costo * 30 / 100) * (esquema_precios.porcentaje_4 / 100) - AVG(lotes.precio_compra), 2) AS margen_ganancia_4, ROUND((lista_precios.costo + lista_precios.costo * 30 / 100) - (lista_precios.costo + lista_precios.costo * 30 / 100) * (esquema_precios.porcentaje_5 / 100), 2) AS precio_con_descuento_5, ROUND((lista_precios.costo + lista_precios.costo * 30 / 100) - (lista_precios.costo + lista_precios.costo * 30 / 100) * (esquema_precios.porcentaje_5 / 100) - AVG(lotes.precio_compra), 2) AS margen_ganancia_5 FROM lote_presentacion_producto INNER JOIN productos ON productos.id = lote_presentacion_producto.producto_id INNER JOIN presentacions ON presentacions.id = lote_presentacion_producto.presentacion_id INNER JOIN lotes ON lotes.id = lote_presentacion_producto.lote_id INNER JOIN lista_precios ON lista_precios.producto_id = lote_presentacion_producto.producto_id AND lista_precios.presentacion_id = lote_presentacion_producto.presentacion_id INNER JOIN proveedors ON proveedors.id = lista_precios.proveedor_id INNER JOIN clientes ON clientes.id = 3 INNER JOIN esquema_precios ON esquema_precios.cliente_id = clientes.id GROUP BY proveedor, droga_presentacion ORDER BY droga_presentacion, margen_ganancia_1 DESC, margen_ganancia_2 DESC, margen_ganancia_3 DESC, margen_ganancia_4 DESC, margen_ganancia_5 DESC;'
            ],
            [
                'nombre'  => 'Productos más vendidos',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'query'   => 'SELECT CONCAT(p.droga, ", " , pr.forma, " ", pr.presentacion, CASE WHEN pr.hospitalario = 1 THEN " - H" ELSE "" END, CASE WHEN pr.trazabilidad = 1 THEN " - T" ELSE "" END, CASE WHEN pr.divisible = 1 THEN " - D" ELSE "" END) AS producto, COUNT(CONCAT(p.droga, ", " , pr.forma, " ", pr.presentacion)) AS cant_productos FROM cotizacions co INNER JOIN producto_cotizados pco ON co.id = pco.cotizacion_id AND co.confirmada IS NOT NULL INNER JOIN productos p ON pco.producto_id = p.id INNER JOIN presentacions pr ON pco.presentacion_id = pr.id GROUP BY p.droga, pr.forma, pr.presentacion, pr.hospitalario, pr.trazabilidad, pr.divisible ORDER BY cant_productos DESC;'
            ],
            [
                'nombre'  => 'Proveedores por volumen de lotes',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'query'   => 'SELECT * FROM listados'
            ],
            [
                'nombre'  => 'Cotizaciones brutas por usuario',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'query'   => 'SELECT users.name, COUNT(cotizacions.id) as cant_cotizaciones FROM cotizacions INNER JOIN users on users.id = cotizacions.user_id GROUP BY users.name ORDER BY `cant_cotizaciones` DESC;'
                //PRODUCCIÓN (LÍNEAS COTIZADAS) POR USUARIO
                //SELECT u.name, COUNT(co.id) as cant_cotizaciones FROM cotizacions co INNER JOIN users u on u.id = co.user_id INNER JOIN producto_cotizados pco ON co.id = pco.cotizacion_id GROUP BY u.name ORDER BY cant_cotizaciones DESC;
            ],
            [
                'nombre'  => 'Cotizaciones aprobadas por usuario',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'query'   => 'SELECT u.name, COUNT(co.id) AS cant_cotizaciones FROM cotizacions co INNER JOIN users u ON co.user_id = u.id AND co.confirmada IS NOT NULL GROUP BY u.name ORDER BY cant_cotizaciones DESC;'
                //PRODUCCIÓN (LÍNEAS COTIZADAS APROBADAS) POR USUARIO
                //SELECT u.name, COUNT(co.id) AS cant_cotizaciones FROM cotizacions co INNER JOIN users u ON co.user_id = u.id AND co.confirmada IS NOT NULL INNER JOIN producto_cotizados pco ON co.id = pco.cotizacion_id GROUP BY u.name ORDER BY cant_cotizaciones DESC;
            ],
            [
                'nombre'  => 'Porcentual de cotizaciones por usuario',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'query'   => 'SELECT u.name, COUNT(u.id), TOT_CO.cotizaciones AS cant_cotizaciones, ROUND((COUNT(u.id) * 100 / TOT_CO.cotizaciones),2) AS porcentaje_co FROM cotizacions co INNER JOIN users u ON co.user_id = u.id CROSS JOIN(SELECT COUNT(*) AS cotizaciones FROM cotizacions) TOT_CO GROUP BY u.name ORDER BY porcentaje_co DESC;'
                //REALCIÓN DE PRODUCCIÓN (LÍNEAS COTIZADAS APROBADAS) POR USUARIO
                //SELECT u.name, COUNT(u.id), TOT_CO.cotizaciones AS cant_cotizaciones, TOT_LIN_CO.lineas_cotizadas , ROUND((COUNT(u.id) * 100 / TOT_LIN_CO.lineas_cotizadas),2) AS porcentaje_co FROM cotizacions co INNER JOIN users u ON co.user_id = u.id INNER JOIN producto_cotizados pco ON co.ID = pco.cotizacion_id CROSS JOIN(SELECT COUNT(*) AS cotizaciones FROM cotizacions) TOT_CO CROSS JOIN(SELECT COUNT(*) AS lineas_cotizadas FROM producto_cotizados) TOT_LIN_CO GROUP BY u.name ORDER BY porcentaje_co DESC;
            ],
            [
                'nombre'  => 'Porcentual de cotizaciones (aprobadas y rechazadas) por usuario',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'query'   => 'SELECT u.name, COUNT(co1.id) AS co_aprobadas, COUNT(co0.id) AS co_rechazadas, ROUND(COUNT(co1.id) * 100 / (COUNT(co1.id) + COUNT(co0.id)),2) AS porcentual_apr, ROUND(COUNT(co0.id) * 100 / (COUNT(co1.id) + COUNT(co0.id)),2) AS porcentual_rec FROM users u LEFT JOIN cotizacions co1 ON u.id = co1.user_id AND co1.confirmada IS NOT NULL LEFT JOIN cotizacions co0 ON u.id = co0.user_id AND co0.rechazada IS NOT NULL GROUP BY u.name ORDER BY co_aprobadas DESC, co_rechazadas ASC;'
                //REALCIÓN DE PRODUCCIÓN (LÍNEAS COTIZADAS APROVADAS Y RECHAZADAS) POR USUARIO
                //SELECT u.name, IFNULL(CO_AP.lineas,0) AS lineas_ap, IFNULL(CO_RE.lineas,0) AS lineas_re, CASE WHEN (IFNULL(CO_AP.lineas,0) + IFNULL(CO_RE.lineas,0)) <> 0 THEN ROUND(IFNULL(CO_AP.lineas,0) * 100 / (IFNULL(CO_AP.lineas,0) + IFNULL(CO_RE.lineas,0)),2) ELSE 0 END AS porcentual_apr, CASE WHEN (IFNULL(CO_AP.lineas,0) + IFNULL(CO_RE.lineas,0)) <> 0 THEN ROUND(IFNULL(CO_RE.lineas,0) * 100 / (IFNULL(CO_AP.lineas,0) + IFNULL(CO_RE.lineas,0)),2) ELSE 0 END AS porcentual_rec FROM users u LEFT JOIN( SELECT u.name, COUNT(pco.id) AS lineas FROM cotizacions co LEFT JOIN producto_cotizados pco ON co.id = pco.cotizacion_id INNER JOIN users u ON co.user_id = u.id AND co.confirmada IS NOT NULL GROUP BY u.name )CO_AP ON u.name = CO_AP.name LEFT JOIN( SELECT u.name, COUNT(pco.id) AS lineas FROM cotizacions co LEFT JOIN producto_cotizados pco ON co.id = pco.cotizacion_id INNER JOIN users u ON co.user_id = u.id AND co.rechazada IS NOT NULL GROUP BY u.name )CO_RE ON u.name = CO_RE.name GROUP BY u.name ORDER BY porcentual_apr DESC, porcentual_rec ASC;
            ],
            [
                'nombre'  => 'Porcentual de cotizaciones (aprobada y rechazadas) valorizada por usuario',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'query'   => 'SELECT * FROM listados'
                // PORCENTAJES IMPORTE SIN DISCRIMINAR ESTADO DE COTIZACIONES
                //SELECT u.name, SUM(co.monto_total) AS importe_usuario, TOT_CO.importe_total, ROUND(SUM(co.monto_total) * 100 / TOT_CO.importe_total, 3) AS porcentaje_usuario FROM cotizacions co INNER JOIN users u ON co.user_id = u.id CROSS JOIN(SELECT SUM(co.monto_total) AS importe_total FROM cotizacions co) TOT_CO GROUP BY u.name ORDER BY porcentaje_usuario DESC;
                // PORCENTAJES IMPORTE DISCRIMINADO POR APROVADAS Y RECHAZADAS (SE PUEDE AGREGAR EL RESTO DE ESTADOS)
                //SELECT u.name, TOT_CO.importe_total, IFNULL(ROUND(SUM(co1.monto_total) * 100 / TOT_CO.importe_total, 3),0) AS porc_aporv_usuario, IFNULL(ROUND(SUM(co0.monto_total) * 100 / TOT_CO.importe_total, 3),0) AS porc_recha_usuario FROM users u LEFT JOIN cotizacions co1 ON u.id = co1.user_id AND co1.confirmada IS NOT NULL LEFT JOIN cotizacions co0 ON u.id = co0.user_id AND co0.rechazada IS NOT NULL CROSS JOIN(SELECT SUM(co.monto_total) AS importe_total FROM cotizacions co) TOT_CO GROUP BY u.name ORDER BY porc_aporv_usuario DESC, porc_recha_usuario ASC;
            ],
            [
                'nombre'  => 'Órdenes de trabajo generadas sin stock',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'query'   => 'SELECT * FROM listados'
            ],
            [
                'nombre'  => 'Productos por proveedor',
                'estructura_html' => '<!DOCTYPE html><html><head><meta charset="UTF-8" /><title>title</title></head><body></body></html>',
                'query'   => 'SELECT pv.razon_social, COUNT(lp.id) AS cant_productos FROM lista_precios lp INNER JOIN proveedors pv ON lp.proveedor_id = pv.id GROUP BY pv.razon_social ORDER BY cant_productos DESC;'
            ],
        ];

        Listado::insert($listados);
    }
}
