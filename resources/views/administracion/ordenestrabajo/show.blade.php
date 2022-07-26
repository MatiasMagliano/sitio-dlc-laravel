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
            <a href="{{ route('administracion.ordentrabajo.index') }}" role="button" class="btn btn-md btn-secondary">Volver a
                Órdenes de trabajo</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
<div class="card">
    <div class="card-header">
        <h5 class="heading-small text-muted mb-1">Datos básicos de Orden de trabajo</h5>
    </div>
    <div class="card-body">
        <table class="table table-bordered" width="100%">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Identificador/Usuario</th>
                    <th>Cliente</th>
                    <th>Productos cotizados</th>
                    <th>Total unidades</th>
                    <th>ESTADO</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="vertical-align: middle;">
                        {{ $ordentrabajo->created_at->format('d/m/Y') }}
                    </td>
                    <td style="vertical-align: middle;">
                        <strong>{{ $ordentrabajo->cotizacion->identificador }}</strong>
                        <br>
                        {{ $ordentrabajo->user->name }}
                    </td>
                    <td style="vertical-align: middle;">
                        {{ $ordentrabajo->cotizacion->cliente->razon_social }}<br>
                        {{ $ordentrabajo->cotizacion->cliente->tipo_afip }}:
                        {{ $ordentrabajo->cotizacion->cliente->afip }}
                    </td>
                    <td style="vertical-align: middle; text-align:center;">{{ $ordentrabajo->productos->count() }}</td>
                    <td style="vertical-align: middle; text-align:center;">
                        {{ $ordentrabajo->productos->sum('cantidad') }}</td>
                    {{-- SE RESUME TODO A UN SOLO SWITCH, a diferencia del index de cotizaciones --}}
                    @switch($ordentrabajo->estado_id)
                        @case(6)
                            {{-- ESTADOS DINAMICOS --}}
                            <td style="vertical-align: middle;">
                                <span class="text-success">{{ $ordentrabajo->estado->estado }} desde el:
                                    {{ $ordentrabajo->en_produccion->format('d/m/Y') }}</span>
                            </td>
                        @case(7)
                            {{-- ESTADOS DINAMICOS --}}
                            <td style="vertical-align: middle;">
                                <span class="text-success">{{ $ordentrabajo->estado->estado }} desde el:
                                    {{ $ordentrabajo->en_produccion->format('d/m/Y') }}</span>
                            </td>
                        @break

                        @default
                            <td>
                                <p>-</p>
                            </td>
                            <td>
                                <p>-</p>
                            </td>
                    @endswitch
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table id="tablaProductos" class="table table-responsive-md table-bordered table-condensed" width="100%">
            <thead>
                <th>Línea</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Lotes asignados</th>
                <th></th>
            </thead>
            <tbody>
                @php $i = 0; /*variable contadora del Nº Orden*/@endphp
                @foreach ($ordentrabajo->productos as $itemOT)
                    <tr>
                        <td style="text-align: center;">{{ ++$i }}</td>
                        <td>{{-- Producto: producto+presentacion --}}
                            {{ $itemOT->producto->droga }}, {{ $itemOT->presentacion->forma }}
                            {{ $itemOT->presentacion->presentacion }}
                        </td>
                        <td style="vertical-align: middle; text-align:center;">
                            {{ $itemOT->cantidad }}
                        </td>
                        @if ($itemOT->lotes == -1)
                            <td class="text-center">
                                Lote sin asignar
                            </td>
                            <td style="vertical-align: middle; text-align:center;">
                                <a href="{{ route('administracion.ordentrabajo.asignarlotes', ['ordentrabajo' => $ordentrabajo, 'producto' => $itemOT->producto->id, 'presentacion' => $itemOT->presentacion->id]) }}"
                                    class="btn btn-sm btn-danger">
                                    Asignar
                                </a>
                            </td>
                        @else
                            <td style="vertical-align: middle; text-align:center;">{{ $itemOT->lotes }}</td>
                            <td style="vertical-align: middle; text-align:center;">
                                <a href="#" class="btn btn-link">
                                    <i class="fas fa-search"></i>
                                </a>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('js')
@include('partials.alerts')
<script type="text/javascript" src="{{ asset('js/datatables-spanish.js') }}" defer></script>
<script>
    $(document).ready(function() {
        $('#tablaProductos').DataTable({
            "responsive": true,
            "dom": 'Pfrtip',
            "scrollY": "25vh",
            "scrollCollapse": true,
            "paging": false,
            "order": [0, 'asc'],
            "bInfo": false,
            "searching": false,
            "columnDefs": [{
                    targets: 3,
                    width: 130,
                },
                {
                    targets: 4,
                    width: 90,
                },
            ],
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
