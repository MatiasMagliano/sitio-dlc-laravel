<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre_corto', 'razon_social', 'tipo_afip', 'afip', 'telefono', 'email', 'contacto',
    ];

    // casteos
    protected $casts = [
        'ultima_cotizacion' => 'datetime',
        'ultima_compra'     => 'datetime'
    ];

    // RELACIONES
    public function cotizaciones()
    {
        return $this->hasMany(Cotizacion::class,);
    }

    public function esquemaPrecio()
    {
        return $this->hasOne(EsquemaPrecio::class);
    }

    public function dde(): HasMany
    {
        return $this->hasMany(DireccionEntrega::class);
    }

    // event handlers para hacer el softdelete de las relaciones
    public static function boot() {
        parent::boot();

        self::deleting(function($cliente) {
            // cotizaciones en estado pendiente
            $cliente->cotizaciones()->each(function($cotizacion) {
                if($cotizacion->estado_id == 1)
                {
                    $cotizacion->delete();
                }
            });

            // direcciones de entrega relacionados
            $cliente->dde->each->delete();

            // esquemas de precios relacionados
            $cliente->esquemaPrecio->delete();
        });
    }
}
