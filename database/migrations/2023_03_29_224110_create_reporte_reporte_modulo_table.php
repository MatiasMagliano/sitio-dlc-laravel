<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\ReporteModulos;
use App\Models\Reporte;

class CreateReporteReporteModuloTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reporte_reporte_modulo', function (Blueprint $table) {
            $table->foreignIdFor(Reporte::class)->constrained();
            $table->foreignIdFor(ReporteModulos::class)->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reporte_reporte_modulo');
    }
}
