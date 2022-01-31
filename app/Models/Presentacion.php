<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presentacion extends Model
{
    use HasFactory;

    //Se setean los campos "llenables" en masa
    /**
     * @var array
     */
    protected $fillable = [
        'id'/*
        'forma',
        'presentacion',
        'hospitalario',
        'trazabilidad'*/
    ];

    // Se definen las relaciones
    public function productos(){
        return $this->belongsToMany(Producto::class);
    }
}
