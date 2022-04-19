<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LotePresentacionProducto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'lote_presentacion_producto';

    protected $fillable = [
        'producto_id', 'presentacion_id', 'lote_id'
    ];

    // RELACIONES

    /**
     * The lotes that belong to the LotePresentacionProducto
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function lotes(): BelongsToMany
    {
        return $this->belongsToMany(Lote::class, 'lote_presentacion_producto');
    }
        /**
    * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
   public function productos(): BelongsToMany
   {
       return $this->belongsToMany(Producto::class, 'lote_presentacion_producto');
   }
}
