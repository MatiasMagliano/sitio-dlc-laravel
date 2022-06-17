<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Lote extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'lotes';

    public $timestamps = false;

    //Se setean los campos "llenables" en masa
    /**
     * @var array
     */
    protected $fillable = [
        'identificador', 'precio_compra', 'cantidad', 'fecha_elaboracion', 'fecha_compra', 'fecha_vencimiento'
    ];

    //Se definen los datos tipo fecha
    protected $dates = [
        'fecha_elaboracion',
        'fecha_compra',
        'fecha_vencimiento',
        'deleted_at'
    ];

    //Se definen los campos casteables
    protected $casts = [
        'precio_compra' => 'float',
        'cantidad' => 'int',
        'fecha_elaboracion' => 'datetime',
        'fecha_compra' => 'datetime',
        'fecha_vencimiento' => 'datetime'
    ];

    //SE DEFINEN LAS RELACIONES
    public function lpp(): BelongsToMany
    {
        return $this->belongsToMany(
            LotePresentacionProducto::class,
            'lote_presentacion_producto',
            'lote_id',
            'id',
        );
    }

    // RELACIONES PARTICULARES
    // devuelve todos los lotes por presentacion y producto
    public static function lotesPorPresentacion($producto, $presentacion)
    {
        return Lote::select(
            'lotes.id AS id',
            'lotes.identificador',
            'lotes.precio_compra',
            'lotes.cantidad',
            'lotes.fecha_vencimiento',
            'lotes.fecha_compra',
            'lotes.deleted_at as eliminado'
            )
            ->leftJoin('lote_presentacion_producto', 'lotes.id', '=', 'lote_presentacion_producto.lote_id')
            ->where('lote_presentacion_producto.presentacion_id', '=', $presentacion)
            ->where('lote_presentacion_producto.producto_id', '=', $producto)
            ->withTrashed()
            ->get();
    }

    public function deposito(){
        return $this->belongsToMany(DepositoCasaCentral::class, 'lote_presentacion_producto', 'id', 'dcc_id');
    }
}
