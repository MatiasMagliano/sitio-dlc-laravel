@extends('adminlte::page')

@section('title', 'Administración - Editar Producto')

@section('css')
    <style>

    </style>
@endsection

@section('content_header')
    <div class="row">
        <div class="col-md-8">
            <h1>Vista general de productos</h1>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <a href="{{ route('administracion.productos.index') }}" role="button" class="btn btn-md btn-secondary">Volver</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')

    {{-- NOMBRE DE LA DROGA --}}
    <div class="card">
        <div class="card-header">
            <div class="row d-flex">
                <div class="col-8">
                    <h6 class="heading-small text-muted mb-1">NOMBRE DEL PRODUCTO</h6>
                </div>
                <div class="col-4 text-right">
                    <a href="{{ route('administracion.productos.create') }}" role="button"
                    class="btn btn-md btn-success">Crear producto</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="pl-lg-4">
                <h2>{{ $producto->droga }}</h2>
            </div>
        </div>
    </div>

    <div class="card-group">

        {{-- DATOS DE PROVEEDOR --}}
        <div class="card mr-2">
            <div class="card-header">
                <div class="row">
                    <div class="col-8">
                        <h6 class="heading-small text-muted mb-1">PROVEEDOR</h6>
                    </div>
                    <div class="col-4 text-right">
                        <a href="{{ route('administracion.proveedores.create') }}" class="btn btn-sm btn-info"
                            role="button"><i class="fas fa-plus fa-sm"></i>&nbsp;<span class="hide">proveedor</span></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="tablaProveedores" class="table table-sm table-responsive-md border-bottom-0" width="100%">
                    <thead>
                        <th>Razón Social</th>
                        <th>CUIT</th>
                        <th>URL</th>
                    </thead>
                    <tbody>
                        @foreach ($producto->proveedores($presentacion_id) as $proveedor)
                            <tr>
                                <td>{{ $proveedor->razon_social }}</td>
                                <td>{{ $proveedor->cuit }}</td>
                                <td><a href="{{ $proveedor->url }}" target="_blank">WEB</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- DATOS DE PRESENTACION --}}
        <div class="card ml-2">
            <div class="card-header">
                <div class="row">
                    <div class="col-8">
                        <h6 class="heading-small text-muted mb-1">PRESENTACION</h6>
                    </div>
                    <div class="col-4 text-right">
                        <a href="{{ route('administracion.presentaciones.create') }}" class="btn btn-sm btn-info"
                            role="button"><i class="fas fa-plus fa-sm"></i>&nbsp;<span class="hide">presentación</span></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="tablaPresentaciones" class="table table-sm table-responsive-md border-bottom-0" width="100%">
                    <thead>
                        <th>Forma Farmacéutica</th>
                        <th>Presentación</th>
                        <th>Detalles</th>
                        <th></th>
                    </thead>
                    <tbody>
                        @foreach ($producto->presentaciones as $presentacion)
                            <tr>
                                <td>{{ $presentacion->forma }}</td>
                                <td>{{ $presentacion->presentacion }}</td>
                                <td>
                                    @if ($presentacion->hospitalario || $presentacion->trazabilidad)
                                        @if ($presentacion->hospitalario)
                                            HOSPITALARIO
                                        @endif
                                        @if ($presentacion->trazabilidad)
                                            TRAZABLE
                                        @endif
                                    @else
                                        SIN DETALLES
                                    @endif
                                </td>
                                <td><a href="#"><i class="fas fa-edit"></i></a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- DATOS DE LOTES --}}
    <div class="card mt-3">
        <div class="card-header">
            <div class="row">
                <div class="col-8">
                    <h6 class="heading-small text-muted mb-1">LOTES</h6>
                </div>
                <div class="col-4 text-right">
                    <a href="{{ route('administracion.lotes.index') }}" class="btn btn-sm btn-info"
                        role="button"><i class="fas fa-plus fa-sm"></i>&nbsp;<span class="hide">lote</span></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table id="tablaLotes" class="table table-sm table-responsive-md border-bottom-0" width="100%">
                <thead>
                    <th>Identificador</th>
                    <th>Cantidad</th>
                    <th>Precio de Compra</th>
                    <th>Vencimiento</th>
                    <th></th>
                </thead>
                <tbody>
                    @foreach ($producto->lotesDeProducto as $lote)
                        <tr>
                            <td>{{ $lote->identificador }}</td>
                            <td>{{ $lote->cantidad }}</td>
                            <td>{{ $lote->precio_compra }}</td>
                            <td>{{ $lote->fecha_vencimiento->format('m/Y') }}</td>
                            <td><a href="{{ route('administracion.lotes.edit', $producto->id) }}"><i class="fas fa-edit"></i></a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <h4>Total en stock: <strong>{{ $producto->lotesDeProducto->sum('cantidad') }}</strong></h4>
          </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('js/datatables-spanish.js') }}" defer></script>
    <script>
        $(document).ready(function() {
            $('#tablaProveedores').DataTable({
                "paging":    false,
                "ordering":  false,
                "info":      false,
                "searching": false
            });

            $('#tablaPresentaciones').DataTable({
                "paging":    false,
                "ordering":  false,
                "info":      false,
                "searching": false
            });

            $('#tablaLotes').DataTable({
                "paging":    false,
                "ordering":  false,
                "info":      false,
                "searching": false
            });
        });
    </script>
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión</b> 2.0 (LARAVEL V.8)
    </div>
@endsection
