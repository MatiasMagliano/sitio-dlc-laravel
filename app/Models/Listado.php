<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listado extends Model
{
    use HasFactory;

    protected $table = 'listados';
    protected $fillable = ['nombre', 'query'];

    public function reporte()
    {
        return $this->belongsToMany(
            Reporte::class,
            'reporte_listados',
            'listado_id',
            'reporte_id',
        );
    }
}
