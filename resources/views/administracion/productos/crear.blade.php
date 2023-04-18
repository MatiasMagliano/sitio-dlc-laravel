@extends('adminlte::page')

@section('title', 'Administrar Productos')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.css" />
    <style>
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
            <h1>Crear producto y sus características</h1>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <a href="{{ route('administracion.productos.index') }}" role="button"
                class="btn btn-md btn-secondary">Volver a productos</a>
        </div>
    </div>
@stop

@section('content')
    <form action="{{ route('administracion.productos.store') }}" method="post" class="needs-validation" autocomplete="off" novalidate>
        @csrf

        @include('administracion.productos.partials.form-crear')
    </form>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#sin_lote").change(function() {
                $('#cuerpo_lote').toggleClass('overlay');
            });
        });
        new SlimSelect({
            select: '.selecion-proveedor',
            placeholder: 'Seleccione un proveedor de la lista',
        })

        new SlimSelect({
            select: '.selecion-presentacion',
            placeholder: 'Seleccione una presentación de la lista',
        })
    </script>
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión de software 2.8</b> (PHP: v{{phpversion()}} | LARAVEL: v.{{App::VERSION()}})
    </div>
@endsection
