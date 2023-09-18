<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
    public function presentacion()
    {
        return $this->belongsToMany(Presentacion::class, 'lote_presentacion_producto');
    }

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
    // devuelve un array con todos los lotes por presentacion y producto
    public static function lotesPorPresentacion($producto, $presentacion)
    {
        return DB::select(
            'SELECT
                l.*
            FROM lotes l
            INNER JOIN lote_presentacion_producto lpp on lpp.lote_id = l.id
            WHERE lpp.producto_id = ? AND lpp.presentacion_id = ?
            ORDER BY l.fecha_vencimiento ASC;',
            [$producto, $presentacion]
        );
    }

    // devuelve un array con todos los lotes por presentacion y producto SIN LOS LOTES BORRADOS
    public static function lotesPorPresentacionSinTrashed($producto, $presentacion)
    {
        return DB::select(
            'SELECT
                l.*
            FROM lotes l
            INNER JOIN lote_presentacion_producto lpp on lpp.lote_id = l.id
            WHERE lpp.producto_id = ? AND lpp.presentacion_id = ? AND l.deleted_at IS NULL
            ORDER BY l.fecha_vencimiento ASC;',
            [$producto, $presentacion]
        );
    }

    public static function restarCantidad($lote_id, $cantidad)
    {
        $lote = Lote::where('id', $lote_id)->first();
        if($lote->cantidad == 0)
        {
            $lote->deleted_at = Carbon::now();
        }
        else
        {
            $lote->decrement('cantidad', $cantidad);
        }
    }

    public static function promedioPrecioLotes($producto, $presentacion)
    {
        return Lote::select(DB::raw('round(AVG(precio_compra),2) as promedio_precio'))
            ->join('lote_presentacion_producto', 'lotes.id', '=', 'lote_presentacion_producto.lote_id')
            ->where('lote_presentacion_producto.presentacion_id', '=', $presentacion)
            ->where('lote_presentacion_producto.producto_id', '=', $producto)
            ->withTrashed()
            ->value('promedio_precio');
    }

    public function deposito(){
        return $this->belongsToMany(DepositoCasaCentral::class, 'lote_presentacion_producto', 'id', 'dcc_id');
    }

    public function descontarLotes(Lote $lote, $cantidad)
    {
        if($lote->cantidad > $cantidad)
        {
            $lote->cantidad -= $cantidad;
            return true;
        }
        else{
            return false;
        }
    }
}
