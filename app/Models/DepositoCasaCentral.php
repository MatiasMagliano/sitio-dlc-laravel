<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositoCasaCentral extends Model
{
    use HasFactory;

    protected $table = 'deposito_casa_centrals';

    protected $fillable = [
        'existencia', 'cotizacion', 'disponible',
    ];

    //SE DEFINEN RELACIONES
    public function producto()
    {
        return $this->belongsTo(
            Producto::class,
            LotePresentacionProducto::class
        )->distinct();
    }

    public function presentacion()
    {
        return $this->belongsToMany(
            Presentacion::class,
            LotePresentacionProducto::class
        )->distinct();
    }

    public function lote()
    {
        return $this->belongsToMany(
            Lote::class,
            LotePresentacionProducto::class
        )->distinct();
    }

    public function scopeGetDepCasaCentral($query, $id){
        return $query->where('id', $id);
    }

    public static function getDcc($producto_id, $presentacion_id){
        $dcc = DepositoCasaCentral::select('deposito_casa_centrals.*')
                ->join('lote_presentacion_producto','deposito_casa_centrals.id','lote_presentacion_producto.dcc_id')
                ->where('lote_presentacion_producto.producto_id',$producto_id)
                ->where('lote_presentacion_producto.presentacion_id',$presentacion_id)
                ->first();
        return $dcc;
    }
}
