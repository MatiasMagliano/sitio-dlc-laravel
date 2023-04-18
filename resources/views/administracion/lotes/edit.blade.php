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

    </style>
@endsection

@section('title', 'Administrar Lotes')

@section('content_header')
    <div class="row">
        <div class="col-xl-8">
            <h1>Administrar lotes del producto <strong>{{ $producto->droga }}</strong></h1>
        </div>
        <div class="col-xl-4 d-flex justify-content-xl-end">
            <a href="{{ route('administracion.lotes.index') }}" role="button"
                class="btn btn-md btn-secondary">Volver</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')
    <div class="card mr-2" id="lotesVigentes">
        <div class="card-header">
            <h3 id="tituloLotesVigentes" class="card-title">
                Lotes vigentes
            </h3>
        </div>
        <div class="card-body">
            <table id="tabla2" class="table table-responsive-md" width="100%">
                <thead>
                    <th>ID</th>
                    <th>Nº</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Vencimiento</th>
                    <th></th>
                </thead>
            </table>
        </div>
        <div class="card-footer">
            <h3>Total en stock:</u>&nbsp;<strong id="totalLotes"></strong></h3>
        </div>
    </div>

    <x-adminlte-card title="Agregar nuevo lote" id="agregarLote" theme="lime" icon="fas fa-plus mr-2">

    {{-- FORMULARIO TIPO JQUERY.VALIDATE --}}
        <form action="javascript:void(0)" method="post" id="formAgregarLote">
            @csrf

            <input type="hidden" id="producto_id" name="producto_id"
                value="{{ $producto->id }}">

            {{-- Campo identificador de lote --}}
            <div class="form-group mb-3">
                <label for="identificador"
                    class="label">{{ __('formularios.batch_identification') }}</label>
                <div class="input-group mb-3">
                    <input type="text" name="identificador" id="identificador" class="form-control"
                        value="" required>
                    <div class="input-group-append">
                        <button type="button" id="escanear" class="btn btn-sm btn-dark">
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
                    <label for="cantidad"
                        class="label">{{ __('formularios.batch_quantity') }}</label>
                    <input type="text" name="cantidad" id="cantidad" class="form-control" value="" required>
                </div>

                <div class="col-md-4 form-group mb-3">
                    @section('plugins.TempusDominusBs4', true)
                    <x-adminlte-input-date name="vencimiento" id="vencimiento"
                        label="{{ __('formularios.batch_expiration') }}" igroup-size="md"
                        :config="$config" placeholder="{{ __('formularios.date_placeholder') }}"
                        autocomplete="off" required>
                        <x-slot name="appendSlot">
                            <div class="input-group-text bg-dark">
                                <i class="fas fa-calendar"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input-date>
                </div>
            </div>

            {{-- Guardar lote nuevo --}}
            <div class="d-flex justify-content-end">
                <x-adminlte-button type="submit" theme="success" label="Guardar" id="guardarCliente" />
            </div>
        </form>
    </x-adminlte-card>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('js/datatables-spanish.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/localization/methods_pt.js"></script>
    <script>
        $(document).ready(function() {
            // VARIABLES GLOBALES
            var tabla2;
            var idProducto = {{ $producto->id }};
            getLotes(idProducto);

            // FUNCIÓN QUE GENERA LA TABLA DE LOS LOTES
            function getLotes(idProducto) {
                var datos = {
                    idProducto: idProducto
                };

                $.ajax({
                    url: "{{ route('administracion.lotes.buscarLotes') }}",
                    type: "GET",
                    data: datos,
                }).done(function(resultado) {
                    tabla2.clear();
                    tabla2.rows.add(resultado).draw();
                });
            }

            tabla2 = $('#tabla2').DataTable({
                "processing": true,
                "order": [
                    [4, "asc"]
                ],
                "paging": false,
                "info": false,
                "searching": false,
                "select": false,
                "columns": [{
                        targets: [0],
                        visible: false,
                        data: 'id',
                        searchable: false
                    },
                    {
                        targets: [1],
                        data: 'identificador',
                    },
                    {
                        targets: [2],
                        data: 'precioCompra'
                    },
                    {
                        targets: [3],
                        data: 'cantidad'
                    },
                    {
                        targets: [4],
                        data: 'hasta'
                    }
                ],
                "columnDefs": [{
                        targets: 1,
                        width: 100,
                    },
                    {
                        targets: 4,
                        render: $.fn.dataTable.render.moment('DD/MM/YYYY'),
                    },
                    {
                        targets: 5,
                        data: null,
                        width: 30,
                        defaultContent:
                        "<button id='btnBorrar' class='btn-xs btn-primary'><i class='fa fa-lg fa-fw fa-trash'></i></button>"
                    }
                ],
                "fnDrawCallback": function(row, data, start, end, display) {
                    var api = this.api(), data;

                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };

                    var total = api
                        .column(3)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        },
                        0
                    );

                    $('#totalLotes').text(total);
                }
            });

            // agrega items en la tabla 2
            tabla2.row.add({
                id: '*',
                identificador: '*',
                precioCompra: '*',
                cantidad: '*',
                hasta: '*',
            }).draw();

            // ELIMINACIÓN DEL LOTE
            $('#tabla2 tbody').on('click', 'button', function(e) {
                var lote = tabla2.row($(this).parents('tr')).data().identificador;

                Swal.fire({
                    icon: 'warning',
                    title: 'Eliminación de lotes',
                    text: '¿Está seguro de eliminar el lote Nº ' + lote + '?',
                    confirmButtonText: 'Borrar',
                    showCancelButton: true,
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.isConfirmed) {
                        var idLote = tabla2.row($(this).parents('tr')).data().id;
                        var datos = {
                            id: idLote,
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
                            dataType: "json",
                            success: function(response) {
                                //sweet alert
                                Swal.fire(
                                    'Eliminado',
                                    response.mensaje,
                                    'success'
                                );

                                // Se actualiza la tabla
                                getLotes(idProducto);
                            },
                            error: function(response) {
                                console.log(response);
                                //sweet alert
                                Swal.fire(
                                    'Algo salió mal...',
                                    response,
                                    'error'
                                );

                                // Se actualiza la tabla
                                getLotes(idProducto);
                            }
                        });
                    }
                });
            });

            //BOTON ESCANEAR - SIN IMPLEMENTAR
            $('#escanear').on('click', function() {
                alert("Escanee el código de barras");
            });

            //AGREGAR LOTE --> VALIDACIÓN DEL FORMULARIO POR JQUERY
            if ($('#formAgregarLote').length > 0) {
                $('#formAgregarLote').validate({
                    rules: {
                        identificador: {
                            required: true,
                            minlength: 5,
                            maxlength: 20
                        },
                        precio: {
                            required: true,
                            number: true,
                        },
                        cantidad: {
                            required: true,
                            number: true,
                            digits: true,

                        },
                        vencimiento: {
                            required: true,
                            date: true,
                        }
                    },
                    messages: {
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
                            date: "La fecha ingresada no es valida"
                        },
                    },
                    errorElement: 'span',
                    errorPlacement: function(error, element) {
                        error.addClass('invalid-feedback');
                        element.closest('.form-group').append(error);
                    },
                    submitHandler: function(form) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: "{{ route('administracion.lotes.store') }}",
                            type: "POST",
                            data: $('#formAgregarLote').serialize(),
                            success: function(response) {
                                $('#submit').html('Submit');
                                $("#submit").attr("disabled", false);

                                //sweet alert
                                Swal.fire({
                                    title: 'Agregar lote',
                                    icon: 'success',
                                    text: response.mensaje,
                                    showConfirmButton: false,
                                    timer: 2500
                                });

                                getLotes(idProducto);

                                // se resetea el formulario de AGREGAR LOTES
                                document.getElementById("formAgregarLote").reset();
                            }, //fin del ajax.success
                            error: function(response) {
                                $("#submit").attr("disabled", true);
                                var respuesta = JSON.parse(response.responseText);
                                //sweet alert
                                Swal.fire({
                                    icon: 'error',
                                    text: respuesta.errors.identificador,
                                    showConfirmButton: true,
                                });

                                getLotes(idProducto);

                                // se resetea el formulario de AGREGAR LOTES
                                document.getElementById("formAgregarLote").reset();
                            }
                        });
                    }
                });
            }
        });
    </script>
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión de software 2.8</b> (PHP: v{{phpversion()}} | LARAVEL: v.{{App::VERSION()}})
    </div>
@endsection
