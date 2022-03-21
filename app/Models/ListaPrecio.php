<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ListaPrecio extends Model
{
    use HasFactory;
    
    protected $table = 'lista_precios';
    //Llenables
    /**
     * @var array
     */
    protected $fillable = [
        'costo',
    ];



    /**
     * Get the user associated with the ListaProveedor
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function proveedores(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }

        /**
     * Get the user associated with the ListaProveedor
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function presentaciones(): BelongsTo
    {
        return $this->belongsTo(Presentacion::class);
    }



}
