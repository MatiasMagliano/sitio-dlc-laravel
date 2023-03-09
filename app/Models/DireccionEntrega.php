<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DireccionEntrega extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'direcciones_entrega';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'cliente_id', 'lugar_entrega', 'domicilio', 'condiciones', 'provincia_id', 'localidad_id', 'observaciones', 'mas_entregado'
    ];

    // RELACIONES
    public function cotizacion()
    {
        $this->belongsToMany(Cotizacion::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
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
