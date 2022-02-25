<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'identificador',
        'precioCompra',
        'desde',
        'hasta',
        'cantidad'
    ];

    //Se definen los campos casteables
    /**
     * @var array
     */
    protected $casts = [
        'precioCompra' => 'float',
        'desde' => 'datetime:Y-m-d',
        'hasta' => 'datetime:Y-m-d',
        'cantidad' => 'int'
    ];

    //Se definen las relaciones

    /**
     * Get the productos that owns the Lote
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productos(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * Get the presentacion that owns the Lote
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function presentacion(): BelongsTo
    {
        return $this->belongsTo(Presentacion::class);
    }

    /**
     * Get the proveedor that owns the Lote
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }
}
