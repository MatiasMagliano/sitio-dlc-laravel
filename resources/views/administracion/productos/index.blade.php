@extends('adminlte::page')

@section('css')
    <style>
        .btn-rm-hover:hover {
            color: white;
            background-color: rgb(160, 0, 0);
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
    <x-adminlte-card>
        <table id="tabla2" class="table table-bordered display nowrap" style="width: 100%;">
            <thead>
                <th>ID</th>
                <th>Droga</th>
                <th>Presentación</th>
                <th>Proveedor/es</th>
                <th>Lotes vigentes</th>
                <th></th>
            </thead>
            <tbody>
                @foreach ($productos as $producto)
                    <tr>
                        <td>{{ $producto->id }}</td>
                        <td width="200px" style="vertical-align: middle;">
                            {{--<a href="{{ route('administracion.lotes.edit', $producto->id) }}">{{ $producto->droga }}</a>--}}
                            {{--
                            <a href="{{ route('administracion.productos.edit', $producto->id) }}" role="button" class="btn btn-sm btn-default btn-edt-hover mx-1 shadow"><i class="fas fa-lg fa-fw fa-cog"></i></a>--}}
                            {{ $producto->droga }}
                        </td>
                        <td style="vertical-align: middle;">
                            {{-- Aquí se hace referencia a la relación creada en el modelo --}}
                            @foreach ($producto->presentaciones as $presentacion)
                                {{ $presentacion->forma }}, {{ $presentacion->presentacion }}
                                <br>
                                @if ($presentacion->hospitalario || $presentacion->trazabilidad)
                                    Producto
                                    @if ($presentacion->hospitalario)
                                        <strong>HOSPITALARIO</strong>
                                    @endif
                                    @if ($presentacion->trazabilidad)
                                        {{--MÉTODO NO IMPLEMENTADO TODAVÍA--}}
                                        <span style="color: red; font-weight:800;">TRAZABLE </span><a href="{{ route('administracion.trazabilidad.show', $producto->id) }}" class="btn-sm bg-gray" role="button">Ver</a>
                                    @endif
                                @endif
                            @endforeach
                        </td>
                        <td>
                            @foreach ($producto->proveedores as $proveedor)
                                <a href="{{ route('administracion.proveedores.show', $proveedor->id) }}">{{ $proveedor->razonSocial }}</a><br>
                            @endforeach
                        </td>
                        <td>
                            <div class="row d-flex">
                                @foreach ($producto->lotes as $lote)
                                    <div class="col-6 px-auto">
                                        <strong>Lote:</strong> <a href="{{ route('administracion.lotes.show', $lote->id) }}">{{ $lote->identificador }}</a>
                                    </div>
                                    <div class="col-6 px-auto">
                                        <strong>Vto:</strong> {{ $lote->hasta->format('m/Y') }} <br>
                                    </div>
                                @endforeach
                                <hr>
                                Total en stock: &nbsp; <strong>{{ $lote->sumaLote($producto->id) }}</strong>
                            </div>
                        </td>
                        <td class="text-center" style="vertical-align: middle;" width="100px">
                            {{-- se crea este método, porque el borrado en Laravel se hace por POST --}}
                            <a id="btnBorrar" class="btn btn-rm-hover btn-sm btn-light mx-1 shadow" ><i class="fa fa-lg fa-fw fa-trash"></i></a>
                        </td>
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
            var tabla2 = $('#tabla2').DataTable({
                "processing": true,
                "dom": 'Bfrtip',
                "order": [1, 'asc'],
                "buttons": [
                    {
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
                    {
                        targets: 0,
                        visible: false
                    },
                    {
                        targets: 1,
                        width: '1%',
                    },
                    {
                        targets: 4,
                        width: '23%',
                    },
                    {
                        targets: 5,
                        width: '1%'
                    }
                ]
            });

            // ELIMINACIÓN DE PRODUCTO
            $('#tabla2 tbody').on('click', '#btnBorrar', function(e) {
                var nombreDroga = tabla2.row($(this).parents('tr')).data()[1];

                Swal.fire({
                    icon: 'warning',
                    title: 'Eliminación de productos',
                    text: '¿Está seguro de eliminar el producto ' + nombreDroga + '?',
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
                                //sweet alert
                                Swal.fire({
                                    title: 'Eliminado',
                                    text: response.mensaje,
                                    icon: 'success',
                                    timer: 2500,
                                    showConfirmButton: false,
                                });

                                setTimeout(() => {
                                    window.location.href = response.redireccion;
                                    //alert(response.redireccion);
                                }, 2500);
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
