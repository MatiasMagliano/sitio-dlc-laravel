<?php

use App\Models\Documento;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Listado;

// *** T A B L A   P I V O T ***

class CreateDocumentoListadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documento_listado', function (Blueprint $table) {
            $table->foreignIdFor(Documento::class)->constrained();
            $table->foreignIdFor(Listado::class)->constrained();;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documento_listado');
    }
}
