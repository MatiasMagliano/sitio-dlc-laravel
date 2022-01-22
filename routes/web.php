<?php

use App\Http\Controllers\Admin\RolController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\TrazabilidadController;
use App\Models\Producto;
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
    Route::resource('/productos', ProductoController::class);
    Route::get('/buscar', [ProductoController::class, 'buscar']);
    Route::resource('/trazabilidad', TrazabilidadController::class);
    //Route::resource('/roles', RolController::class);
});
