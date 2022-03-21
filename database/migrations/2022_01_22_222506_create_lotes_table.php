<?php

use App\Models\Presentacion;
use App\Models\Producto;
use App\Models\Proveedor;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lotes', function (Blueprint $table) {
            $table->id();
            $table->string('identificador', 50);
            $table->unsignedFloat('precio_compra');
            $table->unsignedInteger('cantidad');
            $table->dateTime('fecha_elaboracion');
            $table->dateTime('fecha_compra')->default(Carbon::now()->format('Y-m-d H:i:s'));
            $table->dateTime('fecha_vencimiento');
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
        Schema::dropIfExists('lotes');
    }
}
