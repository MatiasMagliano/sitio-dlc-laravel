<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AgregaColumnasAPresentaciones extends Migration
{
    /**
     * Modifica la tabla Presentaciones, para tener en cuenta las columnas de HOSPITALARIO y TRAZABILIDAD
     *
     * @return void
     */
    public function up()
    {
        Schema::table('presentacions', function (Blueprint $table) {
            $table->boolean('hospitalario')->default(false);
            $table->boolean('trazabilidad')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('presentacions', function (Blueprint $table) {
            $table->dropColumn('hospitalario');
            $table->dropColumn('trazabilidad');
        });
    }
}
