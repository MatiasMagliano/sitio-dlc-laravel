@extends('adminlte::page')

@section('title', 'Administrar reportes')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.css" />
@endsection

@section('content_header')
    <div class="row">
        <div class="col-xl-8">
            <h1>Nuevo reporte</h1>
        </div>
        <div class="col-xl-4 d-flex justify-content-xl-end">
            <a href="{{ route('home') }}" role="button" class="btn btn-md btn-secondary">Volver</a>
        </div>
    </div>
    <hr>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <h4>Seleccione un reporte de la lista</h4>
        </div>
        <div class="row m-3">
            <div class="col">
                <h5 class="mt-3">Reportes de procesos y ventas</h5>
                <ul class="list-group">
    {{-- 1 ---}}   <li class="list-group-item"><a class="btn-link" data-toggle="modal" data-target="#md_pedprocxvend" style="cursor: pointer;">Pedidos procesados por vendedor</a></li>
    {{-- 2 ---}}   <li class="list-group-item"><a class="btn-link" data-toggle="modal" data-target="#md_pedidosrechazados" style="cursor: pointer;">Pedidos rechazados</a></li>
    {{-- 3 ---}}   <li class="list-group-item"><a class="btn-link" data-toggle="modal" data-target="#md_cuotavtasxvendedor" style="cursor: pointer;">Cuota de ventas por vendedor</a></li>
    {{-- 4 ---}}   <li class="list-group-item"><a class="btn-link" data-toggle="modal" data-target="#md_vtasxrangofechas" style="cursor: pointer;">Ventas por rango de fechas</a></li>
    {{-- 5 ---}}   <li class="list-group-item"><a class="btn-link" data-toggle="modal" data-target="#md_prodvendxcliente" style="cursor: pointer;">Productos vendidos por cliente</a></li>
    {{-- 6 ---}}   <li class="list-group-item"><a class="btn-link" data-toggle="modal" data-target="#md_vtasxtipoprod" style="cursor: pointer;">Ventas por tipo de producto</a></li>
                </ul>
            </div>
            <div class="col">
                <h5 class="mt-3">Reportes de productos</h5>
                <ul class="list-group">
    {{-- 7 ---}}   <li class="list-group-item"><a class="btn-link" data-toggle="modal" data-target="#md_prodmasvendido" style="cursor: pointer;">Productos más vendido</a></li>
    {{-- 8 ---}}   <li class="list-group-item"><a class="btn-link" data-toggle="modal" data-target="#md_prodmenosvendido" style="cursor: pointer;">Productos menos vendido</a></li>
    {{-- 9 ---}}   <li class="list-group-item"><a class="btn-link" href="{{ route('administracion.reportes.rep-lotes-y-stock') }}">Lotes y Stock</a></li>
    {{-- 10 --}}   <li class="list-group-item"><a class="btn-link" data-toggle="modal" data-target="#md_prodmascotizados" style="cursor: pointer;">Productos más cotizados</a></li>
    {{-- 11 --}}   <li class="list-group-item"><a class="btn-link" data-toggle="modal" data-target="#md_prodxtemp" style="cursor: pointer;">Productos por temporada</a></li>
                </ul>
            </div>
            <div class="col">
                <h5 class="mt-3">Reportes de clientes</h5>
                <ul class="list-group">
    {{-- 12 --}}   <li class="list-group-item"><a class="btn-link" href="{{ route('administracion.reportes.rep-clientes') }}">Clientes</a></li>
    {{-- 13 --}}   <li class="list-group-item"><a class="btn-link" href="{{ route('administracion.reportes.rep-clientes-mas-cotizados') }}">Clientes más cotizados</a></li>
    {{-- 14 --}}   <li class="list-group-item"><a class="btn-link" href="{{ route('administracion.reportes.rep-ord-de-trabajo') }}">Órdenes de trabajo</a></li>
                </ul>

                <h5 class="mt-3">Reportes de proveedores</h5>
                <ul class="list-group">
    {{-- 15 --}}   <li class="list-group-item"><a class="btn-link" href="{{ route('administracion.reportes.rep-proveedores') }}">Proveedores</a></li>
    {{-- 16 --}}   <li class="list-group-item"><a class="btn-link" data-toggle="modal" data-target="#md_prodporproveedor" style="cursor: pointer;">Productos por proveedor</a></li>
    {{-- 17 --}}   <li class="list-group-item"><a class="btn-link" href="{{ route('administracion.reportes.rep-prod-al-menor-costo') }}">Productos al menor costo</a></li>
                </ul>
            </div>
        </div>
    </div>

