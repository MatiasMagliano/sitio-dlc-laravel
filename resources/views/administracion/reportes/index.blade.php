@extends('adminlte::page')

@section('title', 'Administrar reportes')

@section('css')
@endsection

@section('content_header')
    <div class="row">
        <div class="col-xl-8">
            <h1>Generador de reportes</h1>
        </div>
        <div class="col-xl-4 d-flex justify-content-xl-end">
            <a href="{{ route('home') }}" role="button" class="btn btn-md btn-secondary">Volver</a>
        </div>
    </div>
    <hr>
@endsection

@section('content')
    @section('plugins.Datatables', true)
    @section('plugins.DatatablesPlugins', true)
    @section('plugins.TempusDominusBs4', true)
    <div class="container-fluid">
        <h4>Seleccione un tipo de reporte</h4>

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="listados-tab" data-toggle="tab" href="#listados" role="tab"
                    aria-controls="listados" aria-selected="true">Listados</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="perfil-tab" data-toggle="tab" href="#perfil" role="tab"
                    aria-controls="perfil" aria-selected="false">Perfil</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="contacto-tab" data-toggle="tab" href="#contacto" role="tab"
                    aria-controls="contacto" aria-selected="false">Contacto</a>
            </li>
        </ul>
        <div class="tab-content ml-3" id="tabGeneral">
            <div class="tab-pane fade show active" id="listados" role="tabpanel" aria-labelledby="listados-tab">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="clientes-tab" data-toggle="tab" href="#clientes" role="tab"
                            aria-controls="clientes" aria-selected="true">Clientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="cotizaciones-tab" data-toggle="tab" href="#cotizaciones" role="tab"
                            aria-controls="cotizaciones" aria-selected="false">Cotizaciones</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="productos-tab" data-toggle="tab" href="#productos" role="tab"
                            aria-controls="productos" aria-selected="false">productos</a>
                    </li>
                </ul>
                <div class="tab-content ml-3 tab border" id="tabContentClientes">
                    <div class="tab-pane fade show active" id="clientes" role="tabpanel" aria-labelledby="clientes-tab">
                        {{-- VERTICAL TABS DE CLIENTES --}}
                        <div class="row">
                            <div class="col-1">
                                <div class="nav flex-column nav-tabs" role="tablist" aria-orientation="vertical">
                                    <a href="#listado" class="nav-link active" role="tab" data-toggle="tab" aria-selected="true">Lista</a>
                                    <a href="#ventas" class="nav-link" role="tab" data-toggle="tab" aria-selected="false">Ventas</a>
                                </div>
                            </div>
                            <div class="col">
                                <div class="tab-content tab">
                                    {{-- LISTA DE CLIENTES --}}
                                    <div role="tabpanel" class="tab-pane fade in show active" id="listado">
                                        <h5 class=" mb-3">Seleccione el orden del listado y luego Generar o Descargar</h5 class=" mb-3">
                                        <div class="row">
                                            <div class="col-3">
                                                <form action="" id="frm-list-clientes">
                                                    @include('administracion.reportes.partes.lst-clientes.frm-lst-clientes')
                                                </form>
                                            </div>
                                            <div class="col border bg-light">
                                                <table class="table" id="tbl-clientes">
                                                    <thead>
                                                        <tr>
                                                            <th>Razón social</th>
                                                            <th>Régimen tributario</th>
                                                            <th>Contacto</th>
                                                            <th>Última compra</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- LISTA DE VENTAS --}}
                                    <div role="tabpanel" class="tab-pane fade" id="ventas">
                                        <h5 class=" mb-3">Seleccione el tipo de reporte, el rango de fechas y luego Generar o Descargar</h5 class=" mb-3">
                                        <div class="row">
                                            <div class="col-3">
                                                <form action="" id="frm-list-ventas">
                                                    @include('administracion.reportes.partes.lst-clientes.frm-lst-ventas')
                                                </form>
                                            </div>
                                            <div class="col border bg-light">
                                                <table class="table" id="tbl-ventas">
                                                    <thead>
                                                        <tr>
                                                            <th>F. Aprobación</th>
                                                            <th>Identificador</th>
                                                            <th>Cliente</th>
                                                            <th>Monto</th>
                                                            <th>Pto. Entrega</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="tab-pane fade" id="cotizaciones" role="tabpanel" aria-labelledby="cotizaciones-tab">
                        <div class="row">
                            <div class="col-2">
                                <form action="" id="frm-list-cotizaciones">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="input-orden">Orden del listado *</label>
                                                <select class="form-control" name="orden-listado" id="input-orden">
                                                    <option selected>Fecha creación</option>
                                                    <option>Fecha última modificación</option>
                                                    <option>Estado</option>
                                                    <option>Cliente</option>
                                                    <option>Monto</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="desde">Desde *</label>
                                                <x-adminlte-input-date name="input-desde" id="desde" igroup-size="md" :config="$config_desde" autocomplete="off" required>
                                                    <x-slot name="appendSlot">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-calendar"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input-date>
                                            </div>

                                            <div class="form-group">
                                                <label for="hasta">Hasta *</label>
                                                <x-adminlte-input-date name="input-hasta" id="hasta" igroup-size="md" :config="$config_hasta" autocomplete="off" required>
                                                    <x-slot name="appendSlot">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-calendar"></i>
                                                        </div>
                                                    </x-slot>
                                                </x-adminlte-input-date>
                                            </div>
                                        </div>
                                        <div class="card-footer text-right">
                                            <button type="submit" class="btn btn-sidebar btn-success">Generar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col">
                                CONTENIDO DEL REPORTE DE COTIZACIONES
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="productos" role="tabpanel" aria-labelledby="productos-tab">
                        FORMULARIO DE PRODUCTOS
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="perfil" role="tabpanel" aria-labelledby="perfil-tab">FORMULARIO DE PERFIL</div>
            <div class="tab-pane fade" id="contacto" role="tabpanel" aria-labelledby="contacto-tab">FORMULARIO DE CONTACTO</div>
        </div>

    </div>
