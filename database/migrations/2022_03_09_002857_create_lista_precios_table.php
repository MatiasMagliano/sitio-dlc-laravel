<?php

use App\Models\Presentacion;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListapreciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lista_precios', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Producto::class)->constrained();
            $table->foreignIdFor(Presentacion::class)->constrained();
            $table->foreignIdFor(Proveedor::class)->constrained();
            $table->string('codigoProv', 18)->constrained();
            $table->decimal('costo', 10, 2);
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
        Schema::dropIfExists('lista_precios');
    }
}
