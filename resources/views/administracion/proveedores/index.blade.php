@extends('adminlte::page')

@section('title', 'Administrar Proveedores')

@section('content_header')
    <div class="row">
        <div class="col-xl-8">
            <h1>Administración de proveedores</h1>
        </div>
        <div class="col-xl-4 d-flex justify-content-xl-end">
            {{-- para engañar al sistema, se hace un formulario por GET solamente con el botón x-adminlte-button --}}
            <form action="{{ route('administracion.proveedores.create') }}" method="get">
                <x-adminlte-button type="submit" label="Nuevo Proveedor" class="bg-green" />
            </form>
        </div>
    </div>
@stop

@section('content')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

    <x-adminlte-card>
        <table id="tabla2" class="table table-bordered display nowrap" style="width: 100%;">
            <thead>
                <th>Razón social</th>
                <th>Cuit</th>
                <th>contacto</th>
                <th>Dirección</th>
            </thead>



            @foreach($proveedores as $proveedor)
                <tr>
                    <td width="200px" style="vertical-align: middle;">{{$proveedor->razon_social}}</td>
                    <td width="200px" style="vertical-align: middle;">{{$proveedor->cuit}}</td>
                    <td width="200px" style="vertical-align: middle;">{{$proveedor->contacto}}</td>
                    <td width="200px" style="vertical-align: middle;">{{$proveedor->direccion}}</td>
                </tr>
            @endforeach
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
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: ':visible'
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
