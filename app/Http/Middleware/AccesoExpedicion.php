<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AccesoExpedicion
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // agrega el middleware necesario para permitir el rol EXPEDICION
        if(Gate::allows('es-administracion') || Gate::allows('es-expedicion')){
            return $next($request);
        }

        $request->session()->flash('error', 'Ud no tiene permisos para ver esta página.');
        return redirect('home');
    }
}
