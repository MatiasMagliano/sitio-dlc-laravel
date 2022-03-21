<?php
//Agregado líena 8 y de 83-92
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Presentacion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'presentacions';

    //Se setean los campos "llenables" en masa
    /**
     * EXISTENCIA: SUMA DE TODOS LOS LOTES EXISTENTES
     * COTIZACION: número positivo con suma de los cotizado
     * DISPONIBLE: diferencia entre EXISTENCIA - COTIZACION
     * @var array
     */
    protected $fillable = [
        'forma', 'presentacion', 'existencia', 'cotizacion', 'disponible', 'hospitalario', 'trazabilidad'
    ];

    // Se definen las relaciones
    /**
     *
     * Devuelve todos los lotes de una presentacion en particular
     *
     */
    public function lotes(): BelongsToMany
    {
        return $this->belongsToMany(Lote::class, 'lote_presentacion_producto');
    }

    // devuelve todos los lotes de la presentacion en cuestión (modelo) y producto específico
    public function lotesPorPresentacion($producto)
    {
        return DB::table('lotes')
            ->leftJoin('lote_presentacion_producto', 'lotes.id', '=', 'lote_presentacion_producto.lote_id')
            ->where('lote_presentacion_producto.presentacion_id', '=', $this->id)
            ->where('lote_presentacion_producto.producto_id', '=', $producto)
            ->get();
    }

    /**
     * Get all of the productosCotizados for the Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productosCotizados(): HasMany
    {
        return $this->hasMany(ProductoCotizado::class);
    }

        /**
     * Get all of the productosCotizados for the Producto
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function listaprecios(): HasOne
    {
        return $this->hasOne(ListaPrecio::class);
    }
}
