@extends('adminlte::page')

@section('title', 'Administrar Productos')

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
            <h1>Administración de cotizaciones</h1>
        </div>
        <div class="col-md-4 d-flex justify-content-xl-end">
            <a href="{{ url()->previous() }}" role="button"
                class="btn btn-md btn-secondary">Volver</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row d-flex">
                <div class="col-8">
                    <h5 class="heading-small text-muted mb-1">Básicos de la cotización</h5>
                </div>
                @if (!$cotizacion->finalizada)
                    <div class="col-4 text-right">
                        @if ($cotizacion->productos->count() == 0)
                            <form action="{{route('administracion.cotizaciones.destroy', $cotizacion)}}" method="post" class="d-inline">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="far fa-trash-alt"></i>&nbsp;<span class="hide">Borrar cotización</span>
                                </button>
                            </form>
                        @else
                        <button type="button" class="btn btn-sm btn-secondary"
                        onclick="confirmarCotizacion()">
                        <i class="far fa-trash-alt"></i>&nbsp;<span class="hide">Cancelar cotización</span>
                    </button>
                    &nbsp;
                    <button type="button" class="btn btn-sm btn-success"
                        onclick="confirmarCotizacion()">
                        <i class="fas fa-share-square"></i>&nbsp;<span class="hide">Finalizar cotización</span>
                    </button>
                        @endif
                    </div>
                @endif
            </div>
        </div>
        <div class="card-body">
            <table class="table" width="100%">
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
                        <td class="align-middle">
                            {{$cotizacion->created_at->format('d/m/Y')}}<br>{{$cotizacion->identificador}}
                        </td>
                        <td>
                            {{$cotizacion->cliente->razon_social}}<br>
                            {{$cotizacion->cliente->tipo_afip}}: {{$cotizacion->cliente->afip}}
                        </td>
                        <td class="align-middle">{{$cotizacion->user->name}}</td>
                        <td class="align-middle">{{$cotizacion->productos->count()}}</td>
                        <td class="align-middle">{{$cotizacion->productos->sum('cantidad')}}</td>
                        <td class="align-middle">$ {{number_format($cotizacion->productos->sum('total'), 2, ',', '.')}}</td>
                        <td class="align-middle">
                            @switch($cotizacion->estado_id)
                                @case(1)
                                    <span class="text-fuchsia">{{$cotizacion->estado->estado}}</span>
                                    @break
                                @case(2)
                                    <span class="text-success">{{$cotizacion->estado->estado}}</span>
                                    @break
                                @case(3)
                                    <span class="text-secondary">{{$cotizacion->estado->estado}}</span>
                                    @break
                                @case(4)
                                    <span class="text-success">{{$cotizacion->estado->estado}}</span>
                                    @break
                                @case(5)
                                    <span class="text-danger">{{$cotizacion->estado->estado}}</span>
                                    @break
                                @default
                            @endswitch
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    @if ($cotizacion->confirmada)
        <div class="card bg-gradient-success">
            <div class="card-header">
                <h5 class="card-title">La cotización fue aprobada el {{$cotizacion->confirmada->format('d/m/Y')}}</h5>
            </div>
            <div class="card-body">
                <p>Lugar de entrega: {{$cotizacion->cliente->ptoEntrega($cotizacion->dde_id)->lugar_entrega}}</p>
                <p>Domicilio: {{$cotizacion->cliente->ptoEntrega($cotizacion->dde_id)->domicilio}}</p>
                <p>Condiciones: {{$cotizacion->cliente->ptoEntrega($cotizacion->dde_id)->condiciones}}</p>
                <p>Observaciones: {{$cotizacion->cliente->ptoEntrega($cotizacion->dde_id)->observaciones}}</p>
            </div>
            <div class="card-footer">
                @if ($cotizacion->archivos()->exists())
                    <p>La aprobación recibió una Orden de Provisión, puede descargarla en este
                        <a href="{{ route('administracion.cotizaciones.descargapdf', ['cotizacion' => $cotizacion, 'doc' => 'provision']) }}"target="_blank">
                            link
                        </a>
                    </p>
                @endif
            </div>
        </div>
    @endif

    @if ($cotizacion->rechazada)
        <div class="card bg-gradient-danger">
            <div class="card-header">
                <h5 class="card-title">Motivo de rechazo</h5>
            </div>
            <div class="card-body">
                <p>{{$cotizacion->motivo_rechazo}}</p>
            </div>
            <div class="card-footer">
                @if ($cotizacion->archivos()->exists())
                    <p>El rechazo contiene un archivo comparativo, puede descargarlo en este
                        <a href="{{ route('administracion.cotizaciones.descargapdf', ['cotizacion' => $cotizacion, 'doc' => 'rechazo']) }}"target="_blank">
                            link
                        </a>
                    </p>
                @endif
            </div>
        </div>
    @endif

    @section('plugins.Datatables', true)
    @section('plugins.DatatablesPlugins', true)
    <div class="card">
        <div class="card-header">
            <div class="row d-flex">
                <div class="col-8">
                    <h5 class="heading-small text-muted mb-1">Productos</h5>
                </div>
                @if (!$cotizacion->finalizada)
                    <div class="col-4 text-right">
                        <a href="{{ route('administracion.cotizaciones.agregar.producto', ['cotizacion' => $cotizacion->id]) }}"
                            class="btn btn-sm btn-primary">
                            <i class="fas fa-plus fa-sm"></i>&nbsp;<span class="hide">agregar productos</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>
        <div class="card-body">
            <table id="tablaProductos" class="table table-responsive-md table-bordered table-condensed" width="100%">
                <thead>
                    <th>Línea</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio unitario</th>
                    <th>Total</th>
                    <th></th>
                </thead>
                <tbody>
                    @php $i = 0; /*variable contadora del Nº Orden*/@endphp
                    @foreach ($cotizacion->productos as $cotizado)
                    <tr>
                        <td class="align-middle text-center">{{++$i}}</td>
                        <td>{{--Producto: producto+presentacion--}}
                            {{$cotizado->producto->droga}}, {{$cotizado->presentacion->forma}} {{$cotizado->presentacion->presentacion}}
                        </td>
                        <td class="align-middle text-center">
                            {{$cotizado->cantidad}}
                        </td>
                        <td class="align-middle text-center">
                            $ {{number_format($cotizado->precio, 2, ',', '.')}}
                        </td>
                        <td class="text-right">
                            $ {{number_format($cotizado->total, 2, ',', '.')}}
                        </td>
                        <td class="align-middle text-center">
                            @if (!$cotizacion->finalizada)
                                <a href="{{ route('administracion.cotizaciones.editar.producto', ['cotizacion' => $cotizacion, 'productoCotizado' => $cotizado]) }}"
                                    class="btn btn-link" data-toggle="tooltip" data-placement="middle"
                                    title="Editar producto cotizado">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <button type="button" class="btn btn-link text-danger"
                                    data-id="{{ $cotizado->id }}" data-action="{{ route('administracion.cotizaciones.borrar.producto', ['cotizacion' => $cotizacion, 'productoCotizado' => $cotizado]) }}"
                                    onclick="confirmarBorrado({{$cotizacion->id}}, {{$cotizado->id}})">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            @else
                                <span>-</span>
                            @endif
                        </td>
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
            $('#tablaProductos').DataTable( {
                "responsive": true,
                "dom": 'Pfrtip',
                "scrollY": "35vh",
                "scrollCollapse": true,
                "paging": false,
                "order": [0, 'asc'],
                "bInfo": false,
                "searching": false,
                "columnDefs": [{
                        targets: 4,
                        width: 70,
                    },
                ],
            });
        });
        function confirmarCotizacion(){
            Swal.fire({
                icon: 'warning',
                title: 'Finalizar cotización',
                text: 'A continuación se le presentará un archivo PDF para descargar y presentar al cliente',
                confirmButtonText: 'Finalizar',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.replace('{{ route('administracion.cotizaciones.finalizar', $cotizacion) }}');
                }
            });
        }

        // BORRAR PRODUCTO DE COTIZACIÓN
        function confirmarBorrado(id_cotizacion, id_cotizado){
            Swal.fire({
                icon: 'warning',
                title: 'Borrar producto',
                text: 'Esto quitará el producto de la lista.',
                confirmButtonText: 'Borrar',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if(result.isConfirmed) {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        type: 'DELETE',
                        url: "{{url('/administracion/cotizaciones')}}/" + id_cotizacion + '/producto/' + id_cotizado,
                        data: {_token: CSRF_TOKEN},
                        dataType: 'JSON',
                        success: function (results) {
                            // la redirección se da en el AJAX
                            window.location.replace('{{ route('administracion.cotizaciones.show', ['cotizacione' => $cotizacion]) }}');
                        },
                        error: function (results) {
                            Swal.fire(Swal.fire('Error', results.message, 'error'));
                        }
                    });
                }
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
