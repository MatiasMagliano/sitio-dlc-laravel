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
                        <td>{{ strtoupper($listaPrecio->razon_social) }}</td>
                        <td>{{ $listaPrecio->prods }}</td>
                        <td>{{ $listaPrecio->creado }}</td>
                        <td class="fechaUpdate">{{ $listaPrecio->modificado }}</td>
                        <td><a href="" class="btn btn-link" data-toggle="tooltip" data-placement="bottom"
                                title="Editar listado">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <form action="{{ route('administracion.listaprecios.index') }}"
                                id="{{ $listaPrecio->proveedor_id }}" method="POST" class="d-inline">
                                @csrf
                                @method('delete')
                                <button type="button" class="btn btn-link" data-toggle="tooltip" title="Borrar listado"
                                    onclick="borrarListadoProveedor({{ $listaPrecio->proveedor_id }})">
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
@include('partials.alerts')
<script type="text/javascript" src="{{ asset('js/datatables-spanish.js') }}" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.js"></script>
<script type="text/javascript" src="{{ asset('js/datatables-spanish.js') }}" defer></script>
<script>
    function borrarListadoProveedor($proveedor_id) {
        var datos = {
            proveedor_id: $proveedor_id
        };
        Swal.fire({
            icon: 'warning',
            title: 'Borrar listado',
            text: 'Su cotización no contiene líneas, esto borrará la referencia en el registro.',
            confirmButtonText: 'Borrar',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    data: datos,
                    url: "{{ route('administracion.listaprecios.deleteLista') }}",
                    type: "POST",

                }).done(function(resultado) {});
            }
        });
    }

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
