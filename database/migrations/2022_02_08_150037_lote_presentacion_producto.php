<?php

use App\Models\Lote;
use App\Models\Presentacion;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\ListaPrecio;
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
            $table->foreignIdFor(Producto::class)->constrained();
            $table->foreignIdFor(Presentacion::class)->constrained();
            $table->foreignIdFor(Lote::class)->constrained();
            //$table->foreignIdFor(Proveedor::class)->constrained();
            //$table->foreignIdFor(ListaPrecio::class);
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
