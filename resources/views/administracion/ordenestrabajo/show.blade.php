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

    <div class="card">
        <div class="card-header">
            <h5 class="heading-small text-muted mb-1">Datos de la orden de trabajo: {{$ordentrabajo->cotizacion->identificador}}</h5>
        </div>
        <div class="card-body">
            <table class="table" width="100%">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Lugar de entrega</th>
                        <th></th>
                        <th>ESTADO</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="vertical-align: middle;">
                            {{ $ordentrabajo->cotizacion->created_at->format('d/m/Y') }}
                        </td>
                        <td style="vertical-align: middle;">
                            {{ $ordentrabajo->cotizacion->cliente->razon_social }}
                        </td>
                        <td style="vertical-align: middle;">
                            <u><strong>{{ $ordentrabajo->cotizacion->dde->lugar_entrega }}:</strong></u>
                            <br>
                            <div class="ml-3">
                                <strong>Dirección:</strong>
                                {{ $ordentrabajo->cotizacion->dde->domicilio }}
                                <br>
                                <strong>Condiciones: </strong>
                                {{ $ordentrabajo->cotizacion->dde->condiciones }}
                                <br>
                                <strong>Observaciones: </strong>
                                {{ $ordentrabajo->cotizacion->dde->observaciones }}
                            </div>
                        </td>
                        <td style="vertical-align: middle;">
                            <strong>Cotizazión creada por: </strong>{{ $ordentrabajo->cotizacion->user->name }}
                            <strong>, aprobada el:
                            </strong>{{ $ordentrabajo->cotizacion->confirmada->format('d/m/Y') }} <br>
                            <strong>En producción desde el:
                            </strong>{{ $ordentrabajo->en_produccion->format('d/m/Y') }} <br>
                            <strong>Plazo de entrega: </strong>{{ $ordentrabajo->plazo_entrega }}
                        </td>
                        <td style="vertical-align: middle;">
                            @switch($ordentrabajo->estado_id)
                                @case(6)
                                    {{-- ESTADOS DINAMICOS --}}
                                <td style="vertical-align: middle;">
                                    <span class="text-success">{{ $ordentrabajo->estado->estado }}
                                </td>
                            @case(7)
                                {{-- ESTADOS DINAMICOS --}}
                                <td style="vertical-align: middle;">
                                    <span class="text-success">{{ $ordentrabajo->estado->estado }}
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
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    @section('plugins.Datatables', true)
    @section('plugins.DatatablesPlugins', true)
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
                                    Lotes sin asignar
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
            "order": [3, 'desc'],
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
    <b>Versión de software 2.8</b> (PHP: v{{phpversion()}} | LARAVEL: v.{{App::VERSION()}})
</div>
@endsection
