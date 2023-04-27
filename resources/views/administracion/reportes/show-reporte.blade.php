@extends('adminlte::page')

@section('title', 'Reporte {{$documento->nombre_documento}}')

@section('css')
@endsection

@section('content_header')
    <div class="row">
        <div class="col-xl-8">
            <h1>Reporte {{$documento->nombre_documento}}</h1>
        </div>
        <div class="col-xl-4 d-flex justify-content-xl-end">
            <a href="{{ route('administracion.reportes.index') }}" role="button" class="btn btn-md btn-secondary">Volver</a>
        </div>
    </div>
    <hr>
@endsection

@section('content')
    <div class="container bg-gradient-light">
        @foreach ($encabezados as $encabezado)
            {!! $encabezado !!}
        @endforeach

        ACÁ IRÍA EL REPORTE <br>

        @foreach ($campos_cuerpo as $campo)
            {!! $campo !!}
        @endforeach

        <h2>Listados adjuntos</h2>
        @foreach ($listados as $listado)
            {!! $listado !!}
        @endforeach
    </div>
@endsection

@section('js')
    @include('partials.alerts')
    <script type="text/javascript">
        $(document).ready(function() {

        });
    </script>
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión de software 2.8</b> (PHP: v{{phpversion()}} | LARAVEL: v.{{App::VERSION()}})
    </div>
@endsection
