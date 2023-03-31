<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    use HasFactory;

    public function modulos()
    {
        return $this->belongsToMany(
            ReporteModulos::class,
            'reporte_reporte_modulo',
            'reporte_id',
            'reporte_modulos_id'
        );
    }

    public function listados()
    {
        return $this->belongsToMany(
            Listado::class,
            'reporte_listados',
            'reporte_id',
            'listado_id'
        );
    }
}
