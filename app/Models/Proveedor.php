<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proveedor extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'razonSocial',
        'cuit',
        'contacto',
        'direccion',
        'url'
    ];

    // Se definen las relaciones
    public function presentaciones(){
        return $this->belongsToMany(Presentacion::class, 'presentacion_proveedor')
        ->withPivot('deleted_at', null)
        ->withTimestamps();
    }
}
