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
            <h1>Listado de Precios/ Nuevo Listado</h1>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <button type="submit" class="btn btn-sidebar btn-success">
                <i class="fas fa-share-square"></i>&nbsp;<span class="hide">Guardar</span>
            </button>
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
                @include('administracion.listaprecios.partials.form-proveedornew')
            </div>
        </div>
    </div>

    <div class="card-group">
        <div id="listitems" class="card mt-3">
            <div class="card-header">
                <div class="row d-flex">
                    <div class="col-8">
                        <h6 class="heading-small text-muted mb-1">PRODUCTOS</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @include('administracion.listaprecios.partials.form-detallelistaprecios')
            </div>
            <div class="overlay"><i class="fas fa-ban text-gray"></i></div>           
        </div>
    </div>
@endsection

@section('js')
    @include('partials.alerts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.js"></script>
    @include('administracion.listaprecios.js.listaprecios-new')
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión</b> 2.0 (LARAVEL V.8)
    </div>
@endsection
