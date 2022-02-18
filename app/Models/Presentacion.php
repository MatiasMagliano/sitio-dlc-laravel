<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Presentacion extends Model
{
    use HasFactory, SoftDeletes;

    //Se setean los campos "llenables" en masa
    /**
     * @var array
     */
    protected $fillable = [
        'forma',
        'presentacion',
        'hospitalario',
        'trazabilidad'
    ];

    // Se definen las relaciones

    // relación hacia arriba con PRODUCTO (muchos-a-muchos)
    public function productos(){
        return $this->belongsToMany(Producto::class, 'presentacion_producto')
        ->wherePivot('deleted_at', null)
        ->withTimestamps();
    }

    //relación hacia abajo con PROVEEDOR (uno-a-muchos)
    public function proveedores(){
        return $this->belongsToMany(Proveedor::class, 'presentacion_proveedor')
        ->wherePivot('deleted_at', null)
        ->withTimestamps();
    }

    //relación hacia abajo con LOTES (uno-a-muchos)
    public function lotes(){
        return $this->hasMany(Lote::class);
    }

    //relación hacia abajo con LISTA DE PRECIOS (uno-a-uno)
    /**
     * public function listasDePrecio(){
     *      return $this->belongsTo(Presentacion::class);
     * }
     */


    // Método para eliminar las relaciones del modelo
    public static function boot(){
        parent::boot();

        static::deleting(function ($presentacion){

            // softDelete, sólo de la relación (tabla pivot) de proveedores
            $presentacion->proveedores()
                ->updateExistingPivot($presentacion->pivot->id, ['deleted_at' => Carbon::now()]);
        });
    }
}
