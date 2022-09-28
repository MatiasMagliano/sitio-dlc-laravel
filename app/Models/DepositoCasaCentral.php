<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositoCasaCentral extends Model
{
    use HasFactory;

    protected $table = 'deposito_casa_centrals';

    protected $fillable = [
        'existencia', 'cotizacion', 'disponible',
    ];

    //SE DEFINEN RELACIONES
    public function presentacion()
    {
        return $this->belongsToMany(
            Presentacion::class,
            'lote_presentacion_producto',
            '',
            'dcc_id'
        );
    }

    public function scopeGetDepCasaCentral($query, $id){
        return $query->where('id', $id);
    }
}
