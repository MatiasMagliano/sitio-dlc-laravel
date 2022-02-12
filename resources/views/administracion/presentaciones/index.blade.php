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
        <div class="col-xl-8">
            <h1>Administración de presentaciones</h1>
        </div>
        <div class="col-xl-4 d-flex justify-content-xl-end">
            {{-- para engañar al sistema, se hace un formulario por GET solamente con el botón x-adminlte-button --}}
            <form action="{{ route('administracion.productos.create') }}" method="get">
                <x-adminlte-button type="submit" label="Crear producto" class="bg-green" />
            </form>
        </div>
    </div>
@endsection

@section('content')
    <x-adminlte-card>
        <table id="tabla2" class="table table-bordered display nowrap" style="width: 100%;">
            <thead>
                <th>Producto</th>
                <th>Forma</th>
                <th>Presentación</th>
                <th>Acciones</th>
            </thead>
            <tbody>
                @foreach ($presentaciones as $presentacion)
                    @foreach ($presentacion->productos as $producto)
                        <tr>
                            <td width="200px" style="vertical-align: middle;">
                                @if ($presentacion->hospitalario)
                                    <strong>H -</strong>
                                @endif
                                {{ $producto->droga}}
                                @if ($presentacion->trazabilidad)
                                    <strong style="color: #ff1600">TRAZABLE</strong>

                                @endif
                            </td>
                            <td width="200px" style="vertical-align: middle;">
                                {{ $presentacion->forma}}
                            </td>
                            <td width="200px" style="vertical-align: middle;">
                                {{ $presentacion->presentacion }}
                            </td>
                            <!--
                            <td width="200px" style="vertical-align: middle;">
                                {{ $presentacion->created_at }}
                            </td>
                            <td width="200px" style="vertical-align: middle;">
                                {{ $presentacion->updated_at }}
                            </td>
                            -->
                            <td class="text-center" style="vertical-align: middle;" width="100px">
                            <a href="{{ route('administracion.presentaciones.edit', $presentacion->id) }}" role="button"
                                class="btn btn-sm btn-default btn-edt-hover mx-1 shadow">
                                <i class="fas fa-lg fa-fw fa-cog"></i></a>
                            </td>
                        </tr>
                    @endforeach
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
