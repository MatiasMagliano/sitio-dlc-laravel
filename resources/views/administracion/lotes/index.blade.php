@extends('adminlte::page')
@section('css')
    <style>
        tabla1.dataTable thead th {
            border-bottom: none;
        }

        tabla1.dataTable tfoot th {
            border-top: none;
            border-bottom: 1px solid  #111;
        }

        .selectorProducto{
            height: 100%;
        }

        .lotesVigentes{
            height: 100%;
        }
    </style>
@endsection

@section('title', 'Administrar Lotes')

@section('content_header')
    <div class="row">
        <div class="col-xl-8">
            <h1>Administrar/agregar lotes a productos</h1>
        </div>
        <div class="col-xl-4 d-flex justify-content-xl-end">
            <a href="{{ route('administracion.productos.index') }}" role="button"
                class="btn btn-md btn-secondary">Volver</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')
    <div class="row">
        <div class="col-md-6">
            <x-adminlte-card title="Seleccionar producto" icon="fas fa-search" class="selectorProducto">
                <table id="tabla1" class="tabla1" style="width: 100%; cursor:pointer">
                    <thead>
                        <th>ID</th>
                        <th>Droga</th>
                    </thead>
                    <tbody>
                        @foreach ($productos as $producto)
                            <tr>
                                <td>{{ $producto->id }}</td>
                                <td>{{ $producto->droga }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-adminlte-card>
        </div>
        <div class="col-md-6">
            <x-adminlte-card title="Lotes vigentes" id="lotesVigentes" icon="fas fa-plus" class="lotesVigentes" disabled>
                <table id="tabla2" class="display nowrap" style="width: 100%">
                    <thead>
                        <th>Nº</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Vencimiento</th>
                    </thead>
                </table>
                <hr>

                <x-adminlte-card title="Agregar nuevo lote" id="agregarLote" theme="lime">
                    <form action="{{ route('administracion.lotes.store') }}" method="post" id="formAgregarLote">
                        @csrf

                        <input type="hidden" id="producto_id" name="producto_id" value="null">

                        {{-- Campo identificador de lote --}}
                        <div class="form-group mb-3">
                            <label for="identificador" class="label">{{ __('formularios.batch_identification') }}</label>
                            <div class="input-group mb-3">
                                <input type="text" name="identificador" id="identificador" class="form-control" value="" required>
                                <div class="input-group-append">
                                    <button type="button" id="escanear" class="btn btn-sm btn-primary">
                                        <i class="fas fa-barcode"></i>
                                        Escanear
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Campos de precio, cantidad y vencimiento --}}
                        <div class="row">
                            <div class="col-md-4 form-group mb-3">
                                <label for="precio" class="label">{{ __('formularios.batch_price') }}</label>
                                <input type="text" name="precio" id="precio" class="form-control" value="" required>
                            </div>

                            <div class="col-md-4 form-group mb-3">
                                <label for="cantidad" class="label">{{ __('formularios.batch_quantity') }}</label>
                                <input type="text" name="cantidad" id="cantidad" class="form-control" value="">
                            </div>

                            <div class="col-md-4 form-group mb-3">
                                @section('plugins.TempusDominusBs4', true)
                                <x-adminlte-input-date name="vencimiento" id="vencimiento" label="{{ __('formularios.batch_expiration') }}" igroup-size="md"
                                    :config="$config" placeholder="{{ __('formularios.date_placeholder') }}">
                                    <x-slot name="appendSlot">
                                        <div class="input-group-text bg-dark">
                                            <i class="fas fa-calendar"></i>
                                        </div>
                                    </x-slot>
                                </x-adminlte-input-date>
                            </div>
                        </div>

                        {{-- Register button --}}
                        <div class="d-flex justify-content-end">
                            <x-adminlte-button data-dismiss="modal" theme="secondary" label="Cancelar" style="margin: 0 10px"/>
                            <x-adminlte-button type="submit" theme="success" label="Guardar" id="guardarCliente"/>
                        </div>
                    </form>
                </x-adminlte-card>
            </x-adminlte-card>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('js/datatables-spanish.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function() {
            //VALIDACIÓN DEL FORMULARIO POR JQUERY
            $('#formAgregarLote').validate({
                rules:{
                    identificador:{
                        required: true,
                        minlength: 5,
                        maxlength: 20
                    },
                    precio:{
                        required: true,
                        number: true,
                    },
                    cantidad:{
                        required: true,
                        number: true,
                        digits: true,

                    },
                    vencimiento:{
                        required: true,
                        date: true,
                    }
                },
                messages:{
                    identificador: {
                        required: "Este es un campo requerido",
                        minlength: "El número mínimo de caracteres es 5",
                        maxlength: "Excedido el número máximo de caracteres"
                    },
                    precio: {
                        required: "Este es un campo requerido",
                        number: "El campo es numérico"
                    },
                    cantidad: {
                        required: "Este es un campo requerido",
                        number: "El campo es numérico",
                        digits: "El campo es numérico entero"
                    },
                    vencimiento: {
                        required: "Este es un campo requerido",
                        date: "La fecha ingresada no es válida"
                    },
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                }
            });

            //BOTON ESCANEAR - SIN IMPLEMENTAR
            $('#escanear').on('click', function(){
                alert("Escanee el código de barras");
            });

            //DATATABLES
            var tabla1 = $('#tabla1').DataTable({
                "responsive": true,
                "dom": 'Pfrtip',
                "scrollY": "50vh",
                "scrollCollapse": true,
                "paging": false,
                "select": true,
                "columns":[
                    {data: 'ID', visible: false, searchable: false},
                    {data: 'Droga'}
                ]
            });

            $('#tabla1 tbody').on('click', 'tr', function(){
                $('#lotesVigentes .overlay').remove();
                var idProducto = tabla1.row(this).data().ID;
                $('#producto_id').val(idProducto);
                var datos = {idProducto: idProducto};

                // Se genera la segunda tabla con la característica destroy, para poder recargarla con los nuevos datos
                $('#tabla2').DataTable({
                    "processing": true,
                    "order": [[3, "desc"]],
                    "paging": false,
                    "info": false,
                    "searching": false,
                    "ajax": {
                        "data": datos,
                        "dataSrc": "",
                        "url": "{{ route('administracion.lotes.buscarLotes') }}",
                        "type": "GET",
                    },
                    "columns": [
                        {data: 'identificador', name: 'Nº'},
                        {data: 'precioCompra', name: 'Precio'},
                        {data: 'cantidad', name: 'Cantidad'},
                        {data: 'hasta', name: 'Vencimiento'}
                    ],
                    "destroy": true
                });

                //alert( 'El id del producto es: '+idProducto );
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
