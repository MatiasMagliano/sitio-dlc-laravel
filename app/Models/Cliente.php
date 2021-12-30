<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Cliente
 *
 * @property $id
 * @property $razon_social
 * @property $cuit
 * @property $email
 * @property $direccion
 * @property $updated_at
 * @property $created_at
 * @property $dado_de_baja
 * @property $fecha_baja
 *
 * @property Contacto[] $contactos
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Cliente extends Model
{

    static $rules = [
		'razon_social' => 'required',
		'cuit' => 'required',
		'email' => 'required',
    ];

    protected $perPage = 12;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['razon_social','cuit','email','direccion','dado_de_baja','fecha_baja'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contactos()
    {
        return $this->hasMany('App\Models\Contacto', 'cliente_id', 'id');
    }


}
