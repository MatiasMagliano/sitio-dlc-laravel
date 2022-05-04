<?php

use App\Models\Cotizacion;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArchivoCotizacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archivo_cotizacions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Cotizacion::class)->constrained()->onDelete('cascade');
            $table->string('ruta');
            $table->string('nombre_archivo');
            $table->string('causa_subida');
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
        Schema::dropIfExists('archivo_cotizacions');
    }
}
