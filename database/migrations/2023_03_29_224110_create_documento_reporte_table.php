<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Documento;
use App\Models\Reporte;

// *** T A B L A   P I V O T ***

class CreateDocumentoReporteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documento_reporte', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Documento::class)->constrained();
            $table->foreignIdFor(Reporte::class)->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documento_reporte');
    }
}
