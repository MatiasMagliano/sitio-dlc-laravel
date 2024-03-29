@extends('adminlte::page')

@section('title', 'Administrar Clientes')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.css" />
    <style>
        .texto-header {
            padding: 0 20px;
            height: 60px;
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
            <h1>Administración de clientes</h1>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <a href="{{ route('administracion.clientes.index') }}" role="button" class="btn btn-md btn-secondary">Volver</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')
    @section('plugins.inputmask', true)
    <form action="{{ route('administracion.clientes.store') }}" method="post" class="needs-validation" autocomplete="off" novalidate>
        @csrf

        <div class="card">
            <div class="card-header">
                <div class="row d-flex">
                    <div class="col-10 texto-header">
                        <h5>Nuevo cliente</h5>
                        <p>A cada nuevo cliente le corresponde un nuevo punto de entrega. Ingrese los valores
                            correspondientes a un nuevo cliente. Si desea agregar un nuevo
                            punto de entrega, vaya a la sección <a
                                href="{{ route('administracion.dde.create') }}">Cliente/Agregar puntos de
                                entrega</a>.</p>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @include('administracion.clientes.partials.formulario')
            </div>

            <div class="card-footer">
                <div class="text-right pb-5 pr-3">
                    <button type="submit" class="btn btn-sidebar btn-success"><i
                            class="fas fa-share-square"></i>&nbsp;<span class="hide">Guardar</span></button>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('js')
    @include('partials.alerts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#input-afip').inputmask("9{2}-9{8}-9{1}");
            $('#input-telefono').inputmask({
                "mask": "09{3}-9{7}"
            });

            $("#sin_esquema").change(function() {
                $('#cuerpo_esquema').toggleClass('overlay');
            });

            $('.needs-validation').submit(function(event) {
                // Detiene el envío predeterminado del formulario
                event.preventDefault();

                // Realiza tus validaciones personalizadas aquí
                var validado = true;
                
                //Extensión de campo Telefono
                /*$('#invalid-feedback-telefono p').remove();
                $('#input-telefono').removeClass('is-invalid'); 
                var inputVal = $('#input-telefono').val();
                var telefonoNumerico = inputVal.replace(/\D/g, '');
                if (telefonoNumerico.length != 11) {
                    validado = false;
                    $('#input-telefono').addClass('is-invalid'); 
                    $('#invalid-feedback-telefono').append('<p>El campo teléfono es incorrecto</p>'); 
                }*/

                //Valor de campos Descuentos
                //Descuento 1
                /*$('#descuento_1 p').remove();
                $('#descuento_1 input').removeClass('is-invalid'); 
                if ( $.isNumeric($('#descuento_1 input').val()) == false ){
                    validado = false;
                    $('#descuento_1 input').addClass('is-invalid'); 
                    $('#descuento_1').append('<p>Descuento incorrecto</p>'); 
                    $('#descuento_1 p').addClass('invalid-feedback'); 
                }
                //Descuento 2
                $('#descuento_2 p').remove();
                $('#descuento_2 input').removeClass('is-invalid'); 
                if ( $.isNumeric($('#descuento_2 input').val()) == false ){
                    validado = false;
                    $('#descuento_2 input').addClass('is-invalid'); 
                    $('#descuento_2').append('<p>Descuento incorrecto</p>'); 
                    $('#descuento_2 p').addClass('invalid-feedback'); 
                } 
                //Descuento 3
                $('#descuento_3 p').remove();
                $('#descuento_3 input').removeClass('is-invalid'); 
                if ( $.isNumeric($('#descuento_3 input').val()) == false ){
                    validado = false;
                    $('#descuento_3 input').addClass('is-invalid'); 
                    $('#descuento_3').append('<p>Descuento incorrecto</p>'); 
                    $('#descuento_3 p').addClass('invalid-feedback'); 
                }
                //Descuento 4
                $('#descuento_4 p').remove();
                $('#descuento_4 input').removeClass('is-invalid'); 
                if ( $.isNumeric($('#descuento_4 input').val()) == false ){
                    validado = false;
                    $('#descuento_4 input').addClass('is-invalid'); 
                    $('#descuento_4').append('<p>Descuento incorrecto</p>'); 
                    $('#descuento_4 p').addClass('invalid-feedback'); 
                }
                //Descuento 5
                $('#descuento_5 p').remove();
                $('#descuento_5 input').removeClass('is-invalid'); 
                if ( $.isNumeric($('#descuento_5 input').val()) == false ){
                    validado = false;
                    $('#descuento_5 input').addClass('is-invalid'); 
                    $('#descuento_5').append('<p>Descuento incorrecto</p>'); 
                    $('#descuento_5 p').addClass('invalid-feedback'); 
                }*/

                if (validado){
                    this.submit();
                }
            });

        });

        var provincia = new SlimSelect({
            select: '.selector-provincia',
            placeholder: 'Seleccione una provincia',
            onChange: (info) => {
                getLocalidades(info);
            }
        });

        var localidad = new SlimSelect({
            select: '.selector-localidad',
            placeholder: 'Seleccione una localidad',
        });

        function getLocalidades(provinciaSeleccionada) {
            let datos = {
                provincia_id: provinciaSeleccionada.value,
            };

            $.ajax({
                url: "{{ route('administracion.clientes.ajax.obtenerLocalidades') }}",
                type: "GET",
                data: datos,
            }).done(function(resultado) {
                localidad.setData(resultado);
            });
        }
    </script>
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión de software 2.8</b> (PHP: v{{phpversion()}} | LARAVEL: v.{{App::VERSION()}})
    </div>
@endsection
