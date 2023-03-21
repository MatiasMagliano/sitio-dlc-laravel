@extends('adminlte::page')

@section('title', 'Administrar Productos')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.css" />
    <style>
        @media (max-width: 600px) {
            .hide {
                display: none;
            }
        }
    </style>
@endsection

@section('content_header')
    <div class="row">
        <div class="col-xl-8">
            @foreach ($proveedor as $proveedorItem)
                <h1>Lista de Precios de: {{ $proveedorItem->razon_social }}</h1>
            @endforeach
            
            </div>
            <div class="col-md-4 d-flex justify-content-xl-end">
                {{-- <a href="{{ route('administracion.listaprecios.index') }}" role="button" class="btn btn-md btn-success" style="margin-right:5px" title="Actualizar masivo">Actualizar listado</a> --}}
            <a href="{{ route('administracion.listaprecios.index') }}" role="button" class="btn btn-md btn-secondary" title="Volver a Listados">Volver al Listado</a>
        </div>
    </div>
    @stop

    @section('content')
    <div class="card">
        <div class="card-header">
            <div class="row d-flex">
                <div class="col-8">
                    <h5 class="heading-small text-muted mb-1">Datos básicos del Proveedor</h5>
                </div>
            </div>
        </div>
        <div class="card-body">
            @include('administracion.listaprecios.partials.datatable-showheader')
        </div>
    </div>


    @section('plugins.Datatables', true)
    @section('plugins.DatatablesPlugins', true)
    <div class="wrapper">
        <div class="card">
            <div class="card-header">
                <div class="row d-flex">
                    <div class="col-11">
                        <h5 class="heading-small text-muted mb-1">Productos</h5>
                    </div>
                    <div class="col-1">
                        <button role="button" class="btn btn-success open_first_modal" data-toggle="modal" data-target="#modalAgregProducto" data-toggle="tooltip" data-placement="middle"
                            title="Agregtar producto" value="{{ $proveedor }}">
                        <i class="fas fa-plus"></i>
                    </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @include('administracion.listaprecios.partials.datatable-showitems')
            </div>
        </div>
    </div>

    @include('administracion.listaprecios.partials.modal-show')

@endsection




