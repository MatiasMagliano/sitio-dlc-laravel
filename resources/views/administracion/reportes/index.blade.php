@extends('adminlte::page')

@section('title', 'Administrar reportes')

@section('css')
@endsection

@section('content_header')
    <div class="row">
        <div class="col-xl-8">
            <h1>Administrar reportes</h1>
        </div>
        <div class="col-xl-4 d-flex justify-content-xl-end">
            <a href="{{ route('administracion.reportes.create') }}" role="button" class="btn btn-md btn-success">Nuevo reporte o listado</a>
            &nbsp;
            <a href="{{ route('home') }}" role="button" class="btn btn-md btn-secondary">Volver</a>
        </div>
    </div>
    <hr>
@endsection

@section('content')
    @section('plugins.Datatables', true)
    @section('plugins.DatatablesPlugins', true)
    @section('plugins.TempusDominusBs4', true)

    <div class="card">
        <div class="card-header">
            <h4>Listado de reportes creados por todos los usuarios</h4>
        </div>
        <div class="card-body">
            <table id="tabla_reportes" class="table table-bordered" width="100%">
                <thead class="bg-gray">
                    <th>Nombre del documento</th>
                    <th>Creado por</th>
                    <th>Dirigido a...</th>
                    <th>Tipo</th>
                    <th>fecha de modificación</th>
                    <th></th>
                </thead>
                <tfoot style="display: table-header-group;">
                    <tr class=" bg-gradient-light">
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('js')
    @include('partials.alerts')
    <script type="text/javascript" src="{{ asset('js/datatables-spanish.js') }}" defer></script>
    <script type="text/javascript">
        $(document).ready(function() {
            moment.locale('es');

            // se agrega el campo de búsqueda por columna
            $('#tabla_reportes tfoot th').slice(0, 4).each(function() {
                $(this).html('<input type="text" class="form-control form-control-sm" placeholder="Buscar" />');
            });

            // el datatable es responsivo y oculta columnas de acuerdo al ancho de la pantalla
            var tabla_clientes = $('#tabla_reportes').DataTable({
                "dom": "rltip",
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: "{{ route('administracion.reportes.ajax') }}",
                    method: "GET"
                },
                "columnDefs": [
                    {
                        targets: [0],
                        name: "nombre_documento",
                        className: "align-middle font-weight-bold",
                    },
                    {
                        targets: [1],
                        name: "name",
                        className: "align-middle",
                    },
                    {
                        targets: [2],
                        name: "dirigido_a",
                        className: "align-middle",
                    },
                    {
                        targets: [3],
                        name: "tipo",
                        className: "align-middle text-center",
                    },
                    {
                        targets: [4],
                        name: "updated_at",
                        className: "align-middle text-center",
                        width: 100,
                        'render': function(data) {
                            return moment(new Date(data)).format("DD/MM/YYYY");
                        },
                    },
                    {
                        targets: [5],
                        name: "acciones",
                        className: "align-middle text-center",
                        orderable: false,
                    },
                ],
                initComplete: function() {
                    this.api()
                        .columns([0, 1, 2, 3])
                        .every(function() {
                            var that = this;

                            $('input', this.footer()).on('keyup change clear', function() {
                                if (that.search() !== this.value) {
                                    that.search(this.value).draw();
                                }
                            });
                        });
                },
            });
        });
    </script>
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión de software 2.8</b> (PHP: v{{phpversion()}} | LARAVEL: v.{{App::VERSION()}})
    </div>
@endsection
