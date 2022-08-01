@extends('adminlte::page')

@section('title', 'Ver lotes asignados')

@section('css')
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
        <div class="col-xl-8">
            <h1>Lotes asignados a Orden de trabajo <span
                    style="font-weight: 700">{{ $ordentrabajo->cotizacion->identificador }}</span></h1>
        </div>
        <div class="col-md-4 d-flex justify-content-xl-end">
            <a href="{{ url()->previous() }}" role="button" class="btn btn-md btn-secondary">Volver</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')

    <hr>
    <h1>Lotes de producto</h1>
    <table class="tabe table-bordered" width="100%">
        <thead>
            <th>Identificador</th>
            <th>Cantidad</th>
            <th>Vencimiento</th>
        </thead>
        <tbody>

        </tbody>
    </table>
@endsection

@section('js')
    @include('partials.alerts')
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión</b> 2.0 (LARAVEL V.8)
    </div>
@endsection
