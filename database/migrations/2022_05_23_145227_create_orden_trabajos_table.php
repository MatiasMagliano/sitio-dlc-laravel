<?php

use App\Models\Cotizacion;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdenTrabajosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orden_trabajos', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Cotizacion::class)->constrained();
            $table->foreignIdFor(User::class)->constrained();
            $table->date('plazo_entrega');
            $table->text('observaciones')->nullable();
            $table->boolean('lotes_completos')->default(0);
            $table->date('en_produccion');
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
        Schema::dropIfExists('orden_trabajos');
    }
}
