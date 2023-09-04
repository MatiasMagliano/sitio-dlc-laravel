<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // configuración de bootstrap para el paginador
        Paginator::useBootstrap();

        // Configuración para fechas en español
        Carbon::setLocale(config('app.locale'));
        setlocale(LC_ALL, 'es_ES', 'es', 'ES', 'es_ES.utf8');
    }
}
