<?php

namespace App\Models;

use App\Models\EsquemaPrecio;
use App\Models\ProductoCotizado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class LotePresentacionProducto extends Model
{
    use SoftDeletes;

    protected $table = 'lote_presentacion_producto';

    protected $fillable = [
        'producto_id', 'presentacion_id', 'lote_id', 'dcc_id'
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

    /**
        * The deposito that belong to the LotePresentacionProducto
        *
        * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
        */
    public function deposito(): BelongsToMany
    {
        return $this->belongsToMany(DepositoCasaCentral::class, 'lote_presentacion_producto', 'producto_id', 'presentacion_id');
    }

    //obtiene el deposito con local scope
    public static function getIdDeposito($producto, $presentacion){
        return DB::table('lote_presentacion_producto')
            ->where('producto_id', $producto)
            ->where('presentacion_id', $presentacion)
            ->pluck('dcc_id')
            ->get('0');
    }
}
