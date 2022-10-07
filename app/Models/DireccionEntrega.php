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
        'lugar_entrega', 'domicilio', 'condiciones', 'observaciones', 'mas_entregado'
    ];

    // RELACIONES
    public function cotizacion()
    {
        $this->belongsToMany(Cotizacion::class);
    }

    public function clientes()
    {
        return $this->belongsToMany(Cliente::class);
    }

    public function provincia()
    {
        return $this->belongsTo(Provincia::class);
    }

    public function localidad()
    {
        return $this->belongsTo(Localidad::class);
    }
}
