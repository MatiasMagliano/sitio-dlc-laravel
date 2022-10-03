<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Localidad extends Model
{
    public $timestamps = false;

    protected $table = 'localidades';

    protected $fillable = [
        'id',
        'provincia_id',
        'nombre',
        'departamento'
    ];

    // relaciones
    public function dde()
    {
        return $this->hasMany(DireccionEntrega::class);
    }

    public function provincia()
    {
        return $this->belongsTo(Provincia::class);
    }
}
