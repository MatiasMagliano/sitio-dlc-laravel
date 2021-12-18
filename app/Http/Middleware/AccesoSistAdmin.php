<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class AccesoSistAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(Gate::allows('es-sist-admin')){
            return $next($request);
        }

        $request->session()->flash('error', 'Ud no tiene permisos para ver esta página.');
        return redirect('home');
    }
}
