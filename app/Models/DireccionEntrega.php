<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DireccionEntrega extends Model
{
    use HasFactory;

    protected $table = 'direcciones_entrega';

    protected $fillable = [
        'lugar_entrega', 'domicilio', 'condiciones', 'observaciones'
    ];

    /**
     * Get the clientes that owns the DireccionEntrega
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clientes(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }
}
