<?php

use App\Models\Presentacion;
use App\Models\Producto;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresentacionProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presentacion_producto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('presentacion_id')->constrained()->onDelete('cascade');
            $table->foreignId('producto_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('presentacion_producto');
    }
}
