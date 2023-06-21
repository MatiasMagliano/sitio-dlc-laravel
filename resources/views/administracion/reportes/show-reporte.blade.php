@extends('adminlte::page')

@section('title', 'Visualización de reportes')

@section('css')
@endsection

@section('content_header')
    <div class="row">
        <div class="col-xl-8">
            <h1>Reporte de nombre: "{{$documento->nombre_documento}}"</h1>
        </div>
        <div class="col-xl-4 d-flex justify-content-xl-end">
            <a href="{{ route('administracion.reportes.index') }}" role="button" class="btn btn-md btn-secondary">Volver</a>
        </div>
    </div>
    <hr>
@endsection

@section('content')
    <div class="container bg-gradient-light">
        {{-- MEMBRETE ORIGINADO POR EL ENCABEZADO --}}
        @foreach ($encabezados as $encabezado)
            {!! $encabezado !!}
        @endforeach

        {{-- SUBMEMBRETE --}}
        <br>
        <table width="100%">
            <tr>
                <td><strong class="ml-5">Documento dirigido a: {{$documento->dirigido_a}}</strong></td>
                <td class="text-right">Córdoba, {{\Carbon\Carbon::now()->format("d/m/Y")}}</td>
            </tr>
        </table>
        <hr>

        {!! $reportes !!}

        <br>
        {{-- CAMPOS DEL CUERPO --}}
        @foreach ($campos_cuerpo as $campo)
            {!! $campo !!}
        @endforeach

        {{-- LISTADOS ADJUNTOS --}}
        <h2>Listados adjuntos</h2>
        @foreach ($listados as $listado)
            {!! $listado !!} <br>
        @endforeach
    </div>
@endsection

@section('js')
    @include('partials.alerts')
    @yield('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            @isset($reportes)

            @endisset
        });
    </script>
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión de software 2.8</b> (PHP: v{{phpversion()}} | LARAVEL: v.{{App::VERSION()}})
    </div>
@endsection
