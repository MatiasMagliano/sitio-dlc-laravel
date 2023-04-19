@extends('adminlte::page')

@section('title', 'Administrar Clientes')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.css" />
    <style>
        .texto-header {
            padding: 0 20px;
            height: 90px;
            overflow-y: auto;
            /*font-size: 14px;*/
            font-weight: 500;
            color: #000000;
        }

        .texto-header::-webkit-scrollbar {
            width: 5px;
            background-color: #282828;
        }

        .texto-header::-webkit-scrollbar-thumb {
            background-color: #3bd136;
        }

        .box {
            display: none;
        }

        @media (max-width: 600px) {
            .hide {
                display: none;
            }
        }
    </style>
@endsection

@section('content_header')
    <div class="row">
        <div class="col-md-8">
            <h1>Crear documento</h1>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <a href="{{ route('administracion.reportes.index') }}" role="button" class="btn btn-md btn-secondary">Volver</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')
    <form action="{{ route('administracion.reportes.store') }}" method="post" enctype=multipart/form-data
        class="needs-validation" autocomplete="off" novalidate>
        @csrf

        <div class="card">
            <div class="card-header">
                <div class="row d-flex">
                    <div class="col-10 texto-header">
                        <h5>Nuevo reporte o listado</h5>
                        <p>En esta sección ud. podrá crear y guardar un reporte o un listado de su elección.</p>
                        <p>En caso de reporte o listado, los formatos serán muy diferentes. En el caso del primero, la
                            posibilidad de agregar varios módulos o listados,
                            incluyendo campos de texto que podrá personalizar.</p>
                    </div>

                </div>
            </div>

            {{-- MODAL AGREGAR PROVEEDOR - FORMULARIO JAVASCRIPT --}}
            <div class="modal fade" id="modalSelecionDocumento" tabindex="-1" aria-labelledby="" aria-hidden="true">
                <div class="modal-dialog modal-md modal-dialog-centered">
                    <div class="modal-content">

                        {{-- CABECERA --}}
                        <div class="modal-header bg-gradient-lightblue">
                            <h5 class="modal-title">Selección tipo de documento</h5>
                        </div>

                        {{-- CUERPO --}}
                        <div class="modal-body">
                            <div class="form-group">
                                <select name="reporte_o_listado" id="input-reporte" class="form-control">
                                    <option selected disabled>Seleccione un documento...</option>
                                    <option value="reporte" {{ old('reporte_o_listado') == 'reporte' ? 'selected' : '' }}>
                                        Reporte</option>
                                    <option value="listado" {{ old('reporte_o_listado') == 'listado' ? 'selected' : '' }}>
                                        Listado</option>
                                </select>
                            </div>
                        </div>

                        {{-- FOOTER --}}
                        <div class="modal-footer">
                            <button id="btnSeleccDoc" class="btn btn-sm btn-success">Seleccionar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
            @section('plugins.Summernote', true)
            <div class="reporte box">
                @include('administracion.reportes.partials.nuevo-reporte')
            </div>

            <div class="listado box">
                @include('administracion.reportes.partials.nuevo-listado')
            </div>
        </div>
    </div>
    </div>
</form>
@endsection

