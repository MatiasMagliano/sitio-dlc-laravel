<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * esta función sirve para dejar claro en el modelo que tiene una relación una-a-muchos
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(){
        return $this->belongsToMany(Rol::class);
    }

    /**
     * chequea si el usuario logueado tiene el rol pedido por parámetro
     * @param string $rol
     * @return bool
     */
    public function tieneElRol(string $rol){
        return null !== $this->roles()->where('nombre', $rol)->first();
    }

    /**
     * chequea si el usuario logueado tiene los roles pedido por parámetro
     * @param array $rol
     * @return bool
     */
    public function tieneLosRoles(array $rol){
        return null !== $this->roles()->whereIn('nombre', $rol)->first();
    }
}
