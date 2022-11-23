<?php
//Agregado líena 11-12 ; 46 ; 62-64

use App\Exports\ListaPrecioExport;
use App\Http\Controllers\Admin\RolController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Administracion\LoteController;
use App\Http\Controllers\Administracion\PresentacionController;
use App\Http\Controllers\Administracion\ProductoController;
use App\Http\Controllers\Administracion\ProveedorController;
use App\Http\Controllers\Administracion\TrazabilidadController;
use App\Http\Controllers\Administracion\CotizacionController;
use App\Http\Controllers\Administracion\ListaPrecioController;
use App\Http\Controllers\Administracion\ClienteController;
use App\Http\Controllers\Administracion\OrdenTrabajoController;
use App\Http\Controllers\Administracion\ReportesController;
use App\Http\Controllers\CalendarioController;
use App\Http\Controllers\DireccionEntregaController;
use App\Http\Controllers\HomeController;
use App\Models\DireccionEntrega;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [HomeController::class, 'index'])->middleware(['auth'])->name('home');

require __DIR__ . '/auth.php';

// Rutas agrupadas al admin
Route::prefix('admin')->middleware(['auth', 'auth.esSistAdmin'])->name('admin.')->group(function () {
    Route::resource('/users', UserController::class);
    Route::resource('/roles', RolController::class);
});

