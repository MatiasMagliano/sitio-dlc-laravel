@extends('adminlte::page')

@section('title', $datos_membrete["nombre_reporte"])

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
            <th>Razón social</th>
            <th>E-mail/Sitio web</th>
            <th>Dirección</th>
        </thead>
        <tbody>
            @foreach ($proveedores as $proveedor)
                <tr>
                    <td class="align-middle">
                        {{ $proveedor->razon_social }} <br>
                        <span>CUIT:</span> {{ $proveedor->cuit }}
                    </td>
                    <td class="align-middle">
                        {{ $proveedor->contacto }} <br>
                        {{ $proveedor->url }}
                    </td>
                    <td>
                        {{ $proveedor->direccion }}
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
