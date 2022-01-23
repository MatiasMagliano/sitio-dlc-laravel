<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    //Se setean los campos "llenables" en masa
    /**
     * @var array
     */
    protected $fillable = [
        'droga'
    ];

    //Se definen los campos casteables
    /**
     * @var array
     */
    protected $casts = [
        'vencimiento' => 'datetime:Y-m-d'
    ];

    //Se definen las relaciones

    // En las relaciones MUCHOS-A-MUCHOS, donde hay una tabla pivot, se usa la relación belongsToMany en ambas tablas
    public function presentaciones(){
        return $this->belongsToMany(Presentacion::class);
    }

    public function proveedores(){
        return $this->belongsToMany(Proveedor::class);
    }

    // En este caso la relación es UNO-A-MUCHOS, por lo que se utiliza hasMany de un lado y belongsTo del otro
    public function lotes(){
        return $this->hasMany(Lote::class);
    }

    /**
     * public function estado(){
     *  return $this->belongsTo('App\Models\Estado');
     * }
     */
}
