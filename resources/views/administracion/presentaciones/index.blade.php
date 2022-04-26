@extends('adminlte::page')

@section('css')
    <style>
        tabla1.dataTable thead th {
            border-bottom: none;
        }

        tabla1.dataTable tfoot th {
            border-top: none;
            border-bottom: 1px solid  #111;
        }

        #datosLoteActivo {
            height: 50vh;
        }

        #datosLoteInactivo {
            display: none;
        }
    </style>
@endsection

@section('title', 'Administrar Presentaciones')

@section('content_header')
    <div class="row">
        <div class="col-md-8">
            <h1>Administración de clientes</h1>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <a href="{{ route('administracion.clientes.create') }}" role="button" class="btn btn-md btn-success">Crear
                Presentación</a>
            &nbsp;
            <a href="{{ url()->previous() }}" role="button" class="btn btn-md btn-secondary">Volver</a>
        </div>
    </div>
@endsection

@section('content')
    <x-adminlte-card>
        <table id="tabla2" class="table table-bordered display nowrap" style="width: 100%;">
            <thead>
                <th>Presentación</th>
                <th>Forma</th>
                <th>Alta</th>
            </thead>
            <tbody>
                @foreach ($presentaciones as $presentacion)
                    <tr>
                        <td>
                            @if($presentacion->hospitalario === 1)
                                <strong>HOSPITALARIO - </strong>{{ $presentacion->presentacion }}
                                @else {{ $presentacion->presentacion }}
                            @endif
                            @if($presentacion->trazabilidad === 1)
                                <strong>(Trazable)</strong>
                            @endif
                        </td>
                        <td>{{ $presentacion->forma }}</td>
                        <td>{{ $presentacion->created_at->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-adminlte-card>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('js/datatables-spanish.js') }}" defer></script>
    <script>
        $(document).ready(function() {
            // el datatable es responsivo y oculta columnas de acuerdo al ancho de la pantalla
            $('#tabla2').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'copyHtml5',
                        text: 'Copiar al portapapeles',
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Imprimir',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    {
                        extend: 'colvis',
                        text: 'Seleccionar columnas'
                    }
                ],
                responsive: {
                    details: {
                        renderer: function(api, rowIdx, columns) {
                            var data = $.map(columns, function(col, i) {
                                return col.hidden ?
                                    '<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' +
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
                }
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
