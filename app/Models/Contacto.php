<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Contacto
 *
 * @property $id
 * @property $cliente_id
 * @property $nombre
 * @property $apellido
 * @property $cargo
 * @property $email
 * @property $telefono
 * @property $celular
 *
 * @property Cliente $cliente
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Contacto extends Model
{
    
    static $rules = [
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['cliente_id','nombre','apellido','cargo','email','telefono','celular'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cliente()
    {
        return $this->hasOne('App\Models\Cliente', 'id', 'cliente_id');
    }
    

}
