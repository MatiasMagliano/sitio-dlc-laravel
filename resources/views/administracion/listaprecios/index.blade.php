@extends('adminlte::page')
@section('css')
    <style>
        .dataTable>thead>tr>th[class*="sort"]:before,
        .dataTable>thead>tr>th[class*="sort"]:after {
            content: "" !important;
        }

        tabla1.dataTable thead th {
            border-bottom: none;
        }

        tabla1.dataTable tfoot th {
            border-top: none;
            border-bottom: 1px solid #111;
        }

        #tabla2.dataTable{
            overflow: auto;
        }

    </style>
@endsection

@section('title', 'Administrar Productos')

@section('content_header')
    <div class="row">
        <div class="col-xl-8">
            <h1>Listado de Precios</h1>
        </div>
        <div class="col-xl-4 d-flex justify-content-xl-end">
            <a href="{{ route('administracion.cotizaciones.create') }}" role="button" class="btn btn-md btn-success">Crear
                cotización</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')
<div class="card-group mr-2">
    <div class="card mr-2">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-search mr-2"></i>
                Seleccionar Proveedor
            </h3>
        </div>
        <div class="card-body">
            <table id="tabla1" class="tabla1 mr-2" style="width: 100%; cursor:pointer">
                <thead>
                    <th></th>
                    <th></th>
                </thead>
                <tbody>
                    @foreach ($proveedors as $proveedor)
                        <tr>
                            <td>{{ $proveedor->id }}</td>
                            <td>{{ $proveedor->razonSocial }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card ml-2" id="ListadoDePrecios">
        <div class="card-header">
            <h3 id="tituloListadoDePrecios" class="card-title">
                Lista de Precios de
            </h3>
            <Form action="" method="post">
                <button class="">
                    <i class="fas fa-plus"></i>
                </button>
                <button class="">
                    <i class="fas fa-file-upload"></i>
                </button>
            </Form>
        </div>
        <div class="card-body">
            <table id="tabla2" class="display nowrap" style="width: 100%; cursor:pointer">
                <thead>
                    <th>ID</th>
                    <th>Presentacion</th>
                    <th>Costo</th>
                    <th></th>
                </thead>
            </table>
        </div>
        <div class="overlay"><i class="fas fa-ban text-gray"></i></div>
    </div>
</div>

@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('js/datatables-spanish.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/localization/methods_pt.js"></script>
    <script>
        $(document).ready(function() {
            // VARIABLES LOCALES
            var tabla2;


            // Defino tabla1
            var tabla1 = $('#tabla1').DataTable({
                "responsive": true,
                "dom": 'Pfrtip',
                "scrollY": "50vh",
                "scrollCollapse": true,
                "paging": false,
                "select": true,
                "columns": [{
                    data: 'ID',
                    visible: false,
                    searchable: false},
                    {
                    data: 'Proveedor'}
                ],
                "order": [1, 'asc'],
                "bInfo": false,
            });

            // Crea y llena la tabla2
            function getListadoProveedor(idProveedor) {
                var datos = {
                    idProveedor: idProveedor
                };

                $.ajax({
                    url: "{{ route('administracion.listaprecios.actualizarLista') }}",
                    type: "GET",
                    data: datos,
                }).done(function(resultado) {
                    tabla2.clear();
                    tabla2.rows.add(resultado).draw();
                });

            }
            //Define tabla2
            tabla2 = $('#tabla2').DataTable({
                "scrollY": "50vh",
                "scrollCollapse": true,
                "processing": true,
                "order": [1, "asc"],
                "paging": false,
                "info": false,
                "searching": false,
                "select": false,
                "columns": [{
                    targets: [0],
                    visible: false,
                    data: 'id',
                    searchable: false},
                    {
                    targets: [1],
                    data: 'presentacion_id'},
                    {
                    targets: [2],
                    data: 'costo'}
                ],
                "columnDefs": [{
                    targets: 1,
                    width: 100},
                    {
                    targets: 2,
                    render: $.fn.dataTable.render.number( ',', '.', 2 )},
                    {
                    targets: 3,
                    data: null,
                    width: 30,
                    defaultContent: "<button id='btnBorrar' class='btn-xs btn-primary'><i class='fa fa-lg fa-fw fa-trash'></i></button>"}
                ]
            });

            tabla2.row.add({
                id: '*',
                presentacion_id: '*',
                costo: '*',
            }).draw();

            // Captura de selección en item de tabla1
            $('#tabla1 tbody').on('click', 'tr', function() {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                } else {
                    tabla1.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }

                $('#ListadoDePrecios .overlay').remove();
                var idProveedor = tabla1.row(this).data().ID;
                var Proveedor = tabla1.row(this).data().Proveedor;
                $('#tituloListadoDePrecios').text('Lotes vigentes para ' + Proveedor);
                $('#proveedor_id').val(idProveedor);

                getListadoProveedor(idProveedor);
            });

            //Agrega item a la lista del Proveedor ---Pendiente

            // Quita un item de la lista de precios del proveedor
            $('#tabla2 tbody').on('click', 'button', function(e) {
                var itemlistaproveedor = tabla2.row($(this).parents('tr')).data().presentacion_id;

                Swal.fire({
                    icon: 'warning',
                    title: 'Quitar de la lista del Proveedor',
                    text: '¿Está seguro de quitar el producto ' + itemlistaproveedor + '?',
                    confirmButtonText: 'Borrar',
                    showCancelButton: true,
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.isConfirmed) {
                        var iditemlistaproveedor = tabla2.row($(this).parents('tr')).data().id;
                        var datos = {
                            id: iditemlistaproveedor,
                            method: 'DELETE',
                        };

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $.ajax({
                            url: "{{ url('borrar-lote') }}",
                            type: "DELETE",
                            data: datos,
                            success: function(response) {
                                Swal.fire(
                                    'Eliminado',
                                    response.mensaje,
                                    'success'
                                );
                                // Se actualiza la tabla2
                                var idProveedor = $(
                                    'input[type=hidden][name=proveedor_id]').val();
                                getListadoProveedor(idProveedor);
                            },
                            error: function(response) {
                                console.log(response);
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
