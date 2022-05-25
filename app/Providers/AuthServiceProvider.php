<?php

namespace App\Providers;

use App\Http\Controllers\PoliticasUsuarios;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // define('nombre')
        Gate::define('es-sist-admin', function($user){
            return $user->tieneElRol('sist-admin');
        });

        Gate::define('es-administracion', function($user){
            return $user->tieneElRol('administracion');
        });

        Gate::define('es-expedicion', function($user){
            return $user->tieneElRol('expedicion');
        });
    }
}
