<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lote extends Model
{
    use HasFactory, SoftDeletes;

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
    public function productos(){
        return $this->belongsTo(Producto::class);
    }

    /**
     * Get the Presentacion that owns the Lote
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function presentacion(): BelongsTo
    {
        return $this->belongsTo(Presentacion::class);
    }

    /**
     * Get the proveedores that owns the Lote
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function proveedores(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }
}
