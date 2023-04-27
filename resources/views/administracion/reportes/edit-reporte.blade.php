@extends('adminlte::page')

@section('title', 'Editar documento reporte o listado')

@section('css')
@endsection

@section('content_header')
    <div class="row">
        <div class="col-xl-8">
            <h1>Editar documento</h1>
        </div>
        <div class="col-xl-4 d-flex justify-content-xl-end">
            <a href="{{ route('administracion.reportes.index') }}" role="button" class="btn btn-md btn-secondary">Volver</a>
        </div>
    </div>
    <hr>
@endsection

@section('content')
    DOCUMENTO <br>
    {{$documento}}
    <hr>
    ENCABEZADOS <br>
    {{$documento->encabezados}}
    <hr>
    REPORTE <br>
    {{$documento->reportes}}
    <hr>
    CAMPOS CUERPOS <br>
    {{$documento->camposCuerpo}}
    <hr>
    LISTADOS <br>
    {{$documento->listados}}
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
