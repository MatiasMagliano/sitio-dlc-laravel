@extends('adminlte::page')

@section('title', 'Ver lotes asignados')

@section('css')
    <style>
        .texto-header {
            padding: 0 20px;
            height: 30%;
            overflow-y: auto;
            /*font-size: 14px;*/
            font-weight: 500;
            color: #000000;
        }

        .texto-header::-webkit-scrollbar {
            width: 10px;
            background-color: #282828;
        }

        .texto-header::-webkit-scrollbar-thumb {
            background-color: #fff;
        }

        .pre-scrollable{
            max-height: 230px;
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
    <div class="card">
        <div class="card-header">
            <h4 class="heading-small text-muted mb-1">Producto {{ $producto->droga }},
                {{ $producto->presentaciones($producto->id)->pluck('forma')->get('0') }}
                {{ $producto->presentaciones($producto->id)->pluck('presentacion')->get('0') }}</h4>
        </div>
        <div class="card-body texto-header">
            <table class="table" width="100%">
                <thead>
                    <th>Identificador</th>
                    <th>Ubicación</th>
                    <th>Cantidad</th>
                    <th>Fecha de compra</th>
                    <th>Fecha de elaboración</th>
                    <th>Vencimiento</th>
                    <th></th>
                </thead>
                <tbody>
                    @foreach ($lotes as $lote)
                        <tr>
                            <td>{{$lote->identificador}}</td>
                            <td>N/A</td>
                            <td>{{$lote->cantidad}}</td>
                            <td>{{$lote->fecha_compra->format('d/m/Y')}}</td>
                            <td>{{$lote->fecha_elaboracion->format('d/m/Y')}}</td>
                            <td>{{$lote->fecha_vencimiento->format('d/m/Y')}}</td>
                            <td>-</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('js')
    @include('partials.alerts')
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión de software 2.8</b> (PHP: v{{phpversion()}} | LARAVEL: v.{{App::VERSION()}})
    </div>
@endsection
