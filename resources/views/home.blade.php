@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Tablero inicial</h1>
@stop

@section('content')
{{-- aquí va contenido --}}
@endsection

@section('js')
    @include('partials.alerts')
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión</b> 2.0 (LARAVEL V.8)
    </div>
@endsection
