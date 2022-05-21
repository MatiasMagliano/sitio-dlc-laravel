@extends('adminlte::page')

@section('title', 'Administrar Productos')

@section('content_header')
    <div class="row">
        <div class="col-md-8">
            <h1>Administración de productos</h1>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <a href="{{ route('administracion.productos.create') }}" role="button"
                class="btn btn-md btn-success">Crear producto</a>
            &nbsp;
            <a href="{{ route('administracion.lotes.index') }}" role="button"
                class="btn btn-md btn-success">Crear lotes</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')
    <x-adminlte-card>
        <div class="m-2">
            <table id="tabla2" class="table table-bordered table-responsive-md" width="100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Droga</th>
                        <th>Presentaciones</th>
                        <th>Lotes vigentes</th>
                        <th>Proveedores</th>
                        <th>STOCK</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productos as $producto)
                        @foreach ($producto->presentaciones as $presentacion)
                            <tr>
                                <td>{{$producto->id}}</td>
                                <td style="vertical-align: middle;">
                                    <a href="{{route('administracion.productos.show', ['producto_id' => $producto->id, 'presentacion_id' => $presentacion->id])}}"
                                        class="btn-link justify-content-md-end">
                                        {{$producto->droga}}
                                    </a>
                                </td>
                                <td style="vertical-align: middle;">
                                    {{$presentacion->forma}}, {{$presentacion->presentacion}}
                                </td>
                                <td style="vertical-align: middle; text-align: center;">
                                    {{-- SE REEMPLAZA POR UN BOTÓN AJAX --}}
                                    <a role="button" id="{{$producto->id}}|{{$presentacion->id}}"
                                        class="btn btn-link"
                                        data-toggle="modal" data-target="#modalVerLotes">
                                        <i class="fas fa-search "></i>
                                    </a>
                                </td>
                                <td style="vertical-align: middle; text-align: center;">
                                    {{-- SE REEMPLAZA POR UN BOTÓN AJAX --}}
                                    <a role="button" id="{{$producto->id}}|{{$presentacion->id}}"
                                        class="btn btn-link"
                                        data-toggle="modal" data-target="#modalVerProveedores">
                                        <i class="fas fa-search "></i>
                                    </a>
                                </td>
                                <td>
                                    <u>disponible</u><br>
                                    {{$presentacion->deposito($producto->id, $presentacion->id)->disponible}}
                                </td>
                                <td class="text-center" style="vertical-align: middle;" width="100px">
                                    {{-- PROCEDIMIENTO DE BORRADO --}}
                                    <form action="{{ route('administracion.productos.destroy', $producto) }}" id="frm-borrar-{{$producto->id}}"
                                        method="post" class="d-inline">

                                        @csrf
                                        @method('delete')
                                        <button type="button" class="btn btn-link" data-toggle="tooltip" data-placement="middle" title="Borrar producto"
                                            onclick="
                                                event.preventDefault();
                                                let advertencia = '<p>Esto implica el borrado de todas las relaciones del producto. Si considera que es un error, deberá restaurar manualmente las relaciones</p>';

                                                Swal.fire({
                                                    icon: 'warning',
                                                    title: '¿Está seguro de eliminar el producto?',
                                                    html: '<p style=color: red; font-wieght:800; font-size:1.3em;>¡ATENCION!</p>' + advertencia,
                                                    confirmButtonText: 'Borrar',
                                                    showCancelButton: true,
                                                    cancelButtonText: 'Cancelar',
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        $('#frm-borrar-{{$producto->id}}').submit()
                                                    }
                                                });
                                            ">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-adminlte-card>

    {{-- MODAL VER LOTES VIGENTES --}}
    @section('plugins.TempusDominusBs4', true)
    <div class="modal fade" id="modalVerLotes" tabindex="-1"
        aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Lotes vigentes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <table id="tablaLotes" class="table data-table table-bordered" style="width: 100%;">
                    <thead>
                        <th>Identificador</th>
                        <th>Precio compra</th>
                        <th>F. Compra</th>
                        <th>F. Vencimiento</th>
                    </thead>
                </table>
                &nbsp;
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL VER PROVEEDORES --}}
    <div class="modal fade" id="modalVerProveedores" tabindex="-1"
        aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Lotes vigentes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <table id="tablaProveedores" class="table data-table table-bordered" style="width: 100%;">
                    <thead>
                        <th>Razón social</th>
                        <th>Contacto</th>
                        <th>Dirección</th>
                    </thead>
                </table>
                &nbsp;
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
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

            // VARIABLES LOCALES
            var tablaLotes;
            var tablaProveedores;

            tablaLotes = $('#tablaLotes').DataTable({
                "processing": true,
                "order": [
                    [3, "asc"]
                ],
                "paging": false,
                "info": false,
                "searching": false,
                "select": false,
                "columns": [
                    {
                        targets: [0],
                        data: 'identificador',
                    },
                    {
                        targets: [1],
                        data: 'precio_compra'
                    },
                    {
                        targets: [2],
                        data: 'fecha_compra'
                    },
                    {
                        targets: [3],
                        data: 'fecha_vencimiento'
                    }
                ],
                "columnDefs": [
                    {
                        targets: 2,
                        type: 'datetime',
                        //render: $.fn.dataTable.render.moment(data, 'DD/MM/YYYY'),
                        render: function (data) {
                            return moment(new Date(data)).format('DD/MM/YYYY');
                        }
                    },
                    {
                        targets: 3,
                        type: 'datetime',
                        //render: $.fn.dataTable.render.moment(data, 'DD/MM/YYYY'),
                        render: function (data) {
                            return moment(new Date(data)).format('DD/MM/YYYY');
                        }
                    },
                ],
            });

            tablaProveedores = $('#tablaProveedores').DataTable({
                "processing": true,
                "order": [
                    [0, "asc"]
                ],
                "paging": false,
                "info": false,
                "searching": false,
                "select": false,
                "columns": [
                    {
                        targets: [0],
                        data: 'razon_social',
                    },
                    {
                        targets: [1],
                        data: 'contacto'
                    },
                    {
                        targets: [2],
                        data: 'direccion'
                    },
                ],
            });

            // FUNCIÓN QUE GENERA LA TABLA DE LOS LOTES
            function getLotes(idProducto, idPresentacion) {
                var datos = {
                    producto_id: idProducto,
                    presentacion_id: idPresentacion
                };

                $.ajax({
                    url: "{{ route('administracion.lotes.ajax.obtener') }}",
                    type: "GET",
                    data: datos,
                }).done(function(resultado) {
                    tablaLotes.clear();
                    tablaLotes.rows.add(resultado).draw();
                });
            }

            // FUNCIÓN QUE GENERA LA TABLA DE LOS PROVEEDORES
            function getProveedores(idProducto, idPresentacion) {
                var datos = {
                    producto_id: idProducto,
                    presentacion_id: idPresentacion
                };

                $.ajax({
                    url: "{{ route('administracion.presentaciones.ajax.obtenerProveedores') }}",
                    type: "GET",
                    data: datos,
                }).done(function(resultado) {
                    tablaProveedores.clear();
                    tablaProveedores.rows.add(resultado).draw();
                });
            }

            tablaLotes.row.add({
                identificador: '*',
                precio_compra: '*',
                fecha_compra: '*',
                fecha_vencimiento: '*',
            }).draw();

            tablaProveedores.row.add({
                razon_social: '*',
                contacto: '*',
                direccion: '*',
            }).draw();

            $('#modalVerLotes').on('show.bs.modal', function(event){
                let temporal = event.relatedTarget.id;
                let aux = temporal.split('|');
                getLotes(aux[0], aux[1]);
            })

            $('#modalVerProveedores').on('show.bs.modal', function(event){
                let temporal = event.relatedTarget.id;
                let aux = temporal.split('|');
                getProveedores(aux[0], aux[1]);
            })
        });
    </script>
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión</b> 2.0 (LARAVEL V.8)
    </div>
@endsection
