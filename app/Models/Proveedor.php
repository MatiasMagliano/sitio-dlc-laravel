<?php
//Agregado líena 9 ; 40-52
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Proveedor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'proveedors';

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


    /**
     * Get the user associated with the ListaProveedor
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function listaprecios(): HasOne
    {
        return $this->hasOne(Listaprecio::class);
        
    }


}