Route::prefix('administracion')->middleware(['auth', 'auth.esAdministracion'])->name('administracion.')->group(function () {

    // rutas de LISTA DE PRECIOS
    Route::get('/listaprecios/mostrarLista', [ListaPrecioController::class, 'mostrarLista'])->name('listaprecios.mostrarLista');
    Route::delete('/listaprecios', [ListaPrecioController::class, 'deleteList'])->name('listaprecios.deleteList');

    Route::get('/cotizaciones/{cotizacion}/agregar/producto/descuentos', [CotizacionController::class, 'preciosSugeridos'])
        ->name('cotizaciones.agregar.producto.descuentos');

    // rutas de PRODUCTOS
    Route::get('productos/ajaxProveedores', [ProductoController::class, 'ajaxProveedores'])->name('productos.ajaxProveedores');
    Route::post('productos/ajaxNuevoProveedor', [ProductoController::class, 'ajaxNuevoPorveedor'])->name('productos.ajaxNuevoProveedor');
    Route::get('productos/ajaxdt', [ProductoController::class, 'ajaxdt'])->name('productos.ajax');
    Route::resource('productos', ProductoController::class)->except(['show', 'edit']);
            //crear post para guardar más proveedores
    Route::get('/productos/{producto_id}/show/{presentacion_id}', [ProductoController::class, 'show'])->name('productos.show');
    Route::get('/productos/{producto}/edit/{presentacion}', [ProductoController::class, 'edit'])->name('productos.edit');

    // rutas de CLIENTES
    Route::get('/clientes/ajaxObtenerClientes', [ClienteController::class, 'obtenerClientes'])->name('clientes.ajax.obtener');
    Route::get('/clientes/ajaxObtenerLocalidades', [ClienteController::class, 'obtenerLocalidades'])->name('clientes.ajax.obtenerLocalidades');
    Route::get('/clientes/ajaxObtenerDde', [ClienteController::class, 'obtenerDde'])->name('clientes.ajax.obtenerDde');;
    Route::resource('/clientes', ClienteController::class);

    // rutas de PROVEEDORES
    Route::get('/presentaciones/ajaxObtenerProveedores', [PresentacionController::class, 'obtenerProveedores'])
        ->name('presentaciones.ajax.obtenerProveedores');
    Route::resource('/proveedores', ProveedorController::class);

    // rutas de PRESENTACIONES
    Route::get('/presentaciones/ajaxObtenerProductos', [PresentacionController::class, 'obtenerProductos'])
        ->name('presentaciones.ajax.obtenerProductos');
    Route::resource('/presentaciones', PresentacionController::class)->except('edit');

    // rutas de LOTES
    Route::get('/lotes/ajaxObtenerLotes', [LoteController::class, 'buscarLotes'])->name('lotes.ajax.obtener');
    Route::delete('/lotes/eliminarLote', [LoteController::class, 'destroy'])->name('ajaxEliminar');
    Route::resource('/lotes', LoteController::class)->except('destroy');

    // rutas de DIRECCIONES DE ENTREGA
    Route::get('/dde/ajaxObtenerDde', [DireccionEntregaController::class, 'dtAjaxDde'])->name('dde.ajax.obtenerDde');
    Route::resource('/dde', DireccionEntregaController::class);

    // rutas de TRAZABILIDAD
    Route::resource('/trazabilidad', TrazabilidadController::class);

    // rutas especiales para LISTA DE PRECIOS
    Route::get('/listaprecios/show/{razon_social}', [ProductoController::class, 'show'])->name('listaprecios.show');
    Route::get('/listaprecios/mostrarLista', [ListaPrecioController::class, 'mostrarLista'])->name('listaprecios.mostrarLista');
    Route::get('/listaprecios/exportlist', [ListaPrecioController::class, 'exportlist'])->name('listaprecios.exportlist');

    Route::post('/listaprecios/create', [ListaPrecioController::class, 'addListadoProveedor'])->name('listaprecios.create');
    Route::resource('/listaprecios', ListaPrecioController::class)->except('edit', 'update');
    Route::delete('/listaprecios', [ListaPrecioController::class, 'deleteList'])->name('listaprecios.deleteList');

    // rutas de COTIZACIONES
    Route::get('/cotizaciones/{cotizacion}/finalizar', [CotizacionController::class, 'finalizar'])
        ->name('cotizaciones.finalizar');
    Route::post('/cotizaciones/{cotizacion}/aprobarCotizacion', [CotizacionController::class, 'aprobarCotizacion'])
        ->name('cotizaciones.aprobarCotizacion');
    Route::post('/cotizaciones/{cotizacion}/rechazarCotizacion', [CotizacionController::class, 'rechazarCotizacion'])
        ->name('cotizaciones.rechazarCotizacion');

            //manejo de PDF
    Route::get('/cotizaciones/{cotizacion}/generarpdf', [CotizacionController::class, 'generarpdf'])
        ->name('cotizaciones.generarpdf');
    Route::get('/cotizaciones/{cotizacion}/descargarpdf/{doc}', [CotizacionController::class, 'descargapdf'])
        ->name('cotizaciones.descargapdf');

            //edición del producto dentro de una cotización
    Route::get('/cotizaciones/loadProdCotiz', [CotizacionController::class, 'loadProdCotiz'])
        ->name('cotizaciones.loadProdCotiz');
    Route::get('/cotizaciones/{cotizacion}/agregar/producto', [CotizacionController::class, 'agregarProducto'])
        ->name('cotizaciones.agregar.producto');
    Route::get('/cotizaciones/ajaxProductos', [CotizacionController::class, 'ajaxSlimProducto'])
        ->name('cotizaciones.agregar.ajaxProductos');
    Route::post('/cotizaciones/{cotizacion}/producto', [CotizacionController::class, 'guardarProductoCotizado'])
        ->name('cotizaciones.guardar.producto');
    Route::get('/cotizaciones/edit', [CotizacionController::class, 'editarProductoCotizado'])
        ->name('cotizaciones.editar.producto');
    Route::match(['put', 'patch'], '/cotizaciones/actualizar', [CotizacionController::class, 'actualizarProductoCotizado'])
        ->name('cotizaciones.actualizar.producto');
    Route::delete('/cotizaciones/{cotizacion}/producto/{productoCotizado}', [CotizacionController::class, 'borrarProductoCotizado'])
        ->name('cotizaciones.borrar.producto');

            //ajax para index con serverside datatable
    Route::get('/cotizaciones/ajaxdt', [CotizacionController::class, 'ajaxdt'])->name('cotizaciones.ajax');
    Route::get('/cotizaciones/historico', [CotizacionController::class, 'historico'])
        ->name('cotizaciones.historico');
    Route::get('/cotizaciones/ajaxhistorico', [CotizacionController::class, 'historicoCotizaciones'])
        ->name('cotizaciones.ajax.historico');

    Route::resource('/cotizaciones', CotizacionController::class)->except('edit', 'update');

    // rutas de CALENDARIO DE VENCIMIENTOS
    Route::get('/calendarios/obtenerVencimientos', [CalendarioController::class, 'fechasVencimiento'])->name('ajax.obtener.vencimientos');
    Route::get('/calendarios/obtenerIniciadas', [CalendarioController::class, 'fechasIniciadas'])->name('ajax.obtener.iniciadas');
    Route::get('/calendarios/obtenerPresentadas', [CalendarioController::class, 'fechasPresentadas'])->name('ajax.obtener.presentadas');
    Route::get('/calendarios/obtenerConfirmadas', [CalendarioController::class, 'fechasConfirmadas'])->name('ajax.obtener.confirmadas');
    Route::get('/calendarios/obtenerRechazadas', [CalendarioController::class, 'fechasRechazadas'])->name('ajax.obtener.rechazadas');
    route::get('/calendarios/vencimiento', [CalendarioController::class, 'index'])->name('calendario.vencimientos');

    // rutas de REPORTES
    Route::get('reportes', [ReportesController::class, 'index'])->name('reportes');
});

// RUTAS PARA EXPEDICION y, se incluye administración en la configuración del GATE
Route::prefix('administracion')->middleware(['auth', 'auth.esExpedicion'])->name('administracion.')->group(function () {
    // rutas de ORDENES DE TRABAJO
    Route::get('/ordentrabajo/ajaxObtenerLineas', [OrdenTrabajoController::class, 'obtenerLineasCotizacion'])->name('cotizadas.ajax.obtener');
    Route::get('/ordentrabajo/{ordentrabajo}/descargarpdf/', [OrdenTrabajoController::class, 'descargapdf'])
        ->name('ordentrabajo.descargapdf');
    Route::get('/ordentabajo/{ordentrabajo}/asignarlotes/{producto}/{presentacion}', [OrdenTrabajoController::class, 'asignarLotes'])
        ->name('ordentrabajo.asignarlotes');
    Route::resource('/ordentrabajo', OrdenTrabajoController::class);
});
