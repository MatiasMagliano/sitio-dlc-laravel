<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampoCuerpo extends Model
{
    use HasFactory;

    protected $table = 'campo_cuerpos';
    protected $fillable = [
        'documento_id',
        'texto'
    ];

    public function documentos()
    {
        return $this->belongsToMany(Documento::class);
    }
}
