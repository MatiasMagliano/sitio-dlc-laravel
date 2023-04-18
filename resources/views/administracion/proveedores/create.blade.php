@extends('adminlte::page')

@section('title', 'Administrar Productos')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.css" />
    <style>
        @media (max-width: 600px) {
            .hide {
                display: none;
            }
        }
    </style>
@endsection

@section('content_header')
    <div class="row">
        <div class="col-md-10">
            <h1>Crear nuevo proveedor</h1>
        </div>
        <div class="col-md-2 d-flex justify-content-xl-end">
            <a href="{{ route('administracion.proveedores.index') }}" role="button"
                class="btn btn-md btn-secondary">Volver a proveedores</a>
        </div>
    </div>
@stop

{{-- a partir de acá --}}
<form action="{{ route('administracion.proveedores.create')}}" method="POST">
    @csrf
    <input type="text" name="razon_social" id="razon_social" placeholder="Nombre del proveedor" class="form-control">
    <input type="text" name="descripcion" id="descripcion" placeholder="Descripción" class="form-control mb-2">
    <button class="btn btn-primary btn-block" type="submit">Agregar proveedor</button>
</form>





@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión de software 2.8</b> (PHP: v{{phpversion()}} | LARAVEL: v.{{App::VERSION()}})
    </div>
@endsection
