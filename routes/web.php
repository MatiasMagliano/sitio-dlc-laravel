<?php
//Agregado líena 11-12 ; 46 ; 62-64
use App\Http\Controllers\Admin\RolController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Administracion\LoteController;
use App\Http\Controllers\Administracion\PresentacionController;
use App\Http\Controllers\Administracion\ProductoController;
use App\Http\Controllers\Administracion\ProveedorController;
use App\Http\Controllers\Administracion\TrazabilidadController;
use App\Http\Controllers\Administracion\CotizacionController;
use App\Http\Controllers\Administracion\ListaPrecioController;
use App\Models\Lote;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Yajra\Datatables\Datatables;

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
    Route::get('/productos/buscar', [ProductoController::class, 'buscar']);
    Route::get('/lotes/buscarLotes', [LoteController::class, 'buscarLotes'])->name('lotes.buscarLotes');
    Route::get('/listaprecios/actualizarLista', [ListaPrecioController::class, 'actualizarLista'])->name('listaprecios.actualizarLista');

    Route::post('borrar-lote', [LoteController::class, 'destroy']);

    // rutas de PRODUCTOS
    Route::resource('/productos', ProductoController::class);
    Route::get('/productos/buscar', [ProductoController::class, 'buscar'])->name('buscar.producto');

    // rutas de PROVEEDORES
    Route::resource('/proveedores', ProveedorController::class);

    // rutas de PRESENTACIONES
    Route::resource('/presentaciones', PresentacionController::class)->except('edit');

    // rutas de LOTES
    Route::resource('/lotes', LoteController::class)->except('destroy');

    // rutas de TRAZABILIDAD
    Route::resource('/trazabilidad', TrazabilidadController::class);

    // rutas especiales para LISTA DE PRECIOS
    Route::resource('/listaprecios', ListaPrecioController::class);

    Route::resource('/cotizaciones', CotizacionController::class)->except('edit', 'update');
    Route::get('/cotizaciones/{cotizacion}/finalizar', [CotizacionController::class, 'finalizar'])
        ->name('cotizaciones.finalizar');
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
});
