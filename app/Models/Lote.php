<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        'identificador', 'precio_compra', 'cantidad', 'fecha_elaboracion', 'fecha_compra', 'fecha_vencimiento'
    ];

    //Se definen los campos casteables
    protected $casts = [
        'precio_compra' => 'float',
        'cantidad' => 'int',
        'fecha_elaboracion' => 'datetime',
        'fecha_compra' => 'datetime',
        'fecha_vencimiento' => 'datetime'
    ];

    //Se definen las relaciones

    /**
     * The presentacion that belong to the Lote
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function presentacion(): BelongsToMany
    {
        return $this->belongsToMany(Presentacion::class, 'lote_presentacion_producto');
    }
}
