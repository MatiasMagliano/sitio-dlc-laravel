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

            <a id="{{ $withoutList }}" role="button" class="btn btn-md btn-success">Nuevo</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
<x-adminlte-card>
    <div class="processing">
        <table id="tabla" class="table table-bordered table-responsive-md" width="100%">
            <thead>
                <tr>
                    <th>Proveedor</th>
                    <th>Cuit</th>
                    <th>Productos</th>
                    <th>Alta Listado</th>
                    <th>Último Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($listaPrecios as $listaPrecio)
                    <tr id={{ $listaPrecio->cuit }}>
                        <td>{{ strtoupper($listaPrecio->razon_social) }}</td>
                        <td>{{ $listaPrecio->cuit }}</td>
                        <td>{{ $listaPrecio->prods }}</td>
                        <td>{{ $listaPrecio->creado }}</td>
                        <td class="fechaUpdate">{{ $listaPrecio->modificado }}</td>

                        <td style="vertical-align: middle; text-align:center;">
                            <a href="{{ route('administracion.listaprecios.show', $listaPrecio->razon_social) }}"
                                class="btn btn-link" data-toggle="tooltip" data-placement="bottom"
                                title="Editar cotización">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <form action="{{ route('administracion.listaprecios.deleteList', $listaPrecio->proveedor_id) }}"
                                class="btn btn-link" data-toggle="tooltip" data-placement="bottom" method="POST" title="Eliminar listado">
                                @csrf
                                @method('DELETE')
                                <input type="submit" class="btn btn-danger btn-sm" value="Eliminar">
                                <button id="{{ $listaPrecio->proveedor_id }}|{{ $listaPrecio->razon_social }}" type="submit" data-toggle="tooltip"
                                    data-placement="bottom" title="Borrar cotización" class="btn btn-danger">
                                    <i class="fas fa-trash-alt "></i>
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
<script type="text/javascript" src="{{ asset('js/datatables-spanish.js') }}" defer></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        // VARIABLES LOCALES
        var tabla;
        tabla = $('#tabla').DataTable({
            "paging": false,
            "info": false,
            "searching": false,
            "select": false,
        });

        $("#LockCreate").click(function(){

            var datos = $(this).attr('id');
            $.ajax({
                type: "GET",
                data: datos,
            }).done(function(resultado) {
                alert(resultado);
                Swal.fire({
                    icon: 'success',
                    title: 'Listado creado',
                    text: 'Ahora será redirigido a la pagina Listados de Precios',
                })
            });
        });

        $("#UnLockCreate").on('click', function() {
            $(location).attr('href', 'listaprecios/create')
        });

    });
</script>
<script>
    function mostrarMensajeBloqueo(){
        /*Swal.fire({
            icon: 'error',
            title: 'Acción no permitida',
            text: 'No existen proveedores sin listados activos para dar de alta',
            footer: '<a href="{{ route('administracion.proveedores.create') }}">Ir a Proveedores</a>'
        })*/
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                    'Deleted!',
                    'Your file has been deleted.',
                    $(location).back();
                )
            }
        })
    }
/*
    $("#btnBuscarListado").on('click', function() {
        $idSupplier = $('#input-proveedor').val();
        getListadoProveedor($idSupplier);
    });
    function getListadoProveedor($idSupplier) {
        var datos = {
            proveedor_id: $idSupplier
        };
        $.ajax({
            url: "{{ route('administracion.listaprecios.mostrarLista') }}",
            type: "GET",
            data: datos,
        }).done(function(resultado) {
            tabla.clear();
            tabla.rows.add(resultado).draw();
        });
    };
  */  

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
        

        // SCRIPT DEL SLIMSELECT
        var selProducto = new SlimSelect({
            select: '.seleccion-producto',
            placeholder: 'Seleccione el nombre de la droga y luego su presentación...',
        });
</script>
@endsection

@section('footer')
<strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
<div class="float-right d-none d-sm-inline-block">
    <b>Versión</b> 2.0 (LARAVEL V.8)
</div>
@endsection
