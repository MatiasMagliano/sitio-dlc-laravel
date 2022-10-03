<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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
}
