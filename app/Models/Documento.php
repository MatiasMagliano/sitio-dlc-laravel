<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Documento extends Model
{
    use HasFactory;

    protected $table = 'documentos';
    protected $fillable = [
        'tipo_documento',
        'nombre_documento',
        'dirigido_a',
        'encabezado',
        'user_id'
    ];

    public function encabezados()
    {
        return $this->hasMany(Encabezado::class);
    }

    public function reportes()
    {
        return $this->belongsToMany(Reporte::class);
    }

    public function listados()
    {
        return $this->belongsToMany(Listado::class);
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function camposCuerpo()
    {
        return $this->hasMany(CampoCuerpo::class);
    }
}
