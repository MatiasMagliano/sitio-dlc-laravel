@extends('adminlte::page')

@section('title', 'Administrar Productos')

@section('css') {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.css" /> --}}
    <style>
        .dataTable>thead>tr>th[class*="sort"]:before,
        .dataTable>thead>tr>th[class*="sort"]:after{content:""!important;}
        #tabla1.dataTable tfoot th{border-top:none;border-bottom:1px solid #111;}
        #tabla2.dataTable{overflow:auto;}
        .search input[type=text]{font-size:17px;border:none;outline:none;}
        .search input[type=text]{font-size:17px;border:none;outline:rgba(0,0,0,0);}
        .search button {background:#fff;font-size:17px;border:none;opacity:0.4;}
        .hide{display:none}
        .LockCreate{display:none}
    </style>
@endsection

@section('content_header')
    <div class="row">
        <div class="col-xl-10">
            <h1> Listado de Precios</h1>
        </div>
        {{-- <div class="col-md-2 d-flex justify-content-md-end">
            <a id="ListasSinProductos" role="button" class="btn btn-sm LockCreate btn-primary">Agregar Listado</a>
        </div> --}}
        <div class="col-md-2 d-flex justify-content-md-end">
            <a href="{{ route('administracion.listaprecios.alta') }}" role="button" class="btn btn-sm btn-success"
                data-toggle="tooltip" data-placement="bottom" title="Agregar lista de precios">
                <i class="fas fa-plus"></i> Nuevo Listado</a>
            </a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')
    @section('plugins.Datatables', true)
    @section('plugins.DatatablesPlugins', true)
    @section('plugins.TempusDominusBs4', true)

    <div class="wrapper">
        <div class="card">
            <div class="card-header">
                <div class="mobile">
                    @include('administracion.listaprecios.partials.datatable-index')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @include('partials.alerts')
    <script type="text/javascript" src="{{ asset('js/datatables-spanish.js') }}" defer></script>
    @include('administracion.listaprecios.js.listaprecios-index')
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión</b> 2.0 (LARAVEL V.8)
    </div>
@endsection
