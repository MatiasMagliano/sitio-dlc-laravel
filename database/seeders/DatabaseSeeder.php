<?php
//Agregado líena 5 ; 24
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use phpDocumentor\Reflection\Types\This;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        //tablas básicas
        $this->call(EstadoSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(RolSeeder::class);
        $this->call(ProductoSeeder::class);
        $this->call(ProveedorSeeder::class);
        //$this->call(PresentacionSeeder::class);
        //$this->call(LoteSeeder::class);
        $this->call(ProvinciasSeeder::class);
        $this->call(LocalidadSeeder::class);
        $this->call(ClientesSeeder::class);
        $this->call(EsquemaPrecioSeeder::class);
        $this->call(DireccionEntregaSeeder::class);

        //tablas pivot
        $this->call(RolUserSeeder::class);
        $this->call(LotePresentacionProductoSeeder::class);
        $this->call(LoteVencidosSeeder::class);

        //orden necesario de populado
        $this->call(ListaPrecioSeeder::class);
        $this->call(CotizacionSeeder::class);
        $this->call(CotizacionAprobSeeder::class);
        //$this->call(ReportesSeeder::class);
        //$this->call(ListadosSeeder::class);
    }
}
