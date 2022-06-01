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
        'identificador', 'user_id', 'cliente_id', 'dde_id', 'estado', 'motivo_rechazo'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'finalizada' => 'datetime',
        'presentada' => 'datetime',
        'confirmada' => 'datetime',
        'rechazada'  => 'datetime',
    ];

    // relaciones
    /**
     * Get the cliente that owns the Cotizacion
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
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

    /* --- RELACIONES ESPECIALES ---*/
    public function direccionEntrega($punto){
        return DireccionEntrega::select('*')
            ->join('clientes', 'direcciones_entrega.cliente_id', '=', 'clientes.id')
            ->join('cotizacions', 'cotizacions.cliente_id', '=', 'clientes.id')
            ->join('provincias', 'direcciones_entrega.provincia_id', '=', 'provincias.id')
            ->join('localidades', 'direcciones_entrega.localidad_id', '=', 'localidades.id')
            ->where('direcciones_entrega.id', '=', $punto)
            ->where('cotizacions.id', $this->id)
            ->get();
    }
}
