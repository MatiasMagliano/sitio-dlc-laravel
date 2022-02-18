<?php

use App\Models\Lote;
use App\Models\Presentacion;
use App\Models\Producto;
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
            $table->foreignIdFor(Presentacion::class)->constrained()->onDelete('cascade');
            $table->string('identificador');
            $table->float('precioCompra');
            $table->dateTime('desde')->default(Carbon::now()->format('Y-m-d H:i:s'));
            $table->dateTime('hasta');
            $table->integer('cantidad');
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
