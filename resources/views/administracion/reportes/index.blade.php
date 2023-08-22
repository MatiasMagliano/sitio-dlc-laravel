@extends('adminlte::page')

@section('title', 'Administrar reportes')

@section('css')
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
                    <li class="list-group-item"><a class="btn-link" data-toggle="modal" data-target="#md_pedprocxvend"
                            style="cursor: pointer;">Pedidos procesados por vendedor</a></li>
                    <li class="list-group-item"><a class="btn-link" data-toggle="modal" data-target="#md_vtasxrangofechas"
                            style="cursor: pointer;">Ventas por rango de fechas</a></li>
                </ul>

                <h5 class="mt-3">Reportes de productos</h5>
                <ul class="list-group">
                    <li class="list-group-item"><a class="btn-link" data-toggle="modal" data-target="#md_prodxtemp"
                            style="cursor: pointer;">Productos por temporada</a></li>
                </ul>
            </div>
            <div class="col">
                <h5 class="mt-3">Reportes de clientes</h5>
                <ul class="list-group">
                    <li class="list-group-item"><a href="{{ route('administracion.reportes.rep-clientes') }}">Clientes</a>
                    </li>
                </ul>

                <h5 class="mt-3">Reportes de proveedores</h5>
                <ul class="list-group">
                    <li class="list-group-item"><a
                            href="{{ route('administracion.reportes.rep-proveedores') }}">Proveedores</a></li>
                    <li class="list-group-item"><a
                            href="{{ route('administracion.reportes.rep-prod-al-menor-costo') }}">Productos al menor
                            costo</a></li>
                </ul>
            </div>
        </div>
    </div>

    {{-- MODAL - PEDIDOS PROCESADOS POR VENDEDOR --}}
    <div class="modal fade" id="md_pedprocxvend">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-gradient-blue">
                    <h5 class="modal-title">Selección de datos iniciales</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('administracion.reportes.rep-ped-proc-x-vendedor') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                    @section('plugins.DateRangePicker', true)
                    <div class="form-group">
                        <label for="sel_fecha">Seleccione un rango de fechas:</label>
                        <x-adminlte-date-range name="sel_fecha_pedidos" />
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

{{-- MODAL - VENTAS POR RANGO DE FECHAS --}}
<div class="modal fade" id="md_vtasxrangofechas">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-gradient-blue">
                <h5 class="modal-title">Selección de datos iniciales</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('administracion.reportes.rep-vtas-por-rango-fechas') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @section('plugins.DateRangePicker', true)
                    <div class="form-group">
                        <label for="sel_fecha">Seleccione un rango de fechas:</label>
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

{{-- MODAL - PRODUCTOS POR TEMPORADA --}}
<div class="modal fade" id="md_prodxtemp">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-gradient-blue">
                <h5 class="modal-title">Selección de datos iniciales</h5>
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

@endsection

@section('js')
@include('partials.alerts')
<script type="text/javascript">
    $(document).ready(function() {
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
    });
</script>
@endsection

@section('footer')
<strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
<div class="float-right d-none d-sm-inline-block">
    <b>Versión de software 2.8</b> (PHP: v{{ phpversion() }} | LARAVEL: v.{{ App::VERSION() }})
</div>
@endsection
