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
            $table->foreignIdFor(Producto::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Presentacion::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Lote::class)->constrained()->onDelete('cascade');
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
