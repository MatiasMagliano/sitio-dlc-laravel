<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    use HasFactory;

    //Se setean los campos "llenables" en masa
    /**
     * @var array
     */
    protected $fillable = [
        'identificador'
    ];

    //Se resguardan los campos protegidos
    /**
     * @var array
     */
    protected $hidden = [
        'precioCompra',
        'desde',
        'hasta'
    ];

    //Se definen los campos casteables
    /**
     * @var array
     */
    protected $casts = [
        'precioCompra' => 'float',
        'desde' => 'datetime:Y-m-d',
        'hasta' => 'datetime:Y-m-d'
    ];

    //Se definen las relaciones
    public function producto(){
        return $this->belongsTo(Producto::class);
    }
}