@section('js')
@include('partials.alerts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.js"></script>

<script type="text/javascript">
    var maxCamposEncabezado = 3; //cantidad maxima permitida de campos
    var maxCamposCuerpo = 5; //cantidad maxima permitida de campos
    var maxListados = 5; //cantidad maxima permitida de campos
    var y = 1; //contador inicial de campos de encabezado
    var x = 1; //contador inicial de campos de cuerpo
    var z = 1; //contador inicial de listados

    // lanza el modal al inicio de la página
    $(window).on('load', function() {
        if ('{{ old('reporte_o_listado', 'ninguno') }}' === 'ninguno') {
            $('#modalSelecionDocumento').modal({
                    backdrop: 'static',
                    keyboard: false
                },
                'show'
            );
        } else {
            iniciarForm();
            $('#modalSelecionDocumento').modal('hide')
        }
    });

    function llenarSelect(selector) {
        $.get(
            '{{ route('administracion.ajax.obtener.listados') }}',
            function(data) {
                var objeto = $(selector);
                objeto.empty();
                for (var i = 0; i < data.length; i++) {
                    if (i == 0) {
                        objeto.append(
                            '<option value="" selected disabled>Seleccione un tipo de listado...</option>');
                    }
                    objeto.append('<option value="' + data[i].id + '">' + data[i].nombre + '</option>');
                }
            });
    }

    function iniciarForm() {
        // activación del summernote del encabezado
        $('.campo-encabezado').summernote({
            focus: true,
            disableResizeEditor: true,
        });

        // activación del slimselect de reportes
        var reporte = new SlimSelect({
            select: '.selector-reporte',
            placeholder: 'Seleccione un reporte inicial...',
        });

        // activación del slimselect de listados
        var reporte = new SlimSelect({
            select: '.selector-listado-ppal',
            placeholder: 'Seleccione un reporte inicial...',
        });

        $("#input-reporte").find("option:selected").each(function() {
            var optionValue = $(this).attr("value");
            if (optionValue) {
                $(".box").not("." + optionValue).hide();
                $("." + optionValue).show();
            } else {
                $(".box").hide();
            }
        });
        $('#modalSelecionDocumento').modal('hide');
    }

    // lógica que agrega CAMPOS AL ENCABEZADO DEL REPORTE
    $("#contenido").on("click", "#btn_crear_campo_encabezado", function() {
        //Check maximum number of fields
        if (y < maxCamposEncabezado) {
            let fieldHTML =
                '<div><button type="button" class="btn btn-sm btn-danger remove_button"><i class="fas fa-minus"></i></button>&nbsp;&nbsp;&nbsp;<label for="campo-encabezado-' +
                y + '">Campo adicional encabezado Nº' + y +
                ' *</label><div style="width: 100%"><textarea name="campo-adicional-encabezado[]" id="campo-encabezado-' +
                y +
                '" class="form-control campo-adicional-encabezado"></textarea></div></div>';
            $("#contenido").find("#wrapper-encabezado").append(fieldHTML); // Agrega el campo html
            $('.campo-adicional-encabezado').summernote();
            y++; // Incrementa el contador de campos de encabezado
        }
    });
    $('#contenido').on('click', '#wrapper-encabezado .remove_button', function(e) {
        e.preventDefault();
        $(this).parent('div').remove(); // Borra el campo html
        y--; // Decrementa el contador de encabezados
    });



    // lógica que agrega CAMPOS AL CUERPO DEL REPORTE
    $("#contenido").on("click", "#btn_crear_campo_reporte", function() {
        //Check maximum number of fields
        if (x < maxCamposCuerpo) {
            let fieldHTML =
                '<div><button type="button" class="btn btn-sm btn-danger remove_button"><i class="fas fa-minus"></i></button>&nbsp;&nbsp;&nbsp;<label for="campo-reporte-' +
                x + '">Campo adicional Nº' + x +
                ' *</label><div style="width: 100%"><textarea name="campo-cuerpo-' + x +
                '" id="campo-cuerpo-' + x +
                '"" class="form-control campo-cuerpo"></textarea></div></div>';
            $("#contenido").find("#wrapper-campos").append(fieldHTML); //Add field html
            $('.campo-cuerpo').summernote();
            x++; //Increment field counter
        }
    });
    $("#contenido").on('click', '#wrapper-campos .remove_button', function(e) {
        e.preventDefault();
        $(this).parent('div').remove(); //Remove field html
        x--; //Decrement field counter
    });


    // lógica que agrega LISTADOS AL REPORTE
    $("#contenido").on("click", "#btn_crear_listado", function() {
        //Check maximum number of fields
        if (z < maxListados) {
            // se crea un select con el listado de los listados, jaja
            let fieldHTML =
                '<div class="form-group"><button type="button" class="btn btn-sm btn-danger remove_button"><i class="fas fa-minus"></i></button>&nbsp;&nbsp;&nbsp;<label for="seleccion-listado-' +
                z +
                '">Listado anexado Nº' + z +
                ' *</label><select name="reporte_id[]" id="input-cliente" class="form-control seleccion-listado-' +
                z +
                ' form-control-alternative"><option data-placeholder="true"></option></select></div>';
            $("#contenido").find("#wrapper-listados").append(fieldHTML); //Add field html
            // se enecesita numerarlos para llenarlos oportunamente
            llenarSelect('.seleccion-listado-' + z);
            z++; //Increment field counter
        }
    });
    $("#contenido").on('click', '#wrapper-listados .remove_button', function(e) {
        e.preventDefault();
        $(this).parent('div').remove(); //Remove field html
        z--; //Decrement field counter
    });

    $(document).ready(function() {
        $("#btnSeleccDoc").on('click', function(event) {
            event.preventDefault();
            iniciarForm();
        });
    });
</script>
@endsection

@section('footer')
<strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
<div class="float-right d-none d-sm-inline-block">
    <b>Versión de software 2.8</b> (PHP: v{{ phpversion() }} | LARAVEL: v.{{ App::VERSION() }})
</div>
@endsection
