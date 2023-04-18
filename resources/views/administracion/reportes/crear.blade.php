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
            <h1>Crear reporte</h1>
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

            <div class="card-body">
                <x-adminlte-card title="Seleccione un cliente *" theme="lightblue" theme-mode="outline"
                    body-class=" bg-gradient-lightblue">
                    <div class="form-group">
                        <select name="reporte_o_listado" id="input-reporte"
                            class="form-control @error('reporte_id') is-invalid @enderror"
                            aria-label="Selección del tipo de reporte">
                            <option selected disabled>Seleccione un tipo de reporte...</option>
                            <option value="reporte">Reporte</option>
                            <option value="listado">Listado</option>
                        </select>
                        @error('reporte_o_listado')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </x-adminlte-card>


                {{-- DIV QUE CARGA UNA VISTA DE REPORTE O DE LISTADO YA RENDEREADA --}}
            @section('plugins.Summernote', true)
            <div id="contenido"></div>
        </div>
    </div>
</form>
@endsection

@section('js')
@include('partials.alerts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.js"></script>

<script type="text/javascript">
    var maxCamposEncabezado = 1; //cantidad maxima permitida de campos
    var maxCamposCuerpo = 5; //cantidad maxima permitida de campos
    var maxListados = 5; //cantidad maxima permitida de campos
    //var addButtonEncabezado = $('#btn_crear_campo_encabezado'); //selector botón agregar campos
    var addButtonCuerpo = $('#btn_crear_campo_reporte'); //selector botón agregar campos
    var addButtonListado = $('#btn_crear_listado'); //selector botón agregar campos
    //var wrapperEncabezado = $('#wrapper-encabezado'); //wrapper que contiene los summernotes
    var wrapperCuerpo = $('#wrapper-campos'); //wrapper que contiene los summernotes
    var wrapperListado = $('#wrapper-listados'); //wrapper que contiene los summernotes
    var y = 1; //contador inicial de campos de encabezado
    var x = 1; //contador inicial de campos de cuerpo
    var z = 1; //contador inicial de listados

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

    // lógica que agrega CAMPOS AL ENCABEZADO DEL REPORTE
    $("#contenido").on("click", "#btn_crear_campo_encabezado", function() {
        //Check maximum number of fields
        if (y < maxCamposEncabezado) {
            let fieldHTML =
                '<div><button type="button" class="btn btn-sm btn-danger remove_button"><i class="fas fa-minus"></i></button>&nbsp;&nbsp;&nbsp;<label for="campo-encabezado-' +
                y + '">Campo adicional encabezado Nº' + y +
                ' *</label><div style="width: 100%"><textarea name="campo-adicional-encabezado-' +
                y + '" id="campo-encabezado-' + y +
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
    $(addButtonCuerpo).click(function() {
        //Check maximum number of fields
        if (x < maxCamposCuerpo) {
            let fieldHTML =
                '<div><button type="button" class="btn btn-sm btn-danger remove_button"><i class="fas fa-minus"></i></button>&nbsp;&nbsp;&nbsp;<label for="campo-reporte-' +
                x + '">Campo adicional Nº' + x +
                ' *</label><div style="width: 100%"><textarea name="campo-reporte-' + x +
                '" id="campo-reporte-' + x +
                '"" class="form-control campo-reporte"></textarea></div></div>';
            $(wrapperCuerpo).append(fieldHTML); //Add field html
            $('.campo-reporte').summernote();
            x++; //Increment field counter
        }
    });
    $(wrapperCuerpo).on('click', '.remove_button', function(e) {
        e.preventDefault();
        $(this).parent('div').remove(); //Remove field html
        x--; //Decrement field counter
    });


    // lógica que agrega LISTADOS AL REPORTE
    $(addButtonListado).click(function() {
        //Check maximum number of fields
        if (z < maxListados) {
            let fieldHTML =
                '<div class="form-group"><button type="button" class="btn btn-sm btn-danger remove_button"><i class="fas fa-minus"></i></button>&nbsp;&nbsp;&nbsp;<label for="seleccion-listado-' +
                z +
                '">Listado anexado Nº' + z +
                ' *</label><select name="cliente_id" id="input-cliente" class="form-control seleccion-listado-' +
                z +
                ' form-control-alternative"><option data-placeholder="true"></option></select></div>';
            $(wrapperListado).append(fieldHTML); //Add field html
            llenarSelect('.seleccion-listado-' + z);
            z++; //Increment field counter
        }
    });
    $(wrapperListado).on('click', '.remove_button', function(e) {
        e.preventDefault();
        $(this).parent('div').remove(); //Remove field html
        z--; //Decrement field counter
    });

    $(document).ready(function() {
        $("#input-reporte").on('change', function(event) {
            $.get(
                '{{ route('administracion.ajax.load.view') }}', {
                    seleccion: this.value
                },
                function(vista) {
                    $("#contenido").html(vista);

                    // activación del summernote del encabezado
                    $('#campo-encabezado').summernote({
                        focus: true,
                        disableResizeEditor: true,
                    });

                    // activación del slimselect de reportes
                    var reporte = new SlimSelect({
                        select: '.selector-reporte',
                        placeholder: 'Seleccione un reporte inicial...',
                    });
                }
            );
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