@endsection

@section('js')
    @include('partials.alerts')
    <script type="text/javascript" src="{{ asset('js/datatables-spanish.js') }}" defer></script>

    <script>
        $(document).ready(function() {
            var tbl_clientes = $('#tbl-clientes').DataTable({
                "dom": "t",
                "ordering": false,
                "scrollY": "35vh",
                "scrollCollapse": true,
                "paging": false,
                "columnDefs": [
                    {
                        targets: [0],
                        name: "razon_social",
                        data: "razon_social",
                        className: "align-middle",
                    },
                    {
                        targets: [1],
                        name: "regimen_trib",
                        data: "regimen_trib",
                        className: "align-middle",
                    },
                    {
                        targets: [2],
                        name: "contacto",
                        data: "contacto",
                        className: "align-middle",
                    },
                    {
                        targets: [3],
                        name: "ultima_compra",
                        data: "ultima_compra",
                        className: "align-middle text-center",
                        render: function(data) {
                            return moment(new Date(data)).format("DD/MM/YYYY");
                        },
                    },
                ],
            });

            var tbl_ventas = $('#tbl-ventas').DataTable({
                "dom": "t",
                "ordering": false,
                "columnDefs": [
                    {
                        targets: [0],
                        name: "fecha_aprobacion",
                        data: "fecha_aprobacion",
                        className: "align-middle text-center",
                        render: function(data) {
                            return moment(new Date(data)).format("DD/MM/YYYY");
                        },
                    },
                    {
                        targets: [1],
                        name: "identificador",
                        data: "identificador",
                        className: "align-middle text-center",
                    },
                    {
                        targets: [2],
                        name: "cliente",
                        data: "cliente",
                        className: "align-middle",
                    },
                    {
                        targets: [3],
                        name: "monto_total",
                        data: "monto_total",
                        className: "align-middle text-center",
                    },
                    {
                        targets: [4],
                        name: "punto_entrega",
                        data: "punto_entrega",
                        className: "align-middle",
                    },
                ],
            });

            // REQUEST PARA LISTADO DE CLIENTES
            $('#frm-list-clientes').submit(function(e){
                let orden = $('#frm-list-clientes').serialize();
                $.ajax({
                    url: "{{route('administracion.reportes.lst_clientes')}}",
                    type: "GET",
                    data: orden,
                    success: function(response){
                        // devuelve un objeto cargado de datos
                        tbl_clientes.clear().draw();
                        tbl_clientes.rows.add(response).draw()
                    },
                    error: function(response) {
                    var errors = response.responseJSON;
                    errores = '';
                    $.each( errors, function( key, value ) {
                        errores += value;
                    });
                    Swal.fire({
                        icon: 'error',
                        text: errores,
                        showConfirmButton: true,
                    });
                }
                });

                e.preventDefault();
            });

            // REQUEST PARA LISTADO DE VENTAS
            $('#frm-list-ventas').submit(function(e){
                let orden = $('#frm-list-ventas').serialize();
                $.ajax({
                    url: "{{route('administracion.reportes.lst_ventas')}}",
                    type: "GET",
                    data: orden,
                    success: function(response){
                        // devuelve un objeto cargado de datos
                        tbl_ventas.clear().draw();
                        tbl_ventas.rows.add(response).draw()
                    },
                    error: function(response) {
                        var errors = response.responseJSON;
                        errores = '';
                        $.each( errors, function( key, value ) {
                            errores += value;
                        });
                        Swal.fire({
                            icon: 'error',
                            text: errores,
                            showConfirmButton: true,
                        });
                    }
                });

                e.preventDefault();
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