@section('plugins.DateRangePicker', true)
{{-- MODAL - 1-PEDIDOS PROCESADOS POR VENDEDOR --}}
<div class="modal fade" id="md_pedprocxvend">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-gradient-blue">
                <h5 class="modal-title">Pedidos procesados por vendedor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('administracion.reportes.rep-ped-proc-x-vendedor') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="sel_fecha">Seleccione un rango de fechas:</label>
                        <x-adminlte-date-range name="sel_fecha" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Aceptar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL - 2-PEDIDOS RECHAZADOS --}}
<div class="modal fade" id="md_pedidosrechazados">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-gradient-blue">
                <h5 class="modal-title">Pedidos rechazados</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('administracion.reportes.rep-pedidos-rechazados') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="sel_fecha_rechazados">Seleccione un rango de fechas:</label>
                        <x-adminlte-date-range name="sel_fecha_rechazados" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Aceptar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL - 3-CUOTA DE VENTAS POR VENDEDOR --}}
<div class="modal fade" id="md_cuotavtasxvendedor">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-gradient-blue">
                <h5 class="modal-title">Cuota de ventas por vendedor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('administracion.reportes.rep-cuota-vtas-por-vendedor') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="sel_fecha_pedidos">Seleccione un rango de fechas:</label>
                        <x-adminlte-date-range name="sel_fecha_pedidos"  />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Aceptar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL - 4-VENTAS POR RANGO DE FECHAS --}}
<div class="modal fade" id="md_vtasxrangofechas">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-gradient-blue">
                <h5 class="modal-title">Ventas por rango de fechas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('administracion.reportes.rep-vtas-por-rango-fechas') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @section('plugins.DateRangePicker', true)
                    <div class="form-group">
                        <label for="sel_fecha_ventas">Seleccione un rango de fechas:</label>
                        <x-adminlte-date-range name="sel_fecha_ventas" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Aceptar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL - 5-PRODUCTOS VENDIDOS POR CLIENTE --}}
<div class="modal fade" id="md_prodvendxcliente">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-gradient-blue">
                <h5 class="modal-title">Productos vendidos por cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('administracion.reportes.rep-prod-vend-por-cliente') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        {{-- SLIM SELECT DE CLIENTES --}}
                        <label for="sel_cliente">Seleccione un cliente:</label>
                        <select class="sel-clientes-slim form-control-alternative" name="sel_cliente" id="sel_cliente"></select>
                      </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Aceptar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL - 6-VENTAS POR TIPO DE PRODUCTO --}}
<div class="modal fade" id="md_vtasxtipoprod">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-gradient-blue">
                <h5 class="modal-title">Ventas por tipo de producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('administracion.reportes.rep-vtas-por-tipo-prod') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="sel_tipo_prod">Seleccione un tipo de producto:</label>
                        <select class="form-control" name="sel_tipo_prod" id="sel_tipo_prod">
                          <option value="comun">Común</option>
                          <option value="divisible">Divisible</option>
                          <option value="hospitalario">Hospitalario</option>
                          <option value="trazable">Trazable</option>
                        </select>
                      </div>
                    <div class="form-group">
                        <label for="sel_fecha_ventas_por_tipo">Seleccione un rango de fechas:</label>
                        <x-adminlte-date-range name="sel_fecha_ventas_por_tipo" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Aceptar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL - 7-PRODUCTO MÁS VENDIDO --}}
<div class="modal fade" id="md_prodmasvendido">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-gradient-blue">
                <h5 class="modal-title">Producto más vendido</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('administracion.reportes.rep-prod-mas-vendido') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @section('plugins.DateRangePicker', true)
                    <div class="form-group">
                        <label for="sel_fecha_mas_vendido">Seleccione un rango de fechas:</label>
                        <x-adminlte-date-range name="sel_fecha_mas_vendido" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Aceptar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL - 8-PRODUCTO MENOS VENDIDO --}}
