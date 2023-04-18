@extends('adminlte::page')
@section('css')
    <style>
        .dataTable>thead>tr>th[class*="sort"]:before,
        .dataTable>thead>tr>th[class*="sort"]:after {
            content: "" !important;
        }

        #tabla1 thead th {
            border-bottom: none;
        }

        #tabla1 tfoot th {
            border-top: none;
            border-bottom: 1px solid #111;
        }

        .dataTables_filter {
            display: none;
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
    </style>
@endsection

@section('title', 'Administrar Lotes')

@section('content_header')
    <div class="row">
        <div class="col-xl-8">
            <h1>Administración de lotes</h1>
        </div>
        <div class="col-xl-4 d-flex justify-content-xl-end">
            <a href="{{ route('home') }}" role="button" class="btn btn-md btn-secondary">Volver</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
<div class="card-group mb-4">
    <div class="card mr-2">
        <div class="card-header">
            <div id="nav_search" class="input-group search">
                <input id="search_box" class="form-control" type="text"
                    placeholder="Seleccionar droga o presentación...">
                <div class="input-group-append">
                    <button type="submit"><i class="fas fa-search "></i></button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table id="tabla1" class="table table-borderless" style="width: 100%; cursor:pointer">
                <thead style="display: none">
                    <th>droga</th>
                </thead>
                <tbody>
                    @foreach ($productos as $producto)
                        @foreach ($producto->presentaciones($producto->id) as $presentacion)
                            <tr id="{{ $producto->id }}" value="{{ $presentacion->id }}">
                                <td>{{ $producto->droga }} - {{ $presentacion->forma }},
                                    {{ $presentacion->presentacion }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card ml-2" id="lotesVigentes">
        <div class="card-header">
            <h3>
                Lotes vigentes
            </h3>
        </div>
        <div class="card-body">
            <div class="card">
                <div class="card-header bg-gradient-blue">
                    <h5 id="tituloLotesVigentes" class="card-title">
                    </h5>
                </div>
                <div class="card-body h-25">
                    <table id="tabla2" class="display nowrap" style="width: 100%; cursor:pointer">
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
            </div>

            <div class="card">
                <div class="card-header bg-gradient-green">
                    <h5>Agregar nuevo lote</h5>
                </div>
                <div class="card-body">
                    {{-- FORMULARIO TIPO JQUERY.VALIDATE --}}
                    <form action="javascript:void(0)" method="post" id="formAgregarLote">
                        @csrf

                        <input type="hidden" id="producto_id" name="producto_id" value="">
                        <input type="hidden" id="presentacion_id" name="presentacion_id" value="">

                        {{-- Campo identificador de lote, cantidad y precio --}}
                        <div class="row">
                            <div class="form-group col-md-4 mb-3">
                                <label for="identificador" class="label">Identificador/Nº Lote *</label>
                                <div class="input-group mb-3">
                                    <input type="text" name="identificador" id="identificador" class="form-control"
                                        value="" required>
                                </div>
                            </div>

                            <div class="form-group col-md-4 mb-3">
                                <label for="cantidad" class="label">Cantidad *</label>
                                <input type="text" name="cantidad" id="cantidad" class="form-control" value=""
                                    required>
                            </div>

                            <div class="form-group col-md-4 mb-3">
                                <label for="precio_compra" class="label">Precio de compra *</label>
                                <input type="text" name="precio_compra" id="precio_compra" class="form-control"
                                    value="" required>
                            </div>
                        </div>

                        {{-- Campos de fecha de compra, elaboración y vencimiento --}}
                        <div class="row">
                            @section('plugins.TempusDominusBs4', true)

                            <div class="form-group col-md-4 mb-3">
                                <x-adminlte-input-date name="fecha_compra" id="fecha_compra" label="Fecha compra *"
                                    igroup-size="md" :config="$config"
                                    placeholder="{{ __('formularios.date_placeholder') }}" autocomplete="off" required>
                                    <x-slot name="appendSlot">
                                        <div class="input-group-text bg-dark">
                                            <i class="fas fa-calendar"></i>
                                        </div>
                                    </x-slot>
                                </x-adminlte-input-date>
                            </div>

                            <div class="form-group col-md-4 mb-3">
                                <x-adminlte-input-date name="fecha_elaboracion" id="fecha_elaboracion"
                                    label="Fecha elaboración *" igroup-size="md" :config="$config"
                                    placeholder="{{ __('formularios.date_placeholder') }}" autocomplete="off" required>
                                    <x-slot name="appendSlot">
                                        <div class="input-group-text bg-dark">
                                            <i class="fas fa-calendar"></i>
                                        </div>
                                    </x-slot>
                                </x-adminlte-input-date>
                            </div>

                            <div class="form-group col-md-4 mb-3">
                                <x-adminlte-input-date name="fecha_vencimiento" id="fecha_vencimiento"
                                    label="Fecha vencimiento *" igroup-size="md" :config="$config_vencimiento"
                                    placeholder="{{ __('formularios.date_placeholder') }}" autocomplete="off"
                                    required>
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
                </div>
            </div>
        </div>
        <div class="overlay"><i class="fas fa-ban text-gray"></i></div>
    </div>
</div>
@endsection

@section('js')
@include('partials.alerts')
<script type="text/javascript" src="{{ asset('js/datatables-spanish.js') }}" defer></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/localization/methods_pt.js"></script>
<script>
    $(document).ready(function() {
        // VARIABLES LOCALES
        var tabla2;

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
                tabla2.clear();
                tabla2.rows.add(resultado).draw();
            });
        }

        // datatable LOTES
        tabla2 = $('#tabla2').DataTable({
            "processing": true,
            "scrollY": '20vh',
            "scrollCollapse": true,
            "order": [
                [4, "asc"]
            ],
            "paging": false,
            "info": false,
            "searching": false,
            "select": false,
            "columnDefs": [{
                    targets: [0],
                    visible: false,
                    data: 'id',
                    searchable: false,
                    width: 150,
                },
                {
                    targets: [1],
                    data: 'identificador',
                    width: 200,
                },
                {
                    targets: [2],
                    data: 'precio_compra'
                },
                {
                    targets: [3],
                    data: 'cantidad'
                },
                {
                    targets: [4],
                    data: 'fecha_vencimiento',
                    type: 'datetime',
                    render: function(data) {
                        return moment(new Date(data)).format('DD/MM/YYYY');
                    }
                },
                {
                    targets: 5,
                    data: null,
                    width: 30,
                    defaultContent: "<button id='btnBorrar' class='btn-xs btn-primary'><i class='fa fa-lg fa-fw fa-trash'></i></button>"
                }
            ],
            "rowCallback": function(row, data, index) {
                if (data.fecha_vencimiento < moment(new Date()).format('YYYY-MM-DD HH:mm:ss')) {
                    $('td', row).css('color', 'Brown');
                }
                if (data.eliminado != null) {
                    $('td', row).css('background-color', 'Red');
                    //$('td', row).class('overlay');
                }
            },
        });

        tabla2.row.add({
            id: '*',
            identificador: '*',
            precio_compra: '*',
            cantidad: '*',
            fecha_vencimiento: '*',
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
                    //alert("lote_id: "+ idLote);
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
                        url: "{{ url('administracion/lotes/eliminarLote') }}",
                        type: "DELETE",
                        data: datos,
                        success: function(response) {
                            //sweet alert
                            Swal.fire('Eliminado', response.mensaje, 'success');

                            // Se actualiza la segunda tabla
                            var idProducto = $(
                                'input[type=hidden][name=producto_id]').val();
                            let idPresentacion = $(
                                'input[type=hidden][name=presentacion_id]')
                            .val();
                            getLotes(idProducto, idPresentacion);
                        },
                        error: function(response) {
                            //console.log(response);
                            //sweet alert
                            Swal.fire('Algo salió mal...', response.message,
                                'error');
                        }
                    });
                }
            });
        });

        //BOTON ESCANEAR - SIN IMPLEMENTAR
        $('#escanear').on('click', function() {
            alert("Escanee el código de barras");
        });

        // DEFINICION DE tabla1 --> PRODUCTO/PRESENTACION
        var tabla1 = $('#tabla1').DataTable({
            "responsive": true,
            "dom": 'Pfrtip',
            "sDom": '<"search-box"r>lftip',
            "scrollY": "70vh",
            "scrollCollapse": true,
            "paging": false,
            "select": true,
            "order": [0, 'asc'],
            "bInfo": false,
        });

        //RELOCACIÓN DEL TEXTBOX DE BÚSQUEDA
        $('#search_box').keyup(function() {
            tabla1.search($(this).val()).draw();
        });

        // CAPTURA DEL CLICK EN EL DATATABLE PRODUCTO/PRESENTACION
        $('#tabla1 tbody tr').on('click', function() {
            // marcado/desmarcado del row seleccionado
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
            } else {
                tabla1.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }

            // se sigue con el resto del procedimiento
            $('#lotesVigentes .overlay').remove();

            let idx = tabla1.cell('.selected', 0).index();
            let prod_sel = tabla1.row(idx.row).data();
            $('#tituloLotesVigentes').text('Detalle de ' + prod_sel[0]);

            // se guardan el producto_id y presentacion_id en los inputs ocultos
            $('#producto_id').val($(this).attr("id"));
            $('#presentacion_id').val($(this).attr("value"));

            // Se genera la segunda tabla con la característica destroy, para poder recargarla con los nuevos datos
            getLotes($(this).attr("id"), $(this).attr("value"));

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
                    fecha_compra: {
                        required: true,
                        date: true,
                    },
                    fecha_elaboracion: {
                        required: true,
                        date: true,
                    },
                    fecha_vencimiento: {
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
                    fecha_compra: {
                        required: "Este es un campo requerido",
                        date: "La fecha ingresada no es valida"
                    },
                    fecha_elaboracion: {
                        required: "Este es un campo requerido",
                        date: "La fecha ingresada no es valida"
                    },
                    fecha_vencimiento: {
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

                            // Se actualiza la segunda tabla con la característica destroy, para poder recargarla con los nuevos datos
                            let idProducto = $('input[type=hidden][name=producto_id]')
                                .val();
                            let idPresentacion = $(
                                    'input[type=hidden][name=presentacion_id]')
                                .val();
                            getLotes(idProducto, idPresentacion);

                            // se resetea el formulario de AGREGAR LOTES
                            document.getElementById("formAgregarLote").reset();
                        }, //fin del ajax.success
                        error: function(response) {
                            $("#submit").attr("disabled", true);
                            let respuesta = JSON.parse(response.responseText);
                            //sweet alert
                            Swal.fire({
                                icon: 'error',
                                text: respuesta.message,
                                showConfirmButton: true,
                            });

                            // Se actualiza la segunda tabla con la característica destroy, para poder recargarla con los nuevos datos
                            let idProducto = $('input[type=hidden][name=producto_id]')
                                .val();
                            let idPresentacion = $(
                                    'input[type=hidden][name=presentacion_id]')
                                .val();
                            getLotes(idProducto, idPresentacion);

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
