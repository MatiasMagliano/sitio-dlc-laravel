@extends('adminlte::page')

@section('css')

@endsection

@section('title', 'Administrar - Editar Producto')

@section('content_header')
    <div class="row">
        <div class="col-xl-8">
            <h1>Administración de productos - Edición: {{ $producto->droga }}</h1>
        </div>
        <div class="col-xl-4 d-flex justify-content-xl-end">
            <a href="{{ route('administracion.productos.index') }}" role="button" class="btn btn-md btn-secondary">Volver</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')