@section('js')
    @include('partials.alerts')
    <script type="text/javascript" src="{{ asset('js/datatables-spanish.js') }}" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.js"></script>
    
    <script>
        /*$(document).ready(function() {
                    var tablaProductos = $('#tablaProductos').DataTable( {
                        "dom": "t",
                        "processing": true,
                        "scrollY": "35vh",
                        "scrollCollapse": true,
                        "paging": false,
                        "order": [0, 'asc'],
                        "columns": [
                            {
                                "data": "listaId",
                                "class": "align-middle text-center",
                            },
                            {
                                "data": "proveedorId",
                                "class": "align-middle text-center",
                            },
                            {
                                "data": "razon_social",
                                "class": "align-middle text-center",
                            },
                            {
                                "data": "producto_id",
                                "class": "align-middle text-center",
                            },
                            {
                                "data": "presentacion_id",
                                "class": "align-middle text-center",
                            },
                            {
                                "data": "codigoProv",
                                "class": "align-middle text-center",
                            },
                            {
                                "data": "droga",
                                "class": "align-middle",
                            },
                            {
                                "data": "detalle",
                                "class": "align-middle",
                            },
                            {
                                "data": "costo",
                                "class": "align-middle text-center",
                            },
                            {
                                "data": "updated_at",
                                "class": "align-middle text-center",
                            }
                        ],
                    });
        });*/

        //AGREGAR PRODUCTO - MODAL
        $(document).on('click', '.open_first_modal', function(){
            $.ajax({
                type: "GET",
                url: "{{route('administracion.listaprecios.agregar.producto')}}",
                success: function(data){
                    console.log(data);
                    var rProducto = data.dataResponse[0];
                        
                    for(var i = 0; i < rProducto.nombre.dataProductos.length; i++){
                        $(".seleccion-producto").prepend("<option value='"+ rProducto.nombre.dataProductos[i].productoId +"' selected='selected'>"+ rProducto.nombre.dataProductos[i].droga +"</option>");
                    }
                    var selProducto = new SlimSelect({
                        select: '.seleccion-producto',
                        placeholder: 'Seleccione el nombre de la droga...',
                    });
        
                    for(var i = 0; i < rProducto.detalle.dataPresentaciones.length; i++){
                        $(".seleccion-presentacion").prepend("<option value='"+ rProducto.detalle.dataPresentaciones[i].presentacionId +"' selected='selected'>"+ rProducto.detalle.dataPresentaciones[i].presentacion +"</option>");
                    }
                    var selPresentacion = new SlimSelect({
                        select: '.seleccion-presentacion',
                        placeholder: 'Seleccione la presentación de la droga...',
                    });
                },
            });
        });
        //AGREGAR PRODUCTO - SUBMIT DEL FORMULARIO
        $(document).on('submit','#formAgregProducto',function(event){
            event.preventDefault();
            console.log("Hola");
            $.ajax({
                url: '{{route('administracion.listaprecios.ingresar.producto')}}',
                method: 'POST',
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success:function(response)
                {
                    console.log(response); 
                    Swal.fire({
                        title: 'Agregar producto',
                        icon: response.alert,
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    debugger;
                    location.reload();
                },
                error: function(response) {
                    var errors = response.responseJSON;
                    errores = '';
                    $.each( errors, function( key, value ) {
                        errores += value;
                    });
                    Swal.fire({
                        icon: 'error',
                        text: errores,
                        showConfirmButton: true,
                    });
                }
            });
        });

        //BORRAR PRODUCTO
        function borrarItemListado(item) {
            var rs = document.getElementById('borrar-' + item).name;

            Swal.fire({
                icon: 'warning',
                title: 'Borrar Producto de Listado',
                text: 'Esta acción quitará el producto del listado del proveedor.',
                confirmButtonText: 'Borrar',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    debugger;
                    $('#borrar-' + item).submit();
                    window.location.replace('{{ route('administracion.listaprecios.show','rs') }}');
                }else if (
                    result.dismiss === Swal.DismissReason.cancel
                    ) {
                        swalWithBootstrapButtons.fire(
                            'Cancelado',
                        'Operación cancelada por usuario, no se quita el producto listado de proveedor',
                        'error'
                    )
                }
            });
        };
        
        //MODIFICAR PRODUCTO - MODAL
        $(document).on('click', '.open_modal', function(){
            $.ajax({
                type: "GET",
                url: "{{route('administracion.listaprecios.editar.producto')}}",
                data: {producto: $(this).val()},
                success: function(data){
                    $('#input-droga').val(
                        data.producto.droga +
                        " - " +
                        data.presentacion.forma +
                        ", " +
                        data.presentacion.presentacion
                        );
                        $('#input-listaId').val(data.producto_listaPrecio.id);
                        $('#input-codigoProv').val(data.producto_listaPrecio.codigoProv);
                        $('#input-costo').val(data.producto_listaPrecio.costo);
                    },
                });
            });
        //MODIFICAR PRODUCTO - SUBMIT DEL FORMULARIO
        $(document).on('submit','#formModifProducto',function(event){
            
            event.preventDefault();
            $.ajax({
                url: '{{route('administracion.listaprecios.actualizar.producto')}}',
                method: 'POST',
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success:function(response)
                {
                    Swal.fire({
                        title: 'Modificar producto',
                        icon: 'success',
                        text: response.success,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    debugger;
                    location.reload();
                },
                error: function(response) {
                    var errors = response.responseJSON;
                    errores = '';
                    $.each( errors, function( key, value ) {
                        errores += value;
                    });
                    Swal.fire({
                        icon: 'error',
                        text: errores,
                        showConfirmButton: true,
                    });
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
