<?php

namespace App\Models;

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
    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(
            Producto::class,
            'lote_presentacion_producto',
            'id',
            'producto_id'
        );
    }

    public function presentaciones(): BelongsToMany
    {
        return $this->belongsToMany(
            Presentacion::class,
            'lote_presentacion_producto',
            'id',
            'presentacion_id'
        );
    }

    public function lotes(): BelongsToMany
    {
        return $this->belongsToMany(Lote::class, 'lote_presentacion_producto', 'lote_id');
    }

    public function depositos(): BelongsToMany
    {
        return $this->belongsToMany(DepositoCasaCentral::class, 'lote_presentacion_producto', 'dcc_id');
    }


    /* --- RELACIONES PARTICULARES --- */
    //obtiene el DEPOSITO relacionado a producto-presentacion
    public static function getIdDeposito($producto, $presentacion)
    {
        return DB::table('lote_presentacion_producto')
            ->where('producto_id', $producto)
            ->where('presentacion_id', $presentacion)
            ->pluck('dcc_id')
            ->get('0');
    }

    //obtiene los LOTES relacionados a producto-presentacion
    public static function getLotes($producto, $presentacion)
    {
        return DB::table('lote_presentacion_producto')
            ->join('lotes', 'lotes.id', '=', 'lote_presentacion_producto.lote_id')
            ->where('lote_presentacion_producto.producto_id', $producto)
            ->where('lote_presentacion_producto.presentacion_id', $presentacion)
            ->orderby('fecha_vencimiento')
            ->pluck('lote_id');
    }
}
