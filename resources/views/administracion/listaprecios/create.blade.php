@extends('adminlte::page')

@section('title', 'Administrar Productos')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.css" />
    <style>@media (max-width:600px){.hide{display:none;}}.error{font-size:12px;color:red;text-align:center;}</style>
@endsection

@section('content_header')
    <div class="row">
        <div class="col-md-8">
            <h1>Crear nuevo listado de precios de Proveedor</h1>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <a href="{{ route('administracion.listaprecios.index') }}" role="button" class="btn btn-md btn-secondary">Volver a Listados de Precios</a>
        </div>
    </div>
@stop

@section('content')
    {{-- NOMBRE DEL PROVEEDOR --}}
    <div class="card">
        <div class="card-header">
            <div class="row d-flex">
                <div class="col-8">
                    <div class="row">
                        <h6 class="heading-small text-muted mb-1">PROVEEDOR</h6>
                    </div>
                    <div class="row">
                        <p class="text-muted mb-1" style="font-size:12px;">* Debe seleccionar la razón social y agregar al menos un producto para guardar los cambios</p>
                    </div>
                </div>
                <div class="col-4 text-right">
                    <button id="guardarlista-btn" type="button" class="btn btn-sidebar btn-success">
                        <i class="fas fa-share-square"></i>&nbsp;<span class="hide">Guardar</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @include('administracion.listaprecios.partials.form-selectproveedor')
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.js"></script>
    @include('administracion.listaprecios.js.listaprecios-create')
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión</b> 2.0 (LARAVEL V.8)
    </div>
@endsection
