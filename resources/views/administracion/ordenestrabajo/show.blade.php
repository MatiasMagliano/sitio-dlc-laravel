@extends('adminlte::page')

@section('title', 'Modificar Orden de trabajo')

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
            <h1>Asignar lotes a Orden de trabajo</h1>
        </div>
        <div class="col-md-4 d-flex justify-content-xl-end">
            <a href="{{ route('administracion.ordentrabajo.index') }}" role="button"
                class="btn btn-md btn-secondary">Volver a Órdenes de trabajo</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="heading-small text-muted mb-1">Datos básicos de la cotización</h5>
    </div>
    <div class="card-body">
        <table class="table table-responsive-md" width="100%">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Usuario</th>
                    <th>Productos cotizados</th>
                    <th>Unidades</th>
                    <th>Importe</th>
                    <th>ESTADO</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="vertical-align: middle;">
                        {{$cotizacion->created_at->format('d/m/Y')}}<br>{{$cotizacion->identificador}}
                    </td>
                    <td>
                        {{$cotizacion->cliente->razon_social}}<br>
                        {{$cotizacion->cliente->tipo_afip}}: {{$cotizacion->cliente->afip}}
                    </td>
                    <td style="vertical-align: middle;">{{$cotizacion->user->name}}</td>
                    <td style="vertical-align: middle;">{{$cotizacion->productos->count()}}</td>
                    <td style="vertical-align: middle;">{{$cotizacion->productos->sum('cantidad')}}</td>
                    <td style="vertical-align: middle;">$ {{number_format($cotizacion->productos->sum('total'), 2, ',', '.')}}</td>
                    @switch($cotizacion ->estado_id)
                        @case(6 || 7)
                            {{-- ESTADOS DINAMICOS --}}
                            <td style="vertical-align: middle;">
                                <span class="text-success">{{$cotizacion->estado->estado}}</span>
                            </td>
                            @break
                        @default
                            <td><p>-</p></td>
                            <td><p>-</p></td>
                    @endswitch
                </tr>
            </tbody>
        </table>
    </div>
@endsection

@section('js')
    @include('partials.alerts')
    <script type="text/javascript" src="{{ asset('js/datatables-spanish.js') }}" defer></script>
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión</b> 2.0 (LARAVEL V.8)
    </div>
@endsection
