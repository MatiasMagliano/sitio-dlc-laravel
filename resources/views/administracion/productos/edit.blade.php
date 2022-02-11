@extends('adminlte::page')

@section('css')

@endsection

@section('title', 'Administración - Editar Producto')

@section('content_header')
<div class="row">
    <div class="row">
        <div class="col-md-10">
            <h1>Administración de productos | Crear producto</h1>
        </div>
        <div class="col-md-2 d-flex justify-content-end">
            <a href="{{ route('administracion.productos.index') }}" role="button"
                class="btn btn-md btn-secondary">Volver</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')
@endsection

@section('js')
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión</b> 2.0 (LARAVEL V.8)
    </div>
@endsection

