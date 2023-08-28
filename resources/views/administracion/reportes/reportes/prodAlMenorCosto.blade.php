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
            <th>Producto</th>
            <th>Proveedor menor costo</th>
            <th>Código de proveedor</th>
            <th>Precio</th>
        </thead>
        <tbody>
            @foreach ($datos as $producto)
                <tr>
                    <td class="align-middle" width="40%">
                        {{ $producto->PRODUCTO }} - {{ $producto->PRESENTACION }} ({{ $producto->TIPO }})
                    </td>
                    <td class="align-middle"  width="30%">
                        {{ $producto->PROVEEDOR }}
                    </td>
                    <td class="align-middle text-center"  width="15%">
                        {{ $producto->CODIGOPROV }}
                    </td>
                    <td class="align-middle text-center"  width="15%">
                        $ {{ number_format($producto->COSTO, 2, ',', '.') }}
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
