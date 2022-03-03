<?php

use App\Http\Controllers\Admin\RolController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Administracion\LoteController;
use App\Http\Controllers\Administracion\PresentacionController;
use App\Http\Controllers\Administracion\ProductoController;
use App\Http\Controllers\Administracion\ProveedorController;
use App\Http\Controllers\Administracion\TrazabilidadController;
use App\Http\Controllers\Administracion\CotizacionController;
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
    Route::get('/buscar', [ProductoController::class, 'buscar']);
    Route::get('/lotes/buscarLotes', [LoteController::class, 'buscarLotes'])->name('lotes.buscarLotes');

    Route::post('borrar-lote', [LoteController::class, 'destroy']);

    Route::resource('/productos', ProductoController::class);
    Route::resource('/proveedores', ProveedorController::class);

    // rutas especiales para PRESENTACIONES
    Route::resource('/presentaciones', PresentacionController::class)->except('edit');
    Route::get('/presentaciones/{idProducto}/{idPresentacion}', [PresentacionController::class, 'edit'])->name('presentaciones.edit');

    // rutas especiales para LOTES
    Route::resource('/lotes', LoteController::class)->except('destroy');

    Route::resource('/trazabilidad', TrazabilidadController::class);

    Route::resource('/cotizaciones', CotizacionController::class)->except('edit', 'update');
    Route::get('/cotizaciones/{cotizacion}/finalizar', [CotizacionController::class, 'finalizar'])->name('cotizaciones.finalizar');
});
