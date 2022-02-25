<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
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
        return $this->hasMany(Lote::class);
    }

    /**
     * Get all of the presentaciones for the Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function presentaciones(): HasManyThrough
    {
        return $this->hasManyThrough(Presentacion::class, Lote::class);
    }

    /**
     * Get all of the proveedores for the Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function proveedores(): HasManyThrough
    {
        return $this->hasManyThrough(Proveedor::class, Lote::class);
    }
}
