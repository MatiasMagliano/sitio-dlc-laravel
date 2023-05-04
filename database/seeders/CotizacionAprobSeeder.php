<?php
// SEEDER QUE CREA ALGUNAS COTIZACIONES MÁS Y LAS APRUEBA O RECHAZA AUTOMÁTICAMENTE
namespace Database\Seeders;

use App\Http\Controllers\Administracion\CotizacionController;
use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\DireccionEntrega;
use App\Models\ProductoCotizado;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Faker\Factory as Faker;

class CotizacionAprobSeeder extends Seeder
{
    public function run()
    {
        $this->faker = Faker::create();
        foreach(Cliente::all() as $cliente)
        {
            $maxCotizaciones = rand(1, 3);
            for($i = 1; $i <= $maxCotizaciones; $i++){
                $dirEntrega = DireccionEntrega::where('cliente_id', '=', $cliente->id)
                    ->inRandomOrder()
                    ->first();
                $cotizacion = Cotizacion::factory()->create([
                    'cliente_id' => $cliente->id,
                    'dde_id' => $dirEntrega->id,
                ]);

                // se incrementa el ranking de puntos de entrega: "más entregado"
                $dirEntrega->increment('mas_entregado');

                $maxProductos = rand(10, 100);
                for($j = 1; $j <= $maxProductos; $j++){
                    ProductoCotizado::factory()->create([
                        'cotizacion_id' => $cotizacion->id
                    ]);
                }

                // PROCEDIMIENTO QUE APRUEBA O RECHAZA ALGUNAS COTIZACIONES
                $decision = rand(0, 1); // 0 --> rechaza, 1 --> aprueba
                if($decision){
                    // se crea una fecha aleatoria dentro del año anterior
                    $fecha = Carbon::parse($this->faker->dateTimeBetween('-1 years'));
                    $cotizacion->created_at = $fecha;
                    //se finaliza la cotización
                    $cotizacion->monto_total = $cotizacion->productos->sum('total');
                    $cotizacion->finalizada = $fecha;
                    $cotizacion->cliente->ultima_cotizacion = $fecha;
                    $cotizacion->estado_id = 2;

                    //se presenta y se aprueba
                    if (!$cotizacion->presentada) {
                        $fecha = $fecha->addDays(rand(1, 30));
                        $cotizacion->estado_id = 3;
                        $cotizacion->presentada = $fecha;

                        $fecha = $fecha->addDays(rand(1, 30));
                        $cotizacion->confirmada = $fecha;
                        $cotizacion->estado_id = 4;
                    }
                }
                else{
                    // se crea una fecha aleatoria dentro del año anterior
                    $fecha = Carbon::parse($this->faker->dateTimeBetween('-1 years'));
                    $cotizacion->created_at = $fecha;
                    //se finaliza la cotización
                    $cotizacion->monto_total = $cotizacion->productos->sum('total');
                    $cotizacion->finalizada = $fecha;
                    $cotizacion->cliente->ultima_cotizacion = $fecha;
                    $cotizacion->estado_id = 2;

                    if (!$cotizacion->presentada) {
                        $fecha = $fecha->addDays(rand(1, 30));
                        $cotizacion->estado_id = 3;
                        $cotizacion->presentada = $fecha;

                        $fecha = $fecha->addDays(rand(1, 30));
                        $cotizacion->rechazada = $fecha;
                        $cotizacion->estado_id = 5;
                        $cotizacion->motivo_rechazo = $this->faker->sentence(7, true);
                    }
                }
                $cotizacion->updated_at = $fecha;
            }
        }

    }
}
