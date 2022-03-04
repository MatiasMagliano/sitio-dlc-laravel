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
            <a href="{{ route('administracion.cotizaciones.index') }}" role="button"
                class="btn btn-md btn-secondary">Volver a cotizaciones</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row d-flex">
                <div class="col-8">
                    <h5 class="heading-small text-muted mb-1">Datos básicos de la cotización</h5>
                </div>
                <div class="col-4 text-right">
                    @if ($cotizacion->count() == 0)
                        <form action="{{route('administracion.cotizacions.destroy', $cotizacion)}}" method="post" class="d-inline">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="far fa-trash-alt"></i>&nbsp;<span class="hide">Borrar cotización</span>
                            </button>
                        </form>
                    @else
                    <button type="button" class="btn btn-sm btn-primary"
                        onclick="confirmarCotizacion()">
                        <i class="fas fa-share-square"></i>&nbsp;<span class="hide">Finalizar cotización</span>
                    </button>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-responsive-md" width="100%">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Usuario</th>
                        <th>Lineas cotizadas</th>
                        <th>Vol. dinero</th>
                        <th>Vol. cantidad</th>
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
                        <td style="vertical-align: middle;">${{$cotizacion->monto_total}}</td>
                        <td style="vertical-align: middle;">{{$cotizacion->productos->sum('cantidad')}}</td>
                        <td style="vertical-align: middle;">
                            @if (!$cotizacion->finalizada)
                                <span class="text-danger">Pendiente: </span> {{$cotizacion->estado}}
                            @else
                                <span class="text-success">Presentada el: </span> {{$cotizacion->finalizada->format('d/m/Y')}}
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row d-flex">
                <div class="col-8">
                    <h5 class="heading-small text-muted mb-1">Productos: {{$cotizacion->productos->sum('cantidad')}}</h5>
                </div>
                <div class="col-4 text-right">
                    @if (!$cotizacion->finalizada)
                        <a href="{{ route('administracion.cotizaciones.agregar.producto', ['cotizacion' => $cotizacion->id]) }}"
                            class="btn btn-sm btn-primary">
                            <i class="fas fa-plus fa-sm"></i>&nbsp;<span class="hide">agregar productos</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            <table id="tablaProductos" class="table table-responsive-md table-bordered table-condensed" width="100%">
                <thead>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio unitario</th>
                    <th>Total</th>
                    <th></th>
                </thead>
                <tbody>
                    @foreach ($cotizacion->productos as $cotizado)
                    <tr>
                        <td>{{--Producto: producto+presentacion--}}
                            {{$cotizado->producto->droga}}, {{$cotizado->producto->presentaciones->forma}} {{$cotizado->producto->presentaciones->presentacion}}
                        </td>
                        <td>
                            {{$cotizado->cantidad}}
                        </td>
                        <td>
                            {{$cotizado->precio}}
                        </td>
                        <td>
                            ${{$cotizado->total}}
                        </td>
                        <td>
                            @if (!$cotizacion->finalizada)
                                <a href="{{ route('administracion.cotizaciones.editar.producto', ['cotizacion' => $cotizacion, 'productoCotizado' => $cotizado]) }}"
                                    class="btn btn-link" data-toggle="tooltip" data-placement="bottom"
                                    title="Editar producto cotizado">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <form action="{{ route('administracion.cotizaciones.borrar.producto', ['cotizacion' => $cotizacion, 'productoCotizado' => $cotizado]) }}"
                                    id="{{$cotizado->id}}" method="post" class="d-inline">
                                    @csrf
                                    @method('delete')
                                    <button type="button" class="btn btn-link" data-toggle="tooltip"
                                        data-placement="bottom" title="Borrar cotización"
                                        onclick="borrarProductoCotizado({{$cotizado->id}})">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
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
    <script>
        $(document).ready(function() {
            $('#tablaProductos').DataTable( {
                "responsive": true,
                "dom": 'Pfrtip',
                "scrollY": "50vh",
                "scrollCollapse": true,
                "paging": false,
                "order": [0, 'asc'],
                "bInfo": false,
                "searching": false
            });
        });
        function confirmarCotizacion(){
            Swal.fire({
                icon: 'warning',
                title: 'Presentar cotización',
                text: 'A continuación se le presentará un archivo PDF para descargar y presentar al cliente',
                confirmButtonText: 'Presentar',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.replace('{{ route('administracion.cotizaciones.finalizar', $cotizacion) }}');
                }
            });
        }

        function borrarProductoCotizado(id){
            Swal.fire({
                icon: 'warning',
                title: 'Borrar producto',
                text: 'Esto quitará el producto de la lista.',
                confirmButtonText: 'Borrar',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#' + id).submit();
                    window.location.replace('{{ route('administracion.cotizaciones.show', ['cotizacione' => $cotizacion]) }}');
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
