<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_corto');
            $table->string('razon_social');
            $table->string('tipo_afip', 4)->default('cuit');
            $table->string('afip')->unique();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('contacto')->nullable();
            $table->timestamp('ultima_compra')->nullable();
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
        Schema::dropIfExists('clientes');
    }
}
