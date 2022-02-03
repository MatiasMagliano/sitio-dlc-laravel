<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    use HasFactory;

    public $timestamps = false;

    //Se setean los campos "llenables" en masa
    /**
     * @var array
     */
    protected $fillable = [
        'identificador',
        'precioCompra',
        'desde',
        'hasta',
        'cantidad'
    ];

    //Se resguardan los campos protegidos (ojo que cuando se toman datos por ajax, los campos hidden no se devuelven...)
    /**
     * @var array
     */
    /*protected $hidden = [
        'precioCompra',
        'desde',
        'hasta',
        'cantidad'
    ];*/

    //Se definen los campos casteables
    /**
     * @var array
     */
    protected $casts = [
        'precioCompra' => 'float',
        'desde' => 'datetime:Y-m-d',
        'hasta' => 'datetime:Y-m-d',
        'cantidad' => 'int'
    ];

    //Se definen las relaciones
    public function producto(){
        return $this->belongsTo(Producto::class);
    }

    // función útil para suma de lotes
    public function sumaLote($id){
        return $this->where('producto_id', '=', $id)->sum('cantidad');
    }
}
