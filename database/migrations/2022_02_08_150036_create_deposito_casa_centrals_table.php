<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepositoCasaCentralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposito_casa_centrals', function (Blueprint $table) {
            $table->id();
            $table->integer('existencia')->default(0);
            $table->integer('cotizacion')->default(0);
            $table->integer('disponible')->default(0);
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
        Schema::dropIfExists('deposito_casa_centrals');
    }
}
