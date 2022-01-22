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
        'vencimiento' => 'datetime:Y-m-d'
    ];

    //Se definen las relaciones
    public function presentaciones(){
        return $this->belongsToMany(Presentacion::class);
    }
    
    public function proveedores(){
        return $this->belongsToMany(Proveedor::class);
    }

    /**
     * public function estado(){
     *  return $this->belongsTo('App\Models\Estado');
     * }
     */
}
