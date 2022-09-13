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
            <a href="{{ url()->previous() }}" role="button" class="btn btn-md btn-secondary">Volver</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')
@section('plugins.inputmask', true)
<form action="{{ route('administracion.clientes.store') }}" method="post" class="needs-validation" autocomplete="off"
    novalidate>
    @csrf

    <div class="card">
        <div class="card-header">
            <div class="row d-flex">
                <div class="col-10 texto-header">
                    <h5>Nuevo cliente</h5>
                    <p>A cada nuevo cliente le corresponde un nuevo punto de entrega. Ingrese los valores
                        correspondientes a un nuevo cliente. Si desea agregar un nuevo
                        punto de entrega, vaya a la sección <a
                            href="{{ route('administracion.clientes.agregarPuntoEntrega') }}">Cliente/Agregar puntos de
                            entrega</a>.</p>
                </div>
                <div class="col-2 text-right">
                    <button type="submit" class="btn btn-sidebar btn-success"><i
                            class="fas fa-share-square"></i>&nbsp;<span class="hide">Guardar</span></button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @include('administracion.clientes.partials.formulario')
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
    });

    var localidad = new SlimSelect({
        select: '.selector-localidad',
        placeholder: 'Seleccione una localidad',
    });

    var provincia = new SlimSelect({
        select: '.selector-provincia',
        placeholder: 'Seleccione una provincia',
        onChange: (info) => {
            getLocalidades(info);
        }
    });

    function getLocalidades(provinciaSeleccionada) {
        //debugger;
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
    <b>Versión</b> 2.0 (LARAVEL V.8)
</div>
@endsection
