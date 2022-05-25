<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    /**
     * Get all of the productos for the Cotizacion
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productos(): HasMany
    {
        return $this->hasMany(ProductoCotizado::class);
    }

    /**
     * Get the user that owns the Cotizacion
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function estado(){
        return $this->belongsTo(Estado::class);
    }

    /**
     * Get all of the archivos for the Cotizacion
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function archivos(): HasMany
    {
        return $this->hasMany(ArchivoCotizacion::class);
    }
    /* hasMany->ventas */
}
