@extends('adminlte::page')

@section('title', 'Administrar Productos')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.css" />
    <style>@media(max-width: 600px) {.hide {display: none;}}</style>
@endsection

@section('content_header')
    <div class="row">
        <div class="col-xl-8">
            @foreach ($proveedor as $proveedorItem)
                <h1>Lista de Precios de: {{ $proveedorItem->razon_social }}</h1>
            @endforeach
            
            </div>
            <div class="col-md-4 d-flex justify-content-xl-end">
                {{-- <a href="{{ route('administracion.listaprecios.index') }}" role="button" class="btn btn-md btn-success" style="margin-right:5px" title="Actualizar masivo">Actualizar listado</a> --}}
            <a href="{{ route('administracion.listaprecios.index') }}" role="button" class="btn btn-md btn-secondary" title="Volver a Listados">Volver al Listado</a>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row d-flex">
                <div class="col-8">
                    <h5 class="heading-small text-muted mb-1">Datos básicos del Proveedor</h5>
                </div>
            </div>
        </div>
        <div class="card-body">
            @include('administracion.listaprecios.partials.datatable-showheader')
        </div>
    </div>


    @section('plugins.Datatables', true)
    @section('plugins.DatatablesPlugins', true)
    <div class="wrapper">
        <div class="card">
            <div class="card-header">
                <div class="row d-flex">
                    <div class="col-10">
                        <h5 class="heading-small text-muted mb-1">Productos</h5>
                    </div>
                    <div class="col-2">
                        <button role="button" class="btn btn-sm btn-primary open_first_modal" data-toggle="modal" data-target="#modalAgregProducto" data-toggle="tooltip" data-placement="middle"
                            title="Agregtar producto" value="{{ $proveedor }}">
                        <i class="fas fa-plus"></i> Agregar Prodcuto
                    </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @include('administracion.listaprecios.partials.datatable-showitems')
            </div>
        </div>
    </div>
    @include('administracion.listaprecios.partials.modal-show')

@endsection


@section('js')
    @include('partials.alerts')
    <script type="text/javascript" src="{{ asset('js/datatables-spanish.js') }}" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.js"></script>
    @include('administracion.listaprecios.js.listaprecios-show')
@endsection


@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión</b> 2.0 (LARAVEL V.8)
    </div>
@endsection
