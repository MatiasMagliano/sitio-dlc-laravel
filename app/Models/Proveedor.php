<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

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
    public function productos(){
        return $this->belongsToMany('App\Models\Producto');
    }
}
