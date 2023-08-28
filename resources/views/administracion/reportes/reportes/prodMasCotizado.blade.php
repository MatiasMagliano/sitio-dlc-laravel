@extends('adminlte::page')

@section('title', 'Reporte - '. $datos_membrete[0]["nombre_reporte"])

@section('css')
@endsection

@section('content_header')
    <div class="row">
        <div class="col-xl-8">
            <h1>Reportes</h1>
        </div>
        <div class="col-xl-4 d-flex justify-content-xl-end">
            <a href="{{ route('administracion.reportes.index') }}" role="button" class="btn btn-md btn-secondary">Volver</a>
        </div>
    </div>
    <hr>
@endsection

@section('content')
    {{-- INCLUSIÓN DEL MEMBRETE --}}
    @include('administracion.reportes.reportes.membrete', ['datos_membrete' => $datos_membrete])

    <table class="table table-bordered table-striped" width="100%">
        <thead class="bg-gradient-gray">
            <th>Producto y Presentación</th>
            <th>Tipo de producto</th>
            <th>Cant. veces cotizado</th>
            <th>Cant. veces APROBADO</th>
            <th>Cant. veces RECHAZADO</th>
        </thead>
        <tbody>
            @foreach ($datos as $producto)
                <tr>
                    <td class="align-middle" width="40%">
                        {{ $producto->PROD_PRES }}
                    </td>
                    <td class="align-middle"  width="20%">
                        {{ $producto->TIPO_PROD }}
                    </td>
                    <td class="align-middle text-center"  width="13%">
                        {{ number_format($producto->COTIZ, 0, ',', '.') }}
                    </td>
                    <td class="align-middle text-center"  width="13%">
                        {{ number_format($producto->APROB, 0, ',', '.') }}
                    </td>
                    <td class="align-middle text-center"  width="13%">
                        {{ number_format($producto->RECHA, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@section('js')
    @include('partials.alerts')
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión de software 2.8</b> (PHP: v{{ phpversion() }} | LARAVEL: v.{{ App::VERSION() }})
    </div>
@endsection
