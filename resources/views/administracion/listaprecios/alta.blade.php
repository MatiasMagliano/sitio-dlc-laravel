@extends('adminlte::page')

@section('title', 'Administrar Productos')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.css" />
    <style>
        .texto-header{padding:0 20px;height:60px;overflow-y:auto;/*font-size: 14px;*/font-weight:500;color:#000000;}
        .texto-header::-webkit-scrollbar{width:5px;background-color:#282828;}
        .texto-header::-webkit-scrollbar-thumb{background-color:#3bd136;}
        @media(max-width:600px){.hide{display:none;}}
    </style>
@endsection

@section('content_header')
    <div class="row">
        <div class="col-xl-8">
            <h1>Listado de Precios/ Alta de Proveedor</h1>
        </div>
        <div class="col-md-4 d-flex justify-content-xl-end">
            <a href="{{ route('administracion.listaprecios.index') }}" role="button" class="btn btn-md btn-secondary" title="Volver a Listados">Volver al Listado</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')
    @section('plugins.inputmask', true)
    @section('plugins.Datatables', true)
    @section('plugins.DatatablesPlugins', true)
    <div class="wrapper">
        <div class="card">
            <div class="card-body">
                @include('administracion.listaprecios.partials.alta-proveedor_form')
            </div>
        </div>
    </div>
@endsection

@section('js')
    @include('partials.alerts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.js"></script>
    <script>
        //var emailRegex = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/;
        //var urlRegex = /^(https?:\/\/)?([\w\-]+\.)+([a-z]{2,})(\/\S*)?$/i;
        $(document).ready(function() {
            hideAlertValidation();
            
            $('#input-afip').inputmask("9{2}-9{8}-9{1}");
            $('#input-cuit').inputmask("9{2}-9{8}-9{1}");
            $('#input-telefono').inputmask("09{3}-9{7}");

            //AGREGAR PROVEEDOR - SUBMIT DEL FORMULARIO
            $('#formAgregProveedor').submit(function(event) {

                // Detiene el envío predeterminado del formulario
                event.preventDefault();

                hideAlertValidationSubmit();
                // Realiza tus validaciones personalizadas aquí
                var validado = true;

                /*//Valor de campo Razon social
                if( $('#input-razon_social').val() == "" || $('#input-razon_social').val() == null || $('#input-razon_social').val() == undefined) {
                    validado = false;
                    $('#input-razon_social').addClass('is-invalid'); 
                    $('#invalid-feedback-razon_social').append('<p>El campo razon social es incorrecto</p>'); 
                }
                $pcuit = $('#input-cuit').val().replace("-","");
                $pcuit = $pcuit.replace("-","");
                //Valor de campo cuit
                if($('#input-cuit').val() == "" || $('#input-cuit').val() == null || $('#input-cuit').val() == undefined) {
                    validado = false;
                    $('#input-cuit').addClass('is-invalid'); 
                    $('#invalid-feedback-cuit').append('<p>El campo cuit es incorrecto</p>'); 
                }

                //Valor de campo Email
                if($('#input-email').val() == "" || !emailRegex.test($('#input-email').val())) {
                    validado = false;
                    $('#input-email').addClass('is-invalid'); 
                    $('#invalid-feedback-email').append('<p>El campo email es incorrecto</p>'); 
                }

                //Valor de campo web
                if($('#input-web').val() == "" || !urlRegex.test($('#input-web').val())) {
                    validado = false;
                    $('#input-web').addClass('is-invalid'); 
                    $('#invalid-feedback-web').append('<p>El campo web es incorrecto</p>'); 
                }

                //Valor de campo domicilio
                if($('#input-domicilio').val() == "" || $('#input-domicilio').val() == null || $('#input-domicilio').val() == undefined) {
                    validado = false;
                    $('#input-domicilio').addClass('is-invalid'); 
                    $('#invalid-feedback-domicilio').append('<p>El campo domicilio es incorrecto</p>'); 
                }

                //Valor de campo Provincia
                if($('#input-provincia').val() == "" || $('#input-provincia').val() == null || $('#input-provincia').val() == undefined) {
                    validado = false;
                    $('#input-provincia').addClass('is-invalid'); 
                    $('#invalid-feedback-provincia').append('<p>El campo provincia es incorrecto</p>'); 
                }

                //Valor de campo Localidad
                if($('#input-localidad').val() == "" || $('#input-localidad').val() == null || $('#input-localidad').val() == undefined) {
                    validado = false;
                    $('#input-localidad').addClass('is-invalid'); 
                    $('#invalid-feedback-localidad').append('<p>El campo localidad es incorrecto</p>'); 
                }*/

                if (validado){
                    hideAlertValidation();
                    this.submit();
                }
            });


        });

        function hideAlertValidation(){
            //$('#input-razon_social').removeClass('is-invalid');
            $('#invalid-feedback-razon_social p').remove();
            //$('#input-cuit').removeClass('is-invalid');
            $('#invalid-feedback-cuit p').remove();
            //$('#input-email').removeClass('is-invalid');
            $('#invalid-feedback-email p').remove();
            //$('#input-web').removeClass('is-invalid');
            $('#invalid-feedback-web p').remove();
            //$('#input-domicilio').removeClass('is-invalid');
            $('#invalid-feedback-domicilio p').remove();
            //$('#input-provincia').removeClass('is-invalid');
            $('#invalid-feedback-provincia p').remove();
            //$('#input-localidad').removeClass('is-invalid');
            $('#invalid-feedback-localidad p').remove();

        }

        function hideAlertValidationSubmit(){
            $('#input-razon_social').removeClass('is-invalid');
            $('#invalid-feedback-razon_social p').remove();
            $('#input-cuit').removeClass('is-invalid');
            $('#invalid-feedback-cuit p').remove();
            $('#input-email').removeClass('is-invalid');
            $('#invalid-feedback-email p').remove();
            $('#input-web').removeClass('is-invalid');
            $('#invalid-feedback-web p').remove();
            $('#input-domicilio').removeClass('is-invalid');
            $('#invalid-feedback-domicilio p').remove();
            $('#input-provincia').removeClass('is-invalid');
            $('#invalid-feedback-provincia p').remove();
            $('#input-localidad').removeClass('is-invalid');
            $('#invalid-feedback-localidad p').remove();

        }

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
    </script>
    {{-- @include('administracion.listaprecios.js.alta-listaprecios_JS') --}}
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión de software 2.8</b> (PHP: v{{phpversion()}} | LARAVEL: v.{{App::VERSION()}})
    </div>
@endsection
