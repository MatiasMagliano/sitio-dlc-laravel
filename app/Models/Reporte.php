<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    use HasFactory;

    protected $table = 'reportes';
    protected $fillable = ['nombre', 'querys'];

    public function documento()
    {
        return $this->belongsToMany(Documento::class);
    }
}
