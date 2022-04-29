<?php

use App\Models\Cliente;
use App\Models\DireccionEntrega;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCotizacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cotizacions', function (Blueprint $table) {
            $table->id();
            $table->string('identificador');
            $table->foreignIdFor(User::class)->constrained();
            $table->foreignIdFor(Cliente::class)->constrained();
            //$table->foreignIdFor(DireccionEntrega::class)->constrained();
            $table->unsignedBigInteger('dde_id');
            $table->foreign('dde_id')->references('id')->on('direcciones_entrega');
            $table->decimal('monto_total', 10,2)->nullable()->default(0);
            //fechas importantes que pueden o no estar de acuerdo al estado
            $table->timestamp('finalizada')->nullable();
            $table->timestamp('presentada')->nullable();
            $table->timestamp('confirmada')->nullable();
            $table->timestamp('rechazada')->nullable();
            $table->foreignId('estado_id')->constrained('estados');
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
        Schema::dropIfExists('cotizacions');
    }
}
