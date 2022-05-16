<?php

use App\Models\Lote;
use App\Models\Presentacion;
use App\Models\Producto;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LotePresentacionProducto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lote_presentacion_producto', function(Blueprint $table){
            $table->id();
            $table->foreignIdFor(Producto::class)->constrained();
            $table->foreignIdFor(Presentacion::class)->constrained();
            $table->foreignIdFor(Lote::class)->constrained();
            $table->unsignedBigInteger('dcc_id');
            $table->foreign('dcc_id')->references('id')->on('deposito_casa_centrals');
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
        //
    }
}
