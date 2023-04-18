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
            <a
                @if(url()->previous() == route('administracion.cotizaciones.index'))
                    href="{{route('administracion.cotizaciones.index')}}"
                @elseif (url()->previous() == route('administracion.cotizaciones.historico'))
                    href="{{route('administracion.cotizaciones.historico')}}"
                @else
                    href="{{route('administracion.cotizaciones.index')}}"
                @endif
                role="button" class="btn btn-md btn-secondary">
                    Volver
            </a>
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
                    <th>Acciones</th>
                </thead>
            </table>
        </div>
    </div>

    {{-- MODAL EDICIÓN DE PRODUCTO --}}
    <div class="modal fade" id="modalModifProducto" tabindex="-1" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="" method="POST" id="formModifProducto" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="prodCotiz_id" id="input-prodCotiz" value="">
                    <div class="modal-header bg-gradient-blue">
                        <h5 class="modal-title">Modificar producto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="input-droga">Droga</label>
                            <input type="text" name="droga" id="input-droga" min="0"class="form-control" disabled>
                        </div>

                        <div class="row d-flex">
                            <div class="col">
                                <div class="form-group">
                                    <label for="input-precio">Precio *</label>
                                    <input type="number" name="precio" id="input-precio" min="0"
                                        class="form-control @error('precio') is-invalid @enderror"
                                        value="@if(old('precio')){{old('precio')}}@else{{0}}@endif"
                                        step=".01">
                                        @error('precio')<div class="invalid-feedback">{{$message}}</div>@enderror
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label for="input-cantidad">Cantidad *</label>
                                    <input type="number" name="cantidad" id="input-cantidad" min="0"
                                        class="form-control @error('cantidad') is-invalid @enderror"
                                        value="@if(old('cantidad')){{old('cantidad')}}@else{{0}}@endif">
                                        @error('cantidad')<div class="invalid-feedback">{{$message}}</div>@enderror
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <label for="input-total">Total</label>
                                    <input type="text" name="total" id="input-total"
                                        class="form-control @error('total') is-invalid @enderror"
                                        value="@if(old('total')){{old('total')}}@else{{0}}@endif" disabled>
                                        @error('total')<div class="invalid-feedback">{{$message}}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" id="guardarAprobada" class="btn btn-success">Continuar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @include('partials.alerts')
    <script type="text/javascript" src="{{ asset('js/datatables-spanish.js') }}" defer></script>
    <script>
        var tablaProductos;

        $(document).ready(function() {
            tablaProductos = $('#tablaProductos').DataTable( {
                dom: "t",
                processing: true,
                ajax: {
                    "url": "{{route('administracion.cotizaciones.loadProdCotiz')}}",
                    "data": {cotizacion: {{$cotizacion->id}}},
                },
                scrollY: "35vh",
                scrollCollapse: true,
                paging: false,
                order: [0, 'asc'],
                columns: [
                    {
                        data: "linea",
                        class: "align-middle text-center",
                    },
                    {
                        data: "producto",
                        class: "align-middle",
                    },
                    {
                        data: "cantidad",
                        class: "align-middle text-center",
                    },
                    {
                        data: "precio",
                        class: "align-middle text-center",
                    },
                    {
                        data: "total",
                        class: "align-middle text-center",
                    },
                    {
                        data: "acciones",
                        class: "align-middle text-center",
                    },
                ],
            });
        });

        $(document).on('click', '.open_modal', function(){
            $.ajax({
                type: "GET",
                url: "{{route('administracion.cotizaciones.editar.producto')}}",
                data: {producto: $(this).val()},
                success: function(data){
                    $('#input-droga').val(
                        data.producto.droga +
                        " - " +
                        data.presentacion.forma +
                        ", " +
                        data.presentacion.presentacion
                    );

                    $('#input-prodCotiz').val(data.producto_cotizado.id);
                    $('#input-precio').val(data.producto_cotizado.precio);
                    $('#input-cantidad').val(data.producto_cotizado.cantidad);
                },
            });
        });

        // SCRIPT QUE ACTUALIZA EL TOTAL EN EL CAMPO TOTAL
        $('#input-cantidad').on('input', function() {
            if($('#input-precio').val() <= 0 || $('#input-cantidad').val() <= 0){
                $('#input-total').val('N/A');
            }
            else{
                $('#input-total').val('$' + (parseInt($('#input-cantidad').val()) * parseFloat($('#input-precio').val())).toFixed(2));
            }
        });
        $('#input-precio').on('input', function() {
            if($('#input-precio').val() <= 0 || $('#input-cantidad').val() <= 0){
                $('#input-total').val('N/A');
            }
            else{
                $('#input-total').val('$' + (parseInt($('#input-cantidad').val()) * parseFloat($('#input-precio').val())).toFixed(2));
            }
        });

        //SUBMIT DEL FORMULARIO DE MODIFICACION DE PRODUCTO
        $(document).on('submit','#formModifProducto',function(event){
            event.preventDefault();

            $.ajax({
                url: '{{route('administracion.cotizaciones.actualizar.producto')}}',
                method: 'POST',
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success:function(response)
                {
                    Swal.fire({
                        title: 'Modificar producto',
                        icon: 'success',
                        text: response.success,
                        showConfirmButton: false,
                        timer: 1500
                    });

                    $('#modalModifProducto').modal('toggle')
                    tablaProductos.ajax.reload();
                },
                error: function(response) {
                    var errors = response.responseJSON;
                    errores = '';
                    $.each( errors, function( key, value ) {
                        errores += value;
                    });
                    Swal.fire({
                        icon: 'error',
                        text: errores,
                        showConfirmButton: true,
                    });
                }
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
    </script>
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión de software 2.8</b> (PHP: v{{phpversion()}} | LARAVEL: v.{{App::VERSION()}})
    </div>
@endsection
