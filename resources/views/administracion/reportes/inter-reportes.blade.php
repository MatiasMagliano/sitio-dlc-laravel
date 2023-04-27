@extends('adminlte::page')

@section('title', 'Editar cuerpo de REPORTE')

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
    <h1>Editar reporte</h1>
    <hr>
    <div class="card">
        <div class="card-header">
            <div class="texto-header">
                <h5>Nuevo reporte </h5>
                <p>En esta sección ud. podrá crear, editar y guardar el cuerpo del reporte.</p>
                <p>Desde el botón <span class="btn btn-sm"><i class="fas fa-plus"></i></span> de cada sección podrá agregar
                    encabezados, listados y todo texto que agregue valor al reporte.</p>
            </div>
        </div>

        <div class="card-body">
            <h5 class="heading-small text-muted pb-3 pb-1">Básicos del documento</h5>
            @include('administracion.reportes.partials.datos-documento')
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')

    <form action="{{ route('administracion.reportes.guardarDocumento', ['documento' => $documento]) }}" method="post" enctype=multipart/form-data
        class="needs-validation" autocomplete="off" novalidate>
        @csrf

        <div class="card">
            <div class="card-header">
                <h5 class="heading-small text-muted mb-1">Cuerpo del reporte</h5>
            </div>

            <div class="card-body">
                @section('plugins.Summernote', true)
                @include('administracion.reportes.partials.nuevo-reporte')
            </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-sidebar btn-success">
                    <i class="fas fa-share-square"></i>&nbsp;<span class="hide">Guardar</span>
                </button>
            </div>
        </div>
    </form>
@endsection

@section('js')
    @include('partials.alerts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.js"></script>

    <script type="text/javascript">
        // *** V A R I A B L E S   G L O B A L E S ***
        var maxCamposEncabezado = {{ $max_encabezados }};
        var maxCamposCuerpo = {{ $max_texto_cuerpo }};
        var maxListados = {{ $max_listados }};
        var x = 1; // cont campos encabezado
        var y = 1; // cont campos cuerpo
        var z = 1; // cont listados


        // *** F U N C I O N   L L E N A   S E L E C T   L I S T A D O
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

        function inicializarSummernote(){
            $('.campo-encabezado').summernote({
                focus: false,
                disableResizeEditor: true,
            });
        }

        // *** L O G I C A   A G R E G A   C A M P O   E N C A B E Z A D O ***
        $("#btn_crear_campo_encabezado").click(function(e) {
            if (x <= maxCamposEncabezado) {
                let fieldHTML =
                    '<div><button type="button" class="btn btn-sm btn-danger remove_button"><i class="fas fa-minus"></i></button>&nbsp;&nbsp;&nbsp;<label for="campo_encabezado">Campo adicional encabezado Nº' + x +
                    ' *</label><div style="width: 100%"><textarea name="campo_encabezado[]" class="form-control campo-adicional-encabezado"></textarea></div></div>';
                $("#wrapper-encabezado").append(fieldHTML);
                $('.campo-adicional-encabezado').summernote();
                x++;
            }
            else if(x > maxCamposEncabezado){
                Swal.fire("Límite excedido", "No puede agregar más encabezados", "error");
            }
        });
        $("#wrapper-encabezado").on("click", ".remove_button", function(e) {
            e.preventDefault();
            $(this).parent('div').remove();
            x--;
        });


        // *** L O G I C A   A G R E G A   C A M P O   T E X T O   C U E R P O ***
        $("#btn_crear_campo_reporte").click(function() {
            debugger;
            if (y <= maxCamposCuerpo) {
                let fieldHTML =
                    '<div><button type="button" class="btn btn-sm btn-danger remove_button"><i class="fas fa-minus"></i></button>&nbsp;&nbsp;&nbsp;<label for="campo_cuerpo">Campo adicional Nº' + y +
                    ' *</label><div style="width: 100%"><textarea name="campo_cuerpo[]" class="form-control campo-cuerpo"></textarea></div></div>';
                $("#wrapper-campos").append(fieldHTML);
                $('.campo-cuerpo').summernote();
                y++;
            }
        });
        $("#wrapper-campos").on("click", ".remove_button", function(e) {
            e.preventDefault();
            $(this).parent('div').remove();
            y--;
        });


        // *** L O G I C A   A G R E G A   L I S T A D O S   C U E R P O ***
        $("#btn_crear_listado").click(function() {
            if (z < maxListados) {
                let fieldHTML =
                    '<div class="form-group"><button type="button" class="btn btn-sm btn-danger remove_button"><i class="fas fa-minus"></i></button>&nbsp;&nbsp;&nbsp;<label for="listado_adicional">Listado anexado Nº' + z +
                    ' *</label><select name="listado_adicional[]" id="input-cliente" class="form-control seleccion-listado-' +
                    z +
                    ' form-control-alternative"><option data-placeholder="true"></option></select></div>';
                $("#wrapper-listados").append(fieldHTML);
                llenarSelect('.seleccion-listado-' + z);
                z++;
            }
        });
        $("#wrapper-listados").on("click", ".remove_button", function(e) {
            e.preventDefault();
            $(this).parent('div').remove(); //Remove field html
            z--; //Decrement field counter
        });

        $(document).ready(function() {
            // activación del slimselect de reportes
            var reporte = new SlimSelect({
                select: '.selector-reporte',
                placeholder: 'Seleccione un reporte inicial...',
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
