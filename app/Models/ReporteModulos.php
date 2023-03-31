<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteModulos extends Model
{
    use HasFactory;

    protected $table = 'reporte_modulos';
    protected $fillable = ['nombre', 'query'];

    public function reporte()
    {
        return $this->belongsToMany(
            Reporte::class,
            'reporte_reporte_modulo',
            'reporte_modulos_id',
            'reporte_id',
        );
    }
}
