@extends('adminlte::page')

@section('title', 'Administración de cotizaciones')

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
            <h1>Ver/Editar cotización</h1>
        </div>
        <div class="col-md-4 d-flex justify-content-xl-end">
            <a href="{{ route('administracion.cotizaciones.index') }}" role="button"
                class="btn btn-md btn-secondary">Volver</a>
        </div>
    </div>
@endsection

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
            <table class="table table-responsive-md" width="100%">
                <thead>
                    <tr>
                        <th class="align-middle">Fecha/<br>Identificador</th>
                        <th class="align-middle">Cliente</th>
                        <th class="align-middle">Lugar de entrega</th>
                        <th class="align-middle">Usuario</th>
                        <th class="align-middle">Productos cotizados</th>
                        <th class="align-middle">Unidades</th>
                        <th class="align-middle">Importe</th>
                        <th class="align-middle">ESTADO</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="align-middle">
                            {{$cotizacion->created_at->format('d/m/Y')}}<br><strong>{{$cotizacion->identificador}}</strong>
                        </td>
                        <td class="align-middle">
                            {{$cotizacion->cliente->razon_social}}<br>
                            <span style="text-transform: uppercase;">{{$cotizacion->cliente->tipo_afip}}</span>: {{$cotizacion->cliente->afip}}
                        </td>
                        <td class="align-middle">
                            <strong>{{$cotizacion->dde->lugar_entrega}}</strong> <br>
                            {{$cotizacion->dde->domicilio}} <br>
                            {{$cotizacion->dde->localidad->nombre}}, {{$cotizacion->dde->provincia->nombre}}
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

    {{-- EN CASO DE SER CONFIRMADA, SE AGREGA UNA LEYENDA --}}
    @if ($cotizacion->confirmada)
        <div class="card bg-gradient-success">
            <div class="card-header">
                <h5 class="card-title">La cotización fue aprobada el {{$cotizacion->confirmada->format('d/m/Y')}}</h5>
            </div>
            <div class="card-body">
                <p>Lugar de entrega: {{$cotizacion->dde->lugar_entrega}}</p>
                <p>Domicilio: {{$cotizacion->dde->domicilio}} - {{$cotizacion->dde->localidad->nombre}}, {{$cotizacion->dde->provincia->nombre}}</p>
                <p>Condiciones: {{$cotizacion->dde->condiciones}}</p>
                <p>Observaciones: {{$cotizacion->dde->observaciones}}</p>
            </div>
            <div class="card-footer">
                @if ($cotizacion->archivos()->exists())
                    <p>La aprobación recibió una Orden de Provisión, puede descargarla en este
                        <a class="text-reset"
                            href="{{ route('administracion.cotizaciones.descargapdf', ['cotizacion' => $cotizacion, 'doc' => 'provision']) }}"
                            target="_blank">
                            <strong><u>link</u></strong>
                        </a>
                    </p>
                @endif
            </div>
        </div>
    @endif

    {{-- EN CASO DE SER RECHAZADA, SE AGREGA UNA LEYENDA --}}
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
                        <a class="text-reset"
                            href="{{ route('administracion.cotizaciones.descargapdf', ['cotizacion' => $cotizacion, 'doc' => 'rechazo']) }}"target="_blank">
                            <strong><u>link</u></strong>
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
            <table id="tablaProductos" class="table table-bordered table-condensed table-responsive-md" width="100%">
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
                                @include('administracion.cotizaciones.partials.acciones-show')
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
