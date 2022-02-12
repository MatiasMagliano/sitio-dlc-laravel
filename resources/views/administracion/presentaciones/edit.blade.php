@extends('adminlte::page')

@section('css')
    <style>
        tabla1.dataTable thead th {
            border-bottom: none;
        }

        tabla1.dataTable tfoot th {
            border-top: none;
            border-bottom: 1px solid  #111;
        }

        #datosLoteActivo {
            height: 50vh;
        }

        #datosLoteInactivo {
            display: none;
        }
    </style>
@endsection

@section('title', 'Administrar Presentaciones')

@section('content')
    <h1>{{ $producto->droga }}</h1>
    <h3>{{ $presentacion->forma }}, {{ $presentacion->presentacion }}</h3>
@endsection


@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión</b> 2.0 (LARAVEL V.8)
    </div>
@endsection
