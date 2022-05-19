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

    //Obtiene precios
    public static function listarDescuentos($producto_id, $presentacion_id, $id)
    {   
        //Subconsulta para obtener porcentajes y multiplicarlos en los costos
        $descuentos = ProductoCotizado::select('producto_id','porcentaje_1','porcentaje_2','porcentaje_3','porcentaje_4','porcentaje_5') 
            ->join('cotizacions','producto_cotizados.cotizacion_id','=','cotizacions.id')  
            ->join('clientes','cotizacions.cliente_id','=','clientes.id') 
            ->join('esquema_precios','clientes.id','=','esquema_precios.cliente_id')
            ->where('cotizacions.id','=',$id);

        //Consulta para obtener mercadería y costos
        return LotePresentacionProducto::select('proveedors.razon_social','costo AS costo_1','costo AS costo_2','costo AS costo_3','costo AS costo_4','costo AS costo_5')
            ->join('lista_precios', 'lote_presentacion_producto.id','=','lista_precios.lpp_id')
            ->join('proveedors','lista_precios.proveedor_id','=','proveedors.id')

            //->leftjoin($descuentos, 'producto_cotizados', function($join){
            //    $join->on('lote_presentacion_producto.id','=','producto_cotizados.producto_id');
            //})
            ->where('lote_presentacion_producto.producto_id','=',$producto_id)
            ->where('lote_presentacion_producto.presentacion_id','=',$presentacion_id)
            ->get();
    }
}
