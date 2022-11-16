<?php

use App\Models\Cliente;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDireccionesEntregaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('direcciones_entrega', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Cliente::class)->constrained()->onDelete('cascade');
            $table->string('lugar_entrega'); // palabra clave como nombre general del punto de entrega
            $table->string('domicilio');
            $table->foreignId('provincia_id')->constrained('provincias');
            $table->foreignId('localidad_id')->constrained('localidades');
            $table->integer('mas_entregado');
            $table->string('condiciones')->nullable();
            $table->string('observaciones')->nullable();
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
        Schema::dropIfExists('direcciones_entrega');
    }
}
