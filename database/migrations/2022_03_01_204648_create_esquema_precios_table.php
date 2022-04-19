<?php

use App\Models\Cliente;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEsquemaPreciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('esquema_precios', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Cliente::class)->constrained()->onDelete('cascade');
            $table->float('porcentaje_1')->nullable()->default(0);
            $table->float('porcentaje_2')->nullable()->default(0);
            $table->float('porcentaje_3')->nullable()->default(0);
            $table->float('porcentaje_4')->nullable()->default(0);
            $table->float('porcentaje_5')->nullable()->default(0);
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
        Schema::dropIfExists('esquema_precios');
    }
}
