<?php

use App\Models\Cotizacion;
use App\Models\Presentacion;
use App\Models\Producto;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductoCotizadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('producto_cotizados', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Cotizacion::class)->constrained();
            $table->foreignIdFor(Producto::class)->constrained();
            $table->foreignIdFor(Presentacion::class)->constrained();
            $table->unsignedInteger('cantidad');
            $table->unsignedDecimal('precio', 10, 2);
            $table->unsignedDecimal('total', 20, 2);
            $table->boolean("no_aprobado");
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
        Schema::dropIfExists('producto_cotizados');
    }
}
