@extends('adminlte::page')

@section('title', 'Administrar Productos')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.css" />
    <style>
        .dataTable>thead>tr>th[class*="sort"]:before,
        .dataTable>thead>tr>th[class*="sort"]:after {
            content: "" !important;
        }

        #tabla1.dataTable tfoot th {
            border-top: none;
            border-bottom: 1px solid #111;
        }

        #tabla2.dataTable {
            overflow: auto;
        }

        .search input[type=text] {
            font-size: 17px;
            border: none;
            outline: none;
        }

        .search input[type=text] {
            font-size: 17px;
            border: none;
            outline: rgba(0, 0, 0, 0);
        }

        .search button {
            background: #fff;
            font-size: 17px;
            border: none;
            opacity: 0.4;
        }

        .hide {
            display: none
        }
    </style>
@endsection

@section('content_header')
    <div class="row">
        <div class="col-xl-8">
            <h1>Listado de Precios</h1>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <a href="{{ route('administracion.listaprecios.create') }}" role="button" class="btn btn-md btn-success">Crear
                Lista de Precios</a>

        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
<x-adminlte-card>
    <div class="processing">
        <table id="tabla2" class="table table-bordered table-responsive-md" width="100%">
            <thead>
                <tr>
                    <th>Cuit</th>
                    <th>Razon Social</th>
                    <th>Productos</th>
                    <th>Alta Listado</th>
                    <th>Último Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($listaPrecios as $listaPrecio)
                    <tr id={{ $listaPrecio->cuit }}>
                        <td>{{ $listaPrecio->cuit }}</td>
                        <td>{{ $listaPrecio->proveedor_id }} - {{ strtoupper($listaPrecio->razon_social) }}</td>
                        <td>{{ $listaPrecio->prods }}</td>
                        <td>{{ $listaPrecio->creado }}</td>
                        <td class="fechaUpdate">{{ $listaPrecio->modificado }}</td>

                        <td style="vertical-align: middle; text-align:center;" value="{{ $listaPrecio->cuit }}">
                            <a href="{{ route('administracion.listaprecios.show', $listaPrecio->cuit) }}"
                                class="btn btn-link" data-toggle="tooltip" data-placement="bottom"
                                title="Editar cotización">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <form method="GET" action="{{ route('administracion.listaprecios.deleteList', $listaPrecio->cuit) }}"
                                class="btn btn-link" data-toggle="tooltip" data-placement="bottom"
                                title="Borrar cotización">
                                @csrf
                                @method('GET')
                                <button id="{{ $listaPrecio->proveedor_id }}|{{ $listaPrecio->razon_social }}" type="button" 
                                    class="btn btn-link trashListProv" data-toggle="tooltip"
                                    data-placement="bottom" title="Borrar cotización">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-adminlte-card>

@endsection

@section('js')

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

    $('.trashListProv').on('click', function() {
        $cuit = $(this).attr('id');
        borrarListadoProveedor($cuit);
    });

     function borrarListadoProveedor(listado){
        var proveedor = listado.split("|");
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })

        swalWithBootstrapButtons.fire({
            title: 'Borrar Listado de '+ proveedor[1],
            text: "Esta accción quitará los productos de la lista del proveedor en futuras cotizaciones.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Borrar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
            var datos = {
                lisadoProveedor: 15
            };
            $.ajax({
                url: "{{ route('administracion.listaprecios.deleteList') }}",
                type: "DELETE",
                data: datos,
            });   
                //swalWithBootstrapButtons.fire(
                //    'Borrado',
                //    'Se ha eliminado el listado de proveedor',
                //    'success'
                //    )
                } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                    'Cancelado',
                    'Operación cancelada por usuario, no se borra el listado de proveedor',
                    'error'
                    )
                }
            })
        }

//             Swal.fire({
//   title: 'Are you sure?',
//   text: "You won't be able to revert this!",
//   icon: 'warning',
//   showCancelButton: true,
//   confirmButtonColor: '#3085d6',
//   cancelButtonColor: '#d33',
//   confirmButtonText: 'Yes, delete it!'
// }).then((result) => {
//   if (result.isConfirmed) {
//     Swal.fire(
//       'Deleted!',
//       'Your file has been deleted.',
//       'success'
//     )
//   }
// })
</script>
<script>
    $(document).ready(function() {
        // VARIABLES LOCALES
        var tabla2;
        Tabla 2


        tabla2 = $('#tabla2').DataTable({
            //"scrollY": "57vh",
            //"scrollCollapse": true,
            //"processing": true,
            "order": [1, "asc"],
            "paging": false,
            "info": false,
            "searching": false,
            "select": false,

            "columns": [{
                    targets: [1],
                    data: 'cuit',
                },
                {
                    targets: [1],
                    data: 'razon_social'
                },
                {
                    targets: [1],
                    data: 'prods'
                },
                {
                    targets: [2],
                    data: 'creado'
                },
                {
                    targets: [2],
                    data: 'modificado'
                }
            ],

            "buttons": [{
                    extend: 'copyHtml5',
                    text: 'Copiar al portapapeles',
                    exportOptions: {
                        columns: ':visible'
                    }
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

            "columnDefs": [{
                targets: 2,
                type: 'datetime',
                render: function(data) {
                    return moment(new Date(data)).format('DD/MM/YYYY');
                }
            }]
        });

        $listaPrecios.forEach(modificado => {
            alert("hola");
        });

        $("#btnBuscarListado").on('click', function() {
            $idSupplier = $('#input-proveedor').val();
            getListadoProveedor($idSupplier);
        });

        // Llena Tabla2
        function getListadoProveedor($idSupplier) {
            var datos = {
                proveedor_id: $idSupplier
            };
            $.ajax({
                url: "{{ route('administracion.listaprecios.mostrarLista') }}",
                type: "GET",
                data: datos,
            }).done(function(resultado) {
                tabla2.clear();
                tabla2.rows.add(resultado).draw();
            });
        };



        // Exporta listado
        // $(document).on('click', '#getlistad', function(){
        //     $RS = $('#search-input').val();
        //     $loc = "{{ route('administracion.listaprecios.exportlist') }}";
        //     window.location=$loc;
        // });



        // //function listadoexport(rs) {
        //     $('#getlistadd').on('click', function(){
        //         var rs = $('#search-input').val();
        //         var datos = { razonsocial: rs};
        //         $.ajax({
        //             url: "{{ route('administracion.listaprecios.exportlist') }}",
        //             type: "GET",
        //             data: datos
        //         }).done(function(resultado) {
        //             alert("Listado exportado exitosamente");
        //    });
        //     });
        // };


        //Descarga listado de proveedor
        // $('#getlistado').on('click', function(){

        //     pj = $('#search-input').val();

        //     var datos = {razon_social: pj};
        //     $.ajax({
        //         url: "{{ route('administracion.listaprecios.exportlist') }}",
        //         type: "GET",
        //         data: datos,
        //    }).done(function(resultado) {
        //        alert(pj);
        //        alert('Error en exportación de planillacon');
        //    });
        // });


        // Quita un item de la lista de precios del proveedor


        // SCRIPT DEL SLIMSELECT
        var selProducto = new SlimSelect({
            select: '.seleccion-producto',
            placeholder: 'Seleccione el nombre de la droga y luego su presentación...',
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
