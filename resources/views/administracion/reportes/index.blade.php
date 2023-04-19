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
    <div class="card">
        <div class="card-header">
            <h4>Listado de reportes creados por todos los usuarios</h4>
        </div>
        <div class="card-body">
            <table id="tabla_reportes" class="table table-bordered" width="100%">
                <thead class="bg-gray">
                    <th>Identificador</th>
                    <th>Dirigido a...</th>
                    <th>Tipo</th>
                    <th>fecha de creación</th>
                    <th></th>
                </thead>
                <tfoot style="display: table-header-group;">
                    <tr class=" bg-gradient-light">
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
        // se agrega el campo de búsqueda por columna
        $('#tabla_reportes tfoot th').slice(0, 4).each(function() {
            $(this).html('<input type="text" class="form-control" placeholder="Buscar" />');
        });

        // el datatable es responsivo y oculta columnas de acuerdo al ancho de la pantalla
        var tabla_clientes = $('#tabla_reportes').DataTable({
            "processing": true,
            "dom": 'ltip',
            "order": [0, 'asc'],
            "responsive": [{
                "details": {
                    renderer: function(api, rowIdx, columns) {
                        var data = $.map(columns, function(col, i) {
                            return col.hidden ?
                                '<tr data-dt-row="' + col.rowIndex +
                                '" data-dt-column="' +
                                col.columnIndex + '">' +
                                '<td>' + col.title + ':' + '</td> ' +
                                '<td>' + col.data + '</td>' +
                                '</tr>' :
                                '';
                        }).join('');

                        return data ?
                            $('<table/>').append(data) :
                            false;
                    }
                }
            }],
            initComplete: function() {
                // Apply the search
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
