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
            <h1>Crear reporte</h1>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <a href="{{ route('administracion.reportes.index') }}" role="button" class="btn btn-md btn-secondary">Volver</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')
    <form action="" method="post" class="needs-validation" autocomplete="off" novalidate>
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
                {{-- <div class="form-group">
                    <label for="input-reporte">Tipo de reporte *<small class="small gray">(Formato de
                            salida)</small></label>
                    <select name="reporte_o_listado" id="input-reporte"
                        class="form-control @error('reporte_id') is-invalid @enderror"
                        aria-label="Selección del tipo de reporte">
                        <option value="" selected disabled>Seleccione un tipo de reporte...</option>
                        <option value="reporte">Reporte</option>
                        <option value="listado">Listado</option>
                    </select>
                    @error('reporte_o_listado')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div> --}}


                {{-- DIV QUE REVELA EL FORMULARIO DE REPORTES --}}
                <div id="div_reportes" class="reporte">
                    <hr>
                    @include('administracion.reportes.partials.nuevo-reporte')
                </div>

                {{-- DIV QUE REVELA EL FORMULARIO DE LISTADOS --}}
                <div id="div_listados" class="box listado">
                    <hr>Este es el div de LISTADOS
                </div>
            </div>

            {{-- BOTON GUARDAR --}}
            <div class="text-right p-3">
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
        // botón editar del campo ENCABEZADO, tomado desde su wrapper para hacerlo generico
        $('.div-encabezado').on('click', '#edit', (function(parametro) {
            // console.log();
            $('#campo-encabezado').summernote({
                focus: true,
                disableResizeEditor: true,
            });
        }));

        // botón buardar del campo ENCABEZADO
        $('.div-encabezado').on('click', '#save', (function(parametro) {
            var markup = $('#campo-encabezado').summernote('code');
            $('#campo-encabezado').summernote('destroy');
        }));

        $(document).ready(function() {
            $("#input-reporte").change(function() {
                $(this).find("option:selected").each(function() {
                    var optionValue = $(this).attr("value");
                    if (optionValue) {
                        $(".box").not("." + optionValue).hide();
                        $("." + optionValue).show();
                    } else {
                        $(".box").hide();
                    }
                });
            }).change();

            // inicializador del slimselect de reportes
            provincia = new SlimSelect({
                select: '.selector-reporte',
                placeholder: 'Seleccione un reporte inicial...',
            });

            // lógica que agrega campos de reporte
            var maxField = 5; //cantidad maxima permitida de campos
            var addButton = $('#btn_crear_campo_reporte'); //selector botón agregar campos
            var removeButton = $('#btn_borrar_campo_reporte') //selector botón borrar campos
            var wrapper = $('#wrapper-campos'); //wrapper que contiene los summernotes
            var x = 1; //contador inicial de campos
            var fieldHTML =
                '<label for="campo-reporte">Campo adicional '+x+' *</label><div style="width: 100%"><textarea name="campo-reporte-'+x+'" id="campo-cuerpo" class="form-control"></textarea></div>';

            //Once add button is clicked
            $(addButton).click(function() {
                //Check maximum number of input fields
                if (x < maxField) {
                    $(wrapper).append(fieldHTML); //Add field html
                    let textarea = '#campo-cuerpo-'+x;
                    $(textarea).summernote();
                    x++; //Increment field counter
                }
            });

            //Once remove button is clicked
            $(removeButton).on('click', '.remove_button', function(e) {
                e.preventDefault();
                $(this).parent('div').remove(); //Remove field html
                x--; //Decrement field counter
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
