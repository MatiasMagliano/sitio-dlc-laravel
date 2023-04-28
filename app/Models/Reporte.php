<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    use HasFactory;

    protected $table = 'reportes';
    protected $fillable = ['nombre', 'estructura_html', 'querys'];
    protected $casts = ['querys' => 'array'];

    public function documento()
    {
        return $this->belongsToMany(Documento::class);
    }

    //A T R I B U T O S
    protected function querys(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }
}
