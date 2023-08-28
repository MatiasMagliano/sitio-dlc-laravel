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

    <div class="card">
        <div class="card-header bg-gradient-gray">
            <h3>{{ $cliente->razon_social }} (<span class="text-uppercase">{{ $cliente->nombre_corto }}</span>)</h3>
            <span style="text-transform: uppercase;">{{ $cliente->tipo_afip }}: </span>
            {{ $cliente->afip }}
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped" width="100%">
                <thead class="bg-gradient-lightblue">
                    <th>Producto</th>
                    <th>Forma / Presentación</th>
                    <th>Cantidad</th>
                    <th>Importe</th>
                </thead>
                <tbody>
                    @foreach ($datos as $dato => $item)
                        <tr>
                            <td class="align-middle" width="20%">
                                {{ $item->PRODUCTO }}
                            </td>
                            <td class="align-middle" width="40%">
                                {{ $item->FORM_PRES }}
                            </td>
                            <td class="align-middle text-center" width="20%">
                                {{ $item->CANTIDAD }}
                            </td>

                            <td class="align-middle text-center" width="20%">
                                $ {{ number_format($item->IMPORTE, 2, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
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
