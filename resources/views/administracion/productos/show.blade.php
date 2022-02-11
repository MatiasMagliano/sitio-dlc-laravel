@extends('adminlte::page')

@section('title', 'Administración - Editar Producto')

@section('content_header')
    <div class="row">
        <div class="col-md-10">
            <h1>Administración de productos | Detalle del producto</h1>
        </div>
        <div class="col-md-2 d-flex justify-content-xl-end">
            <a href="{{ route('administracion.productos.index') }}" role="button"
                class="btn btn-md btn-secondary">Volver</a>
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
                    <h6 class="heading-small text-muted mb-1">DATOS DEL PRODUCTO</h6>
                </div>
                <div class="col-4 text-right">
                    <a href="{{ route('administracion.productos.create') }}" role="button"
                    class="btn btn-md btn-success">Crear producto</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="pl-lg-4">
                <div class="form-group">
                    <label for="input-droga">Droga</label>
                    <h3>{{ $producto->droga }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card-group">

        {{-- DATOS DE PROVEEDOR --}}
        <div class="card">
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
                <table id="tablaProveedores" class="display nowrap proveedor" style="width: 100%;">
                    <thead>
                        <th>Razón Social</th>
                        <th>CUIT</th>
                        <th>URL</th>
                    </thead>
                    <tbody>
                        @foreach ($producto->proveedores as $proveedor)
                            <tr>
                                <td>{{ $proveedor->razonSocial }}</td>
                                <td>{{ $proveedor->cuit }}</td>
                                <td><a href="{{ $proveedor->url }}" target="_blank">WEB</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- DATOS DE PRESENTACION --}}
        <div class="card">
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
                <table id="tablaPresentaciones" class="display nowrap" style="width: 100%;">
                    <thead>
                        <th>Forma Farmacéutica</th>
                        <th>Presentación</th>
                        <th>Detalles</th>
                    </thead>
                    <tbody>
                        @foreach ($producto->presentaciones as $presentacion)
                            <tr>
                                <td>{{ $presentacion->forma }}</td>
                                <td>{{ $presentacion->presentacion }}</td>
                                <td>
                                    @if ($presentacion->hospitalario || $presentacion->trazabilidad)
                                        @if ($presentacion->hospitalario)
                                            <strong>HOSPITALARIO </strong>
                                        @endif
                                        @if ($presentacion->trazabilidad)
                                            <strong>TRAZABLE</strong>
                                        @endif
                                    @else
                                        SIN DETALLES
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <br>

    {{-- DATOS DE LOTES --}}
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-8">
                    <h6 class="heading-small text-muted mb-1">LOTE/S</h6>
                </div>
                <div class="col-4 text-right">
                    <a href="{{ route('administracion.lotes.index') }}" class="btn btn-sm btn-info"
                        role="button"><i class="fas fa-plus fa-sm"></i>&nbsp;<span class="hide">lote</span></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table id="tablaLotes" class="display nowrap" style="width: 100%;">
                <thead>
                    <th>Identificador</th>
                    <th>Cantidad</th>
                    <th>Precio de Compra</th>
                    <th>Vencimiento</th>
                </thead>
                <tbody>
                    @foreach ($producto->lotes as $lote)
                        <tr>
                            <td>{{ $lote->identificador }}</td>
                            <td>{{ $lote->cantidad }}</td>
                            <td>{{ $lote->precioCompra }}</td>
                            <td>{{ $lote->hasta->format('m/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
