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
use App\Models\Cotizacion;
use Illuminate\Support\Facades\Route;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;

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

Route::get('/home', function () {
    return view('home');
})->middleware(['auth'])->name('home');

require __DIR__ . '/auth.php';

// Rutas agrupadas al admin
Route::prefix('admin')->middleware(['auth', 'auth.esSistAdmin'])->name('admin.')->group(function () {
    Route::resource('/users', UserController::class);
    Route::resource('/roles', RolController::class);
});

Route::prefix('administracion')->middleware(['auth', 'auth.esAdministracion'])->name('administracion.')->group(function () {
    // rutas especiales para ajax de búsqueda y eliminación
    Route::get('/listaprecios/actualizarLista', [ListaPrecioController::class, 'actualizarLista'])->name('listaprecios.actualizarLista');

    // rutas de PRODUCTOS
    Route::get('/productos/ajaxBuscarProductos', [ProductoController::class, 'buscar'])->name('productos.ajax.obtener');
    Route::resource('/productos', ProductoController::class)->except('show');
    Route::get('/productos/{producto_id}/show/{presentacion_id}', [ProductoController::class, 'show'])->name('productos.show');

    // rutas de CLIENTES
    Route::get('/clientes/ajaxObtenerClientes', [ClienteController::class, 'obtenerClientes'])->name('clientes.ajax.obtener');
    Route::get('/clientes/ajaxObtenerLocalidades', [ClienteController::class, 'obtenerLocalidades'])->name('clientes.ajax.obtenerLocalidades');
    Route::get('/clientes/ajaxObtenerPuntosEntrega', [ClienteController::class, 'obtenerPuntosEntrega'])->name('clientes.ajax.obtenerPuntosEntrega');
    Route::resource('/clientes', ClienteController::class);
    Route::get('/clientes/agregarPuntoEntrega', [ClienteController::class, 'agregarPuntoEntrega'])->name('clientes.agregarPuntoEntrega');
    Route::post('/clientes/{cliente}/agregarPuntoEntrega', [ClienteController::class, 'guardarPuntoEntrega'])->name('clientes.guardarPuntoEntrega');
    Route::get('/clientes/{cliente}/editarPuntoEntrega/{ptoEntrega}', [ClienteController::class, 'editarPuntoEntrega'])->name('clientes.editarPuntoEntrega');
    Route::post('/clientes/{cliente}/editarPuntoEntrega/{ptoEntrega}', [ClienteController::class, 'updatePuntoEntrega'])->name('clientes.updatePuntoEntrega');

    // rutas de PROVEEDORES
    Route::resource('/proveedores', ProveedorController::class);

    // rutas de PRESENTACIONES
    Route::get('/presentaciones/ajaxObtenerProductos', [PresentacionController::class, 'obtenerProductos'])->name('presentaciones.ajax.obtenerProductos');
    Route::resource('/presentaciones', PresentacionController::class)->except('edit');

    // rutas de LOTES
    Route::get('/lotes/ajaxObtenerLotes', [LoteController::class, 'buscarLotes'])->name('lotes.ajax.obtener');
    Route::resource('/lotes', LoteController::class)->except('destroy');

    // rutas de TRAZABILIDAD
    Route::resource('/trazabilidad', TrazabilidadController::class);

    // rutas especiales para LISTA DE PRECIOS
    Route::resource('/listaprecios', ListaPrecioController::class);
    Route::get('/listaprecios/export', [ListaPrecioController::class, 'export'])->name('listaprecios.export');

    //Route::post('importListaPrecioExcel', 'ListaPrecioController@importExcel')->name('ListaPrecio.import.excel');

    // rutas de COTIZACIONES
    Route::get('/cotizaciones/{cotizacion}/finalizar', [CotizacionController::class, 'finalizar'])
        ->name('cotizaciones.finalizar');
    Route::get('/cotizaciones/{cotizacion}/generarpdf', [CotizacionController::class, 'generarpdf'])
    ->name('cotizaciones.generarpdf');
    Route::get('/cotizaciones/{cotizacion}/agregar/producto', [CotizacionController::class, 'agregarProducto'])
        ->name('cotizaciones.agregar.producto');
    Route::get('/cotizaciones/{cotizacion}/producto/{productoCotizado}/edit', [CotizacionController::class, 'editarProductoCotizado'])
        ->name('cotizaciones.editar.producto');
    Route::post('/cotizaciones/{cotizacion}/producto', [CotizacionController::class, 'guardarProductoCotizado'])
        ->name('cotizaciones.guardar.producto');
    Route::match(['put', 'patch'], '/cotizaciones/{cotizacion}/producto/{productoCotizado}', [CotizacionController::class], 'actualizarProductoCotizado')
        ->name('cotizaciones.actualizar.producto');
    Route::delete('/cotizaciones/{cotizacion}/producto/{productoCotizado}', [CotizacionController::class, 'borrarProductoCotizado'])
        ->name('cotizaciones.borrar.producto');
    Route::resource('/cotizaciones', CotizacionController::class)->except('edit', 'update');
});
