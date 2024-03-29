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
        'razon_social',
        'cuit',
        'contacto',
        'direccion',
        'url'
    ];


    public static function getDatosProveedor($razon_social){
        $proveedor = Proveedor::select('*')
        ->where('proveedors.razon_social','=', $razon_social)
        ->distinct()
        ->get();
        return $proveedor;
    }
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
