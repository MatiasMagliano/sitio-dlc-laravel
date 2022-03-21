<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresentacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presentacions', function (Blueprint $table) {
            $table->id();
            $table->string('forma');
            $table->string('presentacion');
            $table->integer('existencia')->default(0);
            $table->integer('cotizacion')->default(0);
            $table->integer('disponible')->default(0); // existencia-cotizacion
            $table->boolean('hospitalario');
            $table->boolean('trazabilidad');
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
        Schema::dropIfExists('presentacions');
    }
}
