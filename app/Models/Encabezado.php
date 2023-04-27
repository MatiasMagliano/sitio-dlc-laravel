<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encabezado extends Model
{
    use HasFactory;

    protected $table = 'encabezados';
    protected $fillable = [
        'documento_id',
        'texto'
    ];

    public function documentos()
    {
        return $this->belongsToMany(Documento::class);
    }
}
