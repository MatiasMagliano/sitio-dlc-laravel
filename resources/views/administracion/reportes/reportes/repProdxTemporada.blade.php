@extends('adminlte::page')

@section('title', 'Reporte - '. $datos_membrete["nombre_reporte"])

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

    @foreach ($datosReorganizados as $periodo => $productos)
        <div class="card">
            <div class="card-head bg-gradient-gray p-3">
                <h3>{{ $periodo }}</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered" width="100%">
                    <thead>
                        <th>Producto</th>
                        <th>Cantidad</th>
                    </thead>
                    <tbody>
                        @foreach ($productos as $producto => $presentaciones)
                            <tr>
                                <td class="align-middle" width="70%">
                                    {{ $presentaciones['PRESENTACION'] }}
                                </td>
                                <td class="align-middle text-center" width="30%">
                                    {{ $presentaciones['CANTIDAD'] }}
                                </td>
                            </tr>
                        @endforeach
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
