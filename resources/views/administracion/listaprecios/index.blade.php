@extends('adminlte::page')

@section('title', 'Administrar Productos')

@section('css') {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.css" /> --}}
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

<div class="wrapper">
    <div class="card">
        <div class="card-header">
            {{-- <div class="desktop">
                @include('administracion.cotizaciones.partials.tabla-desktop')
            </div> --}}
            <div class="mobile">
                @include('administracion.listaprecios.partials.datatable-index')
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@include('partials.alerts')
<script type="text/javascript" src="{{ asset('js/datatables-spanish.js') }}" defer></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.4/locale/es.js"></script>

<script>
    function borrarListado(id) {
        Swal.fire({
            icon: 'warning',
            title: 'Borrar Listado',
            text: 'Esta acción borrará todos los prodcutos del proveedor.',
            confirmButtonText: 'Borrar',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
                debugger;
                $('#borrar-' + id).submit();
                window.location.replace('{{ route('administracion.listaprecios.index') }}');
            }else if (
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                    'Cancelado',
                    'Operación cancelada por usuario, no se borra el listado de proveedor',
                    'error'
                )
            }
        });
    };

    $(document).ready(function() {
        // VARIABLES LOCALES
        var tabla;
        tabla = $('#tabla').DataTable({
            "paging": false,
            "info": false,
            "searching": false,
            "select": false,
        });

        $("#LockCreate").click(function() {
            borrarListado
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
    // SCRIPT DEL SLIMSELECT
    /*var selProducto = new SlimSelect({
        select: '.seleccion-producto',
        placeholder: 'Seleccione el nombre de la droga y luego su presentación...',
    });*/
</script>
@endsection

@section('footer')
<strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
<div class="float-right d-none d-sm-inline-block">
    <b>Versión</b> 2.0 (LARAVEL V.8)
</div>
@endsection
