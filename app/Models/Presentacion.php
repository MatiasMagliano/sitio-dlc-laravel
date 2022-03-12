
<?php
//Agregado líena 8 y de 83-92
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Presentacion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'presentacions';

    //Se setean los campos "llenables" en masa
    /**
     * @var array
     */
    protected $fillable = [
        'forma', 'presentacion', 'stock', 'hospitalario', 'trazabilidad'
    ];

    // Se definen las relaciones

    /**
     * Get all of the lotes for the Presentacion
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lotes(): HasMany
    {
        return $this->hasMany(Lote::class);
    }

    // relación hacia proveedores, desde Presentacion
    /**
     * Get all of the proveedores for the Presentacion
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function proveedores(): HasManyThrough
    {
        return $this->hasManyThrough(
            Proveedor::class,
            Lote::class,
            'producto_id',
            'id',
            'id',
            'proveedor_id'
        );
    }

    /**
     * Get all of the productos for the Presentacion
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function productos(): HasManyThrough
    {
        return $this->hasManyThrough(
            Producto::class,
            Lote::class,
            'producto_id',
            'id',
            'id',
            'producto_id'
        );
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
