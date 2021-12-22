<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productos extends Model
{
    use HasFactory;

    //Se setean los campos "llenables" en masa
    /**
     * @var array
     */
    protected $fillable = [
        'droga',
        'laboratorio',
        'presentacion',
    ];

    //Se resguardan los campos protegidos
    /**
     * @var array
     */
    protected $hidden = [
        'lote',
        'precio',
        'vencimiento'
    ];

    //Se definen los campos casteables
    /**
     * @var array
     */
    protected $casts = [
        'precio' => 'float',
        'vencimiento' => 'datetime'
    ];

    //Se definen las relaciones
    /**
     * public function estado(){
     *  return $this->belongsTo('App\Models\Estado');
     * }
     */
}
