@extends('adminlte::page')

@section('title', 'Administrar Productos')

@section('css')
    <style>
        .fondoTabla {
            background-color: rgba(150, 150, 255, 0.2);
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
            <h1>Administración de cotizaciones</h1>
        </div>
        <div class="col-xl-4 d-flex justify-content-xl-end">
            <a href="{{ route('administracion.cotizaciones.create') }}" role="button"
                class="btn btn-md btn-success">Crear cotización</a>
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
            <table id="tabla2" class="table table-responsive-md fondoTabla" width="100%">
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
                                <span class="text-success">Presentada el: </span> {{$cotizacion->finalizada->format('dd/mm/YYYY')}}
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
                        {{-- TERMINÉ ACÁ --> AGREGAR LA RUTA --}}
                        <a href="{{ route('sales.product.add', ['sale' => $sale->id]) }}" class="btn btn-sm btn-primary">Add</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body"></div>
    </div>
@endsection

@section('js')
    @include('partials.alerts')
    <script>
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
    </script>
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión</b> 2.0 (LARAVEL V.8)
    </div>
@endsection