<div class="modal fade" id="md_prodmenosvendido">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-gradient-blue">
                <h5 class="modal-title">Producto menos vendido</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('administracion.reportes.rep-prod-menos-vendido') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @section('plugins.DateRangePicker', true)
                    <div class="form-group">
                        <label for="sel_fecha_menos_vendido">Seleccione un rango de fechas:</label>
                        <x-adminlte-date-range name="sel_fecha_menos_vendido" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Aceptar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL - 10-PRODUCTOS MÁS COTIZADOS --}}
<div class="modal fade" id="md_prodmascotizados">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-gradient-blue">
                <h5 class="modal-title">Productos más cotizados</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('administracion.reportes.rep-prod-mas-cotizado') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @section('plugins.DateRangePicker', true)
                    <div class="form-group">
                        <label for="sel_fecha_mas_cotizados">Seleccione un rango de fechas:</label>
                        <x-adminlte-date-range name="sel_fecha_mas_cotizados" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Aceptar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL - 11-PRODUCTOS POR TEMPORADA --}}
<div class="modal fade" id="md_prodxtemp">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-gradient-blue">
                <h5 class="modal-title">Productos por temporada</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('administracion.reportes.rep-prod-x-temporada') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="anio">Seleccionar año:</label>
                        <select class="form-control" name="anio" id="sel_anio"></select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Aceptar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL - 16-PRODUCTO POR PROVEEDOR --}}
<div class="modal fade" id="md_prodporproveedor">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-gradient-blue">
                <h5 class="modal-title">Productos por proveedor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('administracion.reportes.rep-prod-por-proveedor') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        {{-- SLIM SELECT DE CLIENTES --}}
                        <label for="sel_proveedor">Seleccione un proveedor:</label>
                        <select class="sel-proveedor-slim form-control-alternative" name="sel_proveedor" id="sel_proveedor"></select>
                      </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Aceptar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('js')
@include('partials.alerts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.js"></script>
    <script type="text/javascript">
    
            const select_clientes = new SlimSelect({
                select: '.sel-clientes-slim',
            });
            const select_proveedor = new SlimSelect({
                select: '.sel-proveedor-slim',
            });

            // LLENAR EL SELECT DE ANIOS
            $('#md_prodxtemp').on('show.bs.modal', function(e) {
                $.ajax({
                    url: "{{ route('administracion.reportes.ajax.llenar-anios-select') }}",
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        var select = $('#sel_anio');
                        select.empty();

                        $.each(data, function(index, item) {
                            select.append($('<option>', {
                                value: item.anio,
                                text: item.anio + ' (' + item
                                    .total_cotizaciones + ' cotizaciones)'
                            }));
                        });
                    }
                });
            });

            // LENAR EL SELECT DE CLIENTES
            $('#md_prodvendxcliente').on('show.bs.modal', function(e) {
                $.ajax({
                    url: "{{ route('administracion.reportes.ajax.llenar-clientes-select') }}",
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        var select = $('#sel_cliente');
                        select.empty();

                        $.each(data, function(index, item) {
                            select.append($('<option>', {
                                value: item.razon_social,
                                text: item.razon_social
                            }));
                        });
                    }
                });
            });

            // LENAR EL SELECT DE PROVEEDORES
            $('#md_prodporproveedor').on('show.bs.modal', function(e) {
                $.ajax({
                    url: "{{ route('administracion.reportes.ajax.llenar-proveedor-select') }}",
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        var select = $('#sel_proveedor');
                        select.empty();

                        $.each(data, function(index, item) {
                            select.append($('<option>', {
                                value: item.razon_social,
                                text: item.razon_social
                            }));
                        });
                    }
                });
            });
        });
    </script>
@endsection

@section('footer')
<strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
<div class="float-right d-none d-sm-inline-block">
    <b>Versión de software 2.8</b> (PHP: v{{ phpversion() }} | LARAVEL: v.{{ App::VERSION() }})
</div>
@endsection
