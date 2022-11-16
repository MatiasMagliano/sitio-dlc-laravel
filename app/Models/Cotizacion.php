<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cotizacion extends Model
{
    use HasFactory;

    protected $table = 'cotizacions';

    protected $fillable = [
        'identificador', 'user_id', 'cliente_id', 'dde_id', 'estado_id', 'motivo_rechazo'
    ];

    public $sortable = [
        'updated_at',
        'cliente_id'
    ];

    protected $casts = [
        'created_at' => 'datetime:d-m-Y',
        'updated_at' => 'datetime:d-m-Y',
        'finalizada' => 'datetime:d-m-Y',
        'presentada' => 'datetime:d-m-Y',
        'confirmada' => 'datetime:d-m-Y',
        'rechazada'  => 'datetime:d-m-Y',
    ];

    // RELACIONES
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function productos(): HasMany
    {
        return $this->hasMany(ProductoCotizado::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function estado(){
        return $this->belongsTo(Estado::class);
    }

    public function archivos(): HasOne
    {
        return $this->hasOne(ArchivoCotizacion::class);
    }

    public function dde()
    {
        return $this->belongsTo(DireccionEntrega::class);
    }

    /* --- RELACIONES ESPECIALES ---*/
    public function direccionEntrega($punto)
    {
        return DireccionEntrega::select('*')
            ->join('clientes', 'direcciones_entrega.cliente_id', '=', 'clientes.id')
            ->join('cotizacions', 'cotizacions.cliente_id', '=', 'clientes.id')
            ->join('provincias', 'direcciones_entrega.provincia_id', '=', 'provincias.id')
            ->join('localidades', 'direcciones_entrega.localidad_id', '=', 'localidades.id')
            ->where('direcciones_entrega.id', '=', $punto)
            ->where('cotizacions.id', $this->id)
            ->get();
    }





    // event handlers para hacer el softdelete de las relaciones
    public static function boot() {
        parent::boot();

        static::deleting(function($cotizacion) {
            // BORRA todos los productos relacionados
            $cotizacion->productos->each->delete();
        });
    }
}
