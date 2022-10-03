<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    public $timestamps = false;

    protected $table = 'provincias';

    protected $fillable = [
        'id',
        'nombre',
        'nombre_completo',
        'iso_id'
    ];

    // relaciones
    public function dde()
    {
        return $this->hasMany(DireccionEntrega::class);
    }

    public function localidades()
    {
        return $this->hasMany(Localidad::class);
    }
}
