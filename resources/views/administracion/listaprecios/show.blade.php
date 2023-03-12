@extends('adminlte::page')

@section('title', 'Administrar Productos')

@section('css')
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
                <div class="col-8">
                    <h5 class="heading-small text-muted mb-1">Productos</h5>
                </div>
            </div>
        </div>
        <div class="card-body">
            @include('administracion.listaprecios.partials.datatable-showitems')
        </div>
    </div>
</div>

@endsection



@section('js')
@include('partials.alerts')
<script type="text/javascript" src="{{ asset('js/datatables-spanish.js') }}" defer></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.4/locale/es.js"></script>

<script>
    function borrarItemListado(item) {
        var name = document.getElementById('borrar-' + item).name;

        Swal.fire({
            icon: 'warning',
            title: 'Borrar Producto de Listado',
            text: 'Esta acción quitará el producto del listado del proveedor.',
            confirmButtonText: 'Borrar',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
                debugger;
                $('#borrar-' + item).submit();
                window.location.replace('{{ route('administracion.listaprecios.show', 'name' ) }}');
            }else if (
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                    'Cancelado',
                    'Operación cancelada por usuario, no se quita el producto listado de proveedor',
                    'error'
                )
            }
        });
    };
</script>

@endsection



@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión</b> 2.0 (LARAVEL V.8)
    </div>
@endsection
