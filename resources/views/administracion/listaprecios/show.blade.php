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
        <a href="{{ route('administracion.listaprecios.index') }}" role="button" class="btn btn-md btn-success" style="margin-right:5px" title="Actualizar masivo">Actualizar listado</a>
        <a href="{{ route('administracion.listaprecios.index') }}" role="button" class="btn btn-md btn-secondary" title="Volver a Listados">Volver al Listado</a>
    </div>
</div>
@stop

@section('content')
{{--<div class="card">
    <div class="card-header">
        <div class="row d-flex">
            <div class="col-8">
                <h5 class="heading-small text-muted mb-1">Datos básicos del Proveedor</h5>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-responsive-md" width="100%">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Razón Social</th>
                    <th>CUIT</th>
                    <th>Dirección</th>
                    <th>Alta</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($proveedor as $proveedorItem)
                    <tr>
                        <td style="vertical-align: middle;">{{ $proveedorItem->id }}</td>
                        <td style="vertical-align: middle;">{{ $proveedorItem->razon_social }}</td>
                        <td style="vertical-align: middle;">{{ $proveedorItem->cuit }}</td>
                        <td style="vertical-align: middle;">{{ $proveedorItem->direccion }}</td>
                        <td style="vertical-align: middle;">{{ $proveedorItem->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>--}}

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
<div class="card">
    <div class="card-header">
        <div class="row d-flex">
            <div class="col-8">
                <h5 class="heading-small text-muted mb-1">Productos</h5>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table id="tablaProductos" class="table table-responsive-md table-bordered table-condensed" width="100%">
            <thead>
                <th>Código</th>
                <th>Droga</th>
                <th>Forma y Presentación</th>
                <th>Costo</th>
                <th>Ultima Actualización</th>
                <th>Acciones</th>
            </thead>
            <tbody>
                @foreach ($listaPrecios as $listaPrecio)
                    <tr>
                        <td style="vertical-align: middle;">{{ $listaPrecio->codigoProv }}</td>
                        <td style="vertical-align: middle;">{{ $listaPrecio->droga }}</td>
                        <td style="vertical-align: middle;">{{ $listaPrecio->forma }} | {{ $listaPrecio->presentacion }}</td>
                        <td style="vertical-align: middle;">$ {{ $listaPrecio->costo }}</td>
                        <td style="vertical-align: middle;">{{ $listaPrecio->updated_at }}</td>
                        <td style="vertical-align: middle; text-align:center;">
                            <a href="{{-- route('administracion.listaprecios.editar.lista', $listaPrecio->cuit, $listaPrecio->listaId) --}}"
                                class="btn btn-link" data-toggle="tooltip" data-placement="bottom"
                                title="Editar item">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <form action="{{-- route('administracion.listaprecios.destroy', $proveedorItem->itemId) --}}"
                                id="borrar-{{$listaPrecio->listaId}}" method="post" class="d-inline">
                                @csrf
                                @method('delete')
                                <button type="button" class="btn btn-link"
                                data-id ="{{ $listaPrecio->listaId }}" data-action="{{-- route('administracion.listaprecios.borrar.producto', ['cotizacion' => $cotizacion, 'productoCotizado' => $cotizado]) --}}"
                                data-toggle="tooltip" data-placement="bottom" title="Borrar item"
                                    onclick="borrarItemListado({{ $listaPrecio->id }})">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('js')
@include('partials.alerts')
<script type="text/javascript" src="{{ asset('js/datatables-spanish.js') }}" defer></script>
<script>
</script>
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión</b> 2.0 (LARAVEL V.8)
    </div>
@endsection
