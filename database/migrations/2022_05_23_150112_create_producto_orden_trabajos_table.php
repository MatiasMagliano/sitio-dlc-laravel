<?php

use App\Models\Lote;
use App\Models\OrdenTrabajo;
use App\Models\Presentacion;
use App\Models\Producto;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductoOrdenTrabajosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('producto_orden_trabajos', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(OrdenTrabajo::class)->constrained();
            $table->foreignIdFor(Producto::class)->constrained();
            $table->foreignIdFor(Presentacion::class)->constrained();
            $table->string('lotes');
            $table->unsignedInteger('cantidad');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('producto_orden_trabajos');
    }
}
