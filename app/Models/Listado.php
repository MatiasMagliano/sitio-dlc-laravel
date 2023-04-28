<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listado extends Model
{
    use HasFactory;

    protected $table = 'listados';
    protected $fillable = ['nombre', 'estructura_html', 'query'];

    public function documento()
    {
        return $this->belongsToMany(Documento::class);
    }
}
