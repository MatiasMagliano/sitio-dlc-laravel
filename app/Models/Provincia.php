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
}
