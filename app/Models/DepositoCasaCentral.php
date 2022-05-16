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

    public function scopeGetDepCasaCentral($query, $id){
        return $query->where('id', $id);
    }
}
