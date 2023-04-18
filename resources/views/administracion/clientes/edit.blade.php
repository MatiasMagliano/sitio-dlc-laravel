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
        });
    </script>
@endsection

@section('footer')
<strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
<div class="float-right d-none d-sm-inline-block">
    <b>Versión de software 2.8</b> (PHP: v{{phpversion()}} | LARAVEL: v.{{App::VERSION()}})
</div>
@endsection
