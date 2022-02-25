<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use HasFactory, SoftDeletes;

    //Se setean los campos "llenables" en masa
    /**
     * @var array
     */
    protected $fillable = [
        'droga'
    ];

    //Se definen las relaciones

    // En las relaciones MUCHOS-A-MUCHOS, donde hay una tabla pivot, se usa la relación belongsToMany en ambas tablas
    // Sin embargo, la relación se complica cuando se usa softDelete, ya que la tabla pivot también
    //está definida como softDelete, pero sin modelo.
    public function lotes(){
        return $this->belongsToMany(Lote::class, 'lote_producto')
        ->wherePivot('deleted_at', null)
        ->withTimestamps();
    }

    // Se puede definir un tipo de relación especial que es uno-a-muchos-atraves
    //para capturar la relación entre producto-lote y producto-proveedor
    /**
     * Get all of the presentaciones for the Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function presentaciones(): HasManyThrough
    {
        return $this->hasManyThrough(Comment::class, Post::class);
    }
    /*public function proveedores(){
        return $this->hasManyThrough(Proveedor::class, Presentacion::class, 'producto_id', 'presentacion_id', 'id', 'id');
    }*/

    // Método para eliminar las relaciones del modelo
    public static function boot(){
        parent::boot();

        static::deleting(function ($producto){

            // La eliminación, también debe respetar el softDelete, por lo que no se puede usar "detach"
            //se debe actualizar la columna "deleted_at"
            $producto->presentaciones()
                ->updateExistingPivot($producto->pivot->id, ['deleted_at' => Carbon::now()]);
        });
    }
}
