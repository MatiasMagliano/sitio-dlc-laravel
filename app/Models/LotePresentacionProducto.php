<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class LotePresentacionProducto extends Model
{
    use HasFactory;

    protected $table = 'lote_presentacion_producto';

    protected $fillable = [
        'producto_id', 'presentacion_id', 'lote_id'
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
}
