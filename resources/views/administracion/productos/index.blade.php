@extends('adminlte::page')

@section('css')
    <style>
        .btn-rm-hover:hover {
            color: white;
            background-color: rgb(160, 0, 0);
        }
        tabla2.dataTable {
            border-top: 1px solid black;
        }
    </style>
@endsection

@section('title', 'Administrar Productos')

@section('content_header')
    <div class="row">
        <div class="col-xl-8">
            <h1>Administración de productos</h1>
        </div>
        <div class="col-xl-4 d-flex justify-content-xl-end">
            <a href="{{ route('administracion.productos.create') }}" role="button" class="btn btn-md btn-success">Crear producto</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')
    <x-adminlte-card class="dataTables_processing">
        <table id="tabla2" class="table table-bordered" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th style="vertical-align: middle;">Droga</th>
                    <th>Detalles</th>
                    <th style="vertical-align: middle;">ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productos as $producto)
                    <tr>
                        <td>
                            {{ $producto->id }}
                        </td>
                        <td style="vertical-align: middle;">
                            {{ $producto->droga }}
                        </td>
                        <td>
                            <div class="table-responsive">
                                <table class="table table-sm table-borderless" width="100%">
                                @foreach ($producto->presentaciones as $presentacion)
                                    <tr class="border-bottom">
                                        <td width="33%" class="border-right">
                                            {{ $presentacion->forma }}, {{ $presentacion->presentacion }}
                                        </td>
                                        <td width="33%">
                                            @foreach ($presentacion->lotes as $lote)
                                                {{ $lote->identificador }}, Vto: {{ $lote->hasta }} <br>
                                            @endforeach
                                        </td>
                                        <td width="33%" class="border-left">
                                            @foreach ($presentacion->proveedores as $proveedor) <br>
                                                {{ $proveedor->razonSocial }}
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                                </table>
                            </div>
                        </td>
                        <td style="vertical-align: middle;">
                            ACCION
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-adminlte-card>
@endsection

@section('js')
    @include('partials.alerts')
    <script type="text/javascript" src="{{ asset('js/datatables-spanish.js') }}" defer></script>
    <script>
        $(document).ready(function() {
            // el datatable es responsivo y oculta columnas de acuerdo al ancho de la pantalla
            var tabla2 = $('#tabla2').DataTable({
                "processing": true,
                "dom": 'Bfrtip',
                "order": [1, 'asc'],
                "buttons": [
                    {extend: 'copyHtml5', text: 'Copiar al portapapeles'},
                    {extend: 'excelHtml5', exportOptions: {columns: ':visible'}},
                    {extend: 'print', text: 'Imprimir', exportOptions: {columns: [0, 1, 2, 3]}},
                    {extend: 'pdfHtml5', exportOptions: {columns: [0, 1, 2, 3]}},
                    {extend: 'colvis', text: 'Seleccionar columnas'}
                ],
                "responsive": [
                    {
                        "details": {
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
                ],
                "columnDefs": [
                    {targets: 0, visible: false}
                ]
            });

            // ELIMINACIÓN DE PRODUCTO
            $('#tabla2 tbody').on('click', '#btnBorrar', function(e) {
                var nombreDroga = tabla2.row($(this).parents('tr')).data()[1];
                //nombreDroga = jQuery.trim(nombreDroga).substring(0,10);
                alert(nombreDroga);
                var advertencia = 'Esto implica el borrado definitivo de todos los lotes vigentes y su relación con presentaciones y proveedores.'

                Swal.fire({
                    icon: 'warning',
                    title: '¿Está seguro de eliminar el producto: ' + nombreDroga + '?',
                    html: '<p style="color: red; font-wieght:800; font-size:1.3em;">¡ATENCION!</p>' + advertencia,
                    confirmButtonText: 'Borrar',
                    showCancelButton: true,
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.isConfirmed) {
                        var idProducto = tabla2.row($(this).parents('tr')).data()[0];
                        var datos = {
                            id: idProducto,
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            method: 'DELETE',
                        };

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $.ajax({
                            url: "productos/" + idProducto,
                            type: "DELETE",
                            data: datos,
                            success: function(response) {
                                window.location.href = response.redireccion;
                            },
                            error: function(response) {
                                console.log(response);
                                //sweet alert
                                Swal('Algo salió mal...', response.mensaje, 'error');
                            }
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
        <b>Versión</b> 2.0 (LARAVEL V.8)
    </div>
@endsection
