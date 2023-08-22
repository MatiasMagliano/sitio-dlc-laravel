@extends('adminlte::page')

@section('title', 'Reporte - Ventas por rango de fechas')

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
            <th>Fecha de aprobación</th>
            <th>Cliente</th>
            <th>Cantidad de líneas</th>
            <th>Importe</th>
        </thead>
        <tbody>
            @foreach ($datos as $venta)
                <tr>
                    <td class="align-middle text-center" width="10%">
                        {{ \Carbon\Carbon::parse($venta->FECHA_DE_APROBACION)->format('d/m/Y - H:i') }}
                    </td>
                    <td class="align-middle"  width="60%">
                        {{ $venta->CLIENTE }}
                    </td>
                    <td class="align-middle text-center"  width="15%">
                        {{ $venta->CANT_LINEAS }}
                    </td>
                    <td class="align-middle text-center"  width="15%">
                        $ {{ number_format($venta->IMPORTE, 2, ',', '.') }}
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
