@extends('adminlte::page')

@section('title', $datos_membrete[0]["nombre_reporte"])

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

    @foreach ($clientes as $cliente)
        <div class="card">
            <div class="card-head bg-gradient-gray p-3">
                <h3>{{ $cliente->razon_social }} (<span class="text-uppercase">{{ $cliente->nombre_corto }}</span>)</h3>
                <span style="text-transform: uppercase;">{{ $cliente->tipo_afip }}: </span>
                {{ $cliente->afip }}
            </div>
            <div class="card-body">
                <table class="table table-bordered" width="100%">
                    <thead>
                        <th>Contacto</th>
                        <th>Puntos de Entrega (<span>{{ $cliente->dde->count() }}</span>)</th>
                        <th>Última Compra</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="align-middle" width="15%">
                                {{ $cliente->contacto }}
                                <br>
                                Tel: {{ $cliente->telefono }}
                                <br>
                                E-mail: {{ $cliente->email }}
                            </td>
                            <td class="align-middle" width="70%">
                                @foreach ($cliente->dde as $puntoEntrega)
                                    <strong>{{ $puntoEntrega->lugar_entrega }}</strong>, {{ $puntoEntrega->domicilio }} -
                                    {{ $puntoEntrega->localidad->nombre }}, {{ $puntoEntrega->provincia->nombre }} <br>
                                    <hr>
                                @endforeach
                            </td>
                            <td class="align-middle text-center" width="15%">
                                @if ($cliente->ultima_compra == null)
                                    NUNCA
                                @else
                                    {{ $cliente->ultima_compra->format('d/m/Y') }}
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
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
