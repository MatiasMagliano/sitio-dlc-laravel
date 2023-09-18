<?php

namespace App\Console\Commands;

use App\Models\DepositoCasaCentral;
use App\Models\LotePresentacionProducto;
use Illuminate\Console\Command;

class ActualizarDepositoCasaCentral extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deposito-casa-central:actualizar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza el modelo DepositoCasaCentral';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Obtener la clave de caché para el valor anterior
        $cacheKey = 'deposito_casa_central_previo';

        // Obtener el valor anterior de la caché o inicializarlo si no existe
        $valorAnterior = cache($cacheKey, function () {
            // Obtener todos los depósitos
            $depositos = DepositoCasaCentral::all();

            $valorAnterior = [];

            foreach ($depositos as $deposito) {
                $valorAnterior[$deposito->id] = [
                    'existencia' => $deposito->existencia,
                    'cotizacion' => $deposito->cotizacion,
                ];
            }

            return $valorAnterior;
        });

        // OBTIENE TODAS LAS LÍNEAS DEL DEPÓSITO
        $depositos = DepositoCasaCentral::all();

        foreach ($depositos as $deposito) {
            // Obtener el ID del depósito actual
            $depositoId = $deposito->id;

            // Consulta para obtener los lotes de producto asociados a este depósito
            $lotesProducto = LotePresentacionProducto::where('dcc_id', $depositoId)->get();

            // Inicializar la variable para la cantidad efectiva en existencia
            $existencia = 0;

            // Sumar las cantidades disponibles de los lotes de producto
            foreach ($lotesProducto as $lote) {
                $existencia += $lote->cantidad_disponible;
            }

            // Actualizar la columna EXISTENCIA en el depósito actual
            $deposito->update(['EXISTENCIA' => $existencia]);
        }

        foreach ($depositos as $deposito) {
            // Comparar con el valor anterior y actualizar solo si hay cambios
            if (
                $deposito->existencia !== $valorAnterior[$deposito->id]['existencia'] ||
                $deposito->cotizacion !== $valorAnterior[$deposito->id]['cotizacion']
            ) {
                $diferencia = $deposito->existencia - $deposito->cotizacion;
                $deposito->update(['disponible' => $diferencia]);

                // Actualizar el valor anterior en la caché
                $valorAnterior[$deposito->id] = [
                    'existencia' => $deposito->existencia,
                    'cotizacion' => $deposito->cotizacion,
                ];
            }
        }

        // Almacenar el nuevo valor anterior en la caché
        cache([$cacheKey => $valorAnterior], now()->addMinutes(7)); // Cambia el tiempo de expiración según tus necesidades
    }
}
