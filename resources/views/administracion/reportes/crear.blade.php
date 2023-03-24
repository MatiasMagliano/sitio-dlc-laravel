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
            <h1>Crear reporte</h1>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <a href="{{ route('administracion.reportes.index') }}" role="button" class="btn btn-md btn-secondary">Volver</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')
    <form action="" method="post">
        @include('administracion.reportes.partials.nuevo-reporte')
    </form>
@endsection

@section('js')
    @include('partials.alerts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.js"></script>

    <script type="text/javascript">
        function getReporte(id) {
            let datos = {
                reporte_id: id.value,
            };

            $.ajax({
                url: "{{ route('administracion.clientes.ajax.obtenerLocalidades') }}",
                type: "GET",
                data: datos,
            }).done(function(resultado) {
                localidad.setData(resultado);
            });
        }

        $(document).ready(function() {
            const reporte = new SlimSelect({
                select: '.selector-reporte',
                placeholder: 'Seleccione un tipo de reporte',
                onChange: (info) => {
                    getReporte(id);
                }
            });

            const usuario = new SlimSelect({
                select: '.selector-usuario',
                placeholder: 'Seleccione un usuario',
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
