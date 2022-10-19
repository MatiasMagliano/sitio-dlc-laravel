<?php
//Agregado líena 8 y de 83-92
namespace App\Models;

use App\Models\LotePresentacionProducto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Presentacion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'presentacions';

    protected $fillable = [
        'forma', 'presentacion', 'hospitalario', 'trazabilidad'
    ];

    // Se definen las relaciones
    public function producto()
    {
        return $this->belongsToMany(
            Producto::class,
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

    public function dcc()
    {
        return $this->belongsToMany(
            DepositoCasaCentral::class,
            LotePresentacionProducto::class,
            '',
            'dcc_id'
        )->distinct();
    }

    public function productosCotizados(): HasMany
    {
        return $this->hasMany(ProductoCotizado::class);
    }

    public function listaprecios(): HasOne
    {
        return $this->hasOne(ListaPrecio::class);
    }

    // RELACIONES PARTICULARES
    //Devuelve todos los productos de una determinada presentacion
    public function productosDePresentacion($presentacion_id){
        return Producto::select('*')
        ->join('lote_presentacion_producto', 'lote_presentacion_producto.producto_id', '=', 'productos.id')
        ->where('lote_presentacion_producto.presentacion_id', $presentacion_id)
        ->get();
    }

    // devuelve todos los lotes por presentacion y producto
    public function lotesPorPresentacion($producto)
    {
        return Lote::select('*')
            ->leftJoin('lote_presentacion_producto', 'lotes.id', '=', 'lote_presentacion_producto.lote_id')
            ->where('lote_presentacion_producto.presentacion_id', '=', $this->id)
            ->where('lote_presentacion_producto.producto_id', '=', $producto)
            ->get();
    }

    //devuelve todos los proveedores por presentación y producto
    public function proveedoresPorPresentacion($producto){
        return Proveedor::select('*')
            ->join('lista_precios', 'lista_precios.proveedor_id', '=', 'proveedors.id')
            ->join('lote_presentacion_producto', 'lote_presentacion_producto.id', '=', 'lista_precios.lpp_id')
            ->where('lote_presentacion_producto.presentacion_id', '=', $this->id)
            ->where('lote_presentacion_producto.producto_id', '=', $producto)
            ->get();
    }

    //devuelve el depósito para la presentación y producto solicitado
    public function deposito($producto, $presentacion){
        $deposito = DepositoCasaCentral::find(
            LotePresentacionProducto::getIdDeposito($producto, $presentacion)
        );
        return $deposito;
    }
}
