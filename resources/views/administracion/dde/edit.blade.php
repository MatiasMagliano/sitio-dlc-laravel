@extends('adminlte::page')

@section('title', 'Editar dirección de entrega')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.css" />
@endsection

@section('content_header')
    <div class="row">
        <div class="col-xl-8">
            <h1>Editar dirección de entrega</h1>
        </div>
        <div class="col-xl-4 d-flex justify-content-xl-end">
            <a href="{{ route('administracion.dde.index') }}" role="button" class="btn btn-md btn-secondary">Volver</a>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ route('administracion.dde.update', ['dde' => $dde]) }}" method="post"
        class="needs-validation" autocomplete="off" novalidate>
        @method('PATCH')
        @csrf

        <div class="card">
            <div class="card-header">
                <div class="row d-flex">
                    <div class="col-10 texto-header">
                        <h5>Editar direcciones de entrega para: <strong>{{$dde->cliente->razon_social}}</strong></h5>
                        <p>Ingrese los nuevos valores correspondientes al punto de entrega. Si desea agregar un nuevo punto de
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
                @include('administracion.dde.partials.form-edit')
            </div>
        </div>
    </form>
@endsection

@section('js')
    @include('partials.alerts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.js"></script>
    <script>
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
