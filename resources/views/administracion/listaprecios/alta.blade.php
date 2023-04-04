@extends('adminlte::page')

@section('title', 'Administrar Productos')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.css" />
    <style>
        .texto-header{padding:0 20px;height:60px;overflow-y:auto;/*font-size: 14px;*/font-weight:500;color:#000000;}
        .texto-header::-webkit-scrollbar{width:5px;background-color:#282828;}
        .texto-header::-webkit-scrollbar-thumb{background-color:#3bd136;}
        @media(max-width:600px){.hide{display:none;}}
    </style>
@endsection

@section('content_header')
    <div class="row">
        <div class="col-xl-8">
            <h1>Listado de Precios/ Alta de Proveedor</h1>
        </div>
        <div class="col-md-4 d-flex justify-content-xl-end">
            <a href="{{ route('administracion.listaprecios.index') }}" role="button" class="btn btn-md btn-secondary" title="Volver a Listados">Volver al Listado</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')
    @section('plugins.inputmask', true)
    @section('plugins.Datatables', true)
    @section('plugins.DatatablesPlugins', true)
    <div class="wrapper">
        <div class="card">
            <div class="card-body">
                @include('administracion.listaprecios.partials.form-altaproveedor')
            </div>
        </div>
    </div>
@endsection

@section('js')
    @include('partials.alerts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.js"></script>
    @include('administracion.listaprecios.js.listaprecios-alta')
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión</b> 2.0 (LARAVEL V.8)
    </div>
@endsection
