@extends('adminlte::page')

@section('title', 'Detalles del proveedor')

@section('content_header')
    <div class="row">
        <div class="col-xl-8">
            <h1>Detalles del proveedor</h1>
        </div>
        <div class="col-xl-4 d-flex justify-content-xl-end">
            {{-- para engañar al sistema, se hace un formulario por GET solamente con el botón x-adminlte-button --}}
            <form action="{{ route('administracion.productos.index') }}" method="get">
                <x-adminlte-button type="submit" label="Volver" class="bg-gray" />
            </form>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')
<h1>MÉTODOS NO IMPLEMENTADOS</h1>
<p><strong>Proveedor:</strong> {{ $proveedor->razonSocial }}</p>
<p><strong>Cuit:</strong> {{ $proveedor->cuit }}</p>
<p><strong>Contacto:</strong> {{ $proveedor->contacto }}</p>
<p><strong>Dirección:</strong> {{ $proveedor->direccion }}</p>
<p><strong>URL:</strong> <a href="{{ $proveedor->url }}" target="_blank">{{ $proveedor->razonSocial }}</a></p>
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión</b> 2.0 (LARAVEL V.8)
    </div>
@endsection
