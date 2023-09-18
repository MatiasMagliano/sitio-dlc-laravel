@extends('adminlte::page')

@section('title', 'Editar cliente')

@section('css')
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
            <h1>Editar cliente</h1>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <a href="{{ route('administracion.clientes.index') }}" role="button" class="btn btn-md btn-secondary">Volver</a>
        </div>
    </div>
@endsection

@section('content')
    @section('plugins.inputmask', true)
    <form action="{{ route('administracion.clientes.update', ['cliente' => $cliente]) }}" method="post"
        class="needs-validation" autocomplete="off" novalidate>
        @method('PATCH')
        @csrf

        <div class="card">
            <div class="card-header">
                <div class="row d-flex">
                    <div class="col-10 texto-header">
                        <h5>Editar cliente</h5>
                        <p>Ingrese los nuevos valores correspondientes a un cliente. Si desea agregar un nuevo punto de
                            entrega, vaya a la sección <a href="{{ route('administracion.dde.create') }}">Cliente/Agregar
                                puntos de entrega</a>.
                        </p>
                    </div>
                    <div class="col-2 text-right">
                        <button type="submit" class="btn btn-sidebar btn-success"><i
                                class="fas fa-share-square"></i>&nbsp;<span class="hide">Guardar</span></button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @include('administracion.clientes.partials.form-edit')
            </div>
        </div>
    </form>
@endsection

@section('js')
    @include('partials.alerts')
    <script>
        $(document).ready(function() {
            $('#input-telefono').inputmask({"mask": "09{3}-9{7}"});   
            
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
                if ($('#descuento_1 input').val() < 0){
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
                if ($('#descuento_2 input').val() < 0){
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
                if ($('#descuento_3 input').val() < 0){
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
                if ($('#descuento_4 input').val() < 0){
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
                }
                if ($('#descuento_5 input').val() < 0){
                    valadado = false;
                    $('#descuento_5 input').addClass('is-invalid'); 
                    $('#descuento_5').append('<p>Descuento incorrecto</p>'); 
                    $('#descuento_5 p').addClass('invalid-feedback'); 
                }*/

                if (validado){
                    this.submit();
                }
            });
        });

    </script>
@endsection

@section('footer')
<strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
<div class="float-right d-none d-sm-inline-block">
    <b>Versión de software 2.8</b> (PHP: v{{phpversion()}} | LARAVEL: v.{{App::VERSION()}})
</div>
@endsection
