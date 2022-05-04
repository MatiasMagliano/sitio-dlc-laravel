@extends('adminlte::page')

@section('title', 'Administrar Productos')

@section('css')
    <style>
        $custom-file-text: (
            en: "Browse",
            es: "Elegir"
        );
    </style>
@endsection

@section('content_header')
    <div class="row">
        <div class="col-xl-8">
            <h1>Administración de cotizaciones</h1>
        </div>
        <div class="col-xl-4 d-flex justify-content-xl-end">
            <a href="{{ route('administracion.cotizaciones.create') }}" role="button" class="btn btn-md btn-success">Crear
                cotización</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')
    <x-adminlte-card>
        <div class="processing">
            <table id="tabla2" class="table table-bordered table-responsive-md" width="100%">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Ident./Usuario</th>
                        <th>Cliente</th>
                        <th>Productos cotizados</th>
                        <th>Total unidades</th>
                        <th>Importe total</th>
                        <th>ESTADO</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cotizaciones as $cotizacion)
                        <tr class="{{$cotizacion->rechazada ? 'table-secondary' : ''}}">
                            <td style="vertical-align: middle;">
                                {{ $cotizacion->created_at->format('d/m/Y') }}
                            </td>
                            <td style="vertical-align: middle;">
                                <strong>{{ $cotizacion->identificador }}</strong>
                                <br>
                                {{ $cotizacion->user->name }}
                            </td>
                            <td style="vertical-align: middle;">
                                {{ $cotizacion->cliente->razon_social }}<br>
                                {{ $cotizacion->cliente->tipo_afip }}: {{ $cotizacion->cliente->afip }}
                            </td>
                            <td style="vertical-align: middle; text-align:center;">{{$cotizacion->productos->count()}}</td>
                            <td style="vertical-align: middle; text-align:center;">{{$cotizacion->productos->sum('cantidad')}}</td>
                            <td style="vertical-align: middle; text-align:center;">
                                $ {{number_format($cotizacion->monto_total, 2, ',', '.')}}
                            </td>
                            {{-- ESTADOS DINAMICOS --}}
                            <td style="vertical-align: middle;">
                                @switch($cotizacion->estado_id)
                                    @case(1)
                                        <span class="text-fuchsia">{{$cotizacion->estado->estado}}</span>
                                        @break
                                    @case(2)
                                        <span class="text-success">{{$cotizacion->estado->estado}} {{$cotizacion->finalizada->format('d/m/Y')}}</span>
                                        @break
                                    @case(3)
                                        <span class="text-secondary">{{$cotizacion->estado->estado}} {{$cotizacion->presentada->format('d/m/Y')}}</span>
                                        @break
                                    @case(4)
                                        <span class="text-success">{{$cotizacion->estado->estado}} {{$cotizacion->confirmada->format('d/m/Y')}}</span>
                                        @break
                                    @case(5)
                                        <span class="text-danger">{{$cotizacion->estado->estado}} {{$cotizacion->rechazada->format('d/m/Y')}}</span>
                                        @break
                                    @default
                                @endswitch
                            </td>
                            {{-- ACCIONES DINAMICAS --}}
                            <td style="vertical-align: middle; text-align:center;">
                                @switch($cotizacion->estado_id)
                                    @case(1)
                                        <a href="{{ route('administracion.cotizaciones.show', ['cotizacione' => $cotizacion]) }}"
                                            class="btn btn-link" data-toggle="tooltip" data-placement="bottom"
                                            title="Editar cotización">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <form action="{{ route('administracion.cotizaciones.destroy', $cotizacion) }}"
                                            id="borrar-{{$cotizacion->id}}" method="post" class="d-inline">
                                            @csrf
                                            @method('delete')
                                            <button type="button" class="btn btn-link" data-toggle="tooltip"
                                                data-placement="bottom" title="Borrar cotización"
                                                onclick="borrarCotizacion({{$cotizacion->id}})">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                        @break
                                    @case(2)
                                        <a href="{{ route('administracion.cotizaciones.descargapdf', ['cotizacion' => $cotizacion, 'doc' => 'cotizacion']) }}"
                                            class="btn btn-sm btn-default" target="_blank">
                                            Presentar
                                        </a>
                                        @break
                                    @case(3)
                                        <div class="btn-group-vertical">
                                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                                data-target="#modalAprobarCotizacion" id="{{$cotizacion->id}}">Aprobar</button>
                                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                                data-target="#modalRechazarCotizacion" id="{{$cotizacion->id}}">Rechazar</button>
                                        </div>
                                        @break
                                    @case(4)
                                        <div class="btn-group-vertical">
                                            <a href="{{ route('administracion.cotizaciones.descargapdf', ['cotizacion' => $cotizacion, 'doc' => 'cotizacion']) }}"
                                                class="btn btn-sm btn-default" target="_blank">
                                                Cotización
                                            </a>
                                            <a href="{{ route('administracion.cotizaciones.descargapdf', ['cotizacion' => $cotizacion, 'doc' => 'provision']) }}"
                                                class="btn btn-sm btn-default" target="_blank">
                                                Provisión
                                            </a>
                                        </div>
                                        @break
                                    @case(5)
                                        <a href="{{ route('administracion.cotizaciones.show', ['cotizacione' => $cotizacion]) }}"
                                            class="btn btn-link" data-toggle="tooltip" data-placement="bottom"
                                            title="Ver cotización">
                                            <i class="fas fa-search "></i>
                                        </a>
                                        <a href="{{ route('administracion.cotizaciones.descargapdf', ['cotizacion' => $cotizacion, 'doc' => 'rechazo']) }}"
                                            class="btn btn-link" target="_blank">
                                            <i class="fas fa-file-download"></i>
                                        </a>
                                        @break
                                    @default
                                @endswitch
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-adminlte-card>

     {{-- MODAL APROBACIÓN LICITACIÓN --}}
    <div class="modal fade" id="modalAprobarCotizacion" tabindex="-1"
        aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-gradient-blue">
                    <h5 class="modal-title">Aprobar licitación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="POST" id="formAprobada" enctype="multipart/form-data">
                    @csrf
                    {{-- CAUSAS: aprobada/rechazada --}}
                    <input type="hidden" name="causa_subida" value="aprobada">
                    <div class="modal-body">
                        <p>Deberá consignar la fecha de aprobación. Opcionalmente podrá adjuntar la Orden de Compra provista por el cliente. Esto comformará información respaldatoria a la cotización.</p>
                        <hr>
                        <div class="row">
                            <div class="col form-group">
                                @section('plugins.TempusDominusBs4', true)
                                <x-adminlte-input-date name="confirmada" id="confirmada" igroup-size="md"
                                    :config="$config" placeholder="{{ __('formularios.date_placeholder') }}"
                                    autocomplete="off" required>
                                    <x-slot name="appendSlot">
                                        <div class="input-group-text bg-dark">
                                            <i class="fas fa-calendar"></i>
                                        </div>
                                    </x-slot>
                                </x-adminlte-input-date>
                            </div>
                            <div class="col custom-file">
                                <input type="file" class="custom-file-input" id="customFile" name="archivo" accept=".jpg,.png,.pdf">
                                <label class="custom-file-label" for="customFile">Seleccionar archivo</label>
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

     {{-- MODAL RECHAZO LICITACIÓN --}}
     <div class="modal fade" id="modalRechazarCotizacion" tabindex="-1"
        aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-gradient-danger">
                    <h5 class="modal-title">Rechazar licitación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="POST" id="formRechazada" enctype="multipart/form-data">
                    @csrf
                    {{-- CAUSAS: aprobada/rechazada --}}
                    <input type="hidden" name="causa_subida" value="rechazada">
                    <div class="modal-body">
                        <p>Deberá consignar la fecha de rechazo y un motivo. Opcionalmente, podrá adjuntar el <strong>pliego procesado</strong> con comparativo de precios, provisto por el cliente. Se adjuntará como información respaldatoria a la cotización rechazada.</p>
                        <div class="form-group">
                            <label for="input-motivo_rechazo">Motivo del rechazo</label>
                            <textarea name="motivo_rechazo" class="form-control @error('motivo_rechazo') is-invalid @enderror" id="input-motivo_rechazo" rows="2" required></textarea>
                            <small id="input-motivo_rechazo" class="form-text text-muted">Datos relevantes como cliente ganador, fuera de término, errores de cotización, etc..</small>
                            @error('motivo_rechazo')<div class="invalid-feedback">{{$message}}</div>@enderror
                        </div>
                        <div class="row">
                            <div class="col form-group">
                                @section('plugins.TempusDominusBs4', true)
                                <x-adminlte-input-date name="rechazada" id="rechazada" igroup-size="md"
                                    :config="$config" placeholder="{{ __('formularios.date_placeholder') }}"
                                    autocomplete="off" required>
                                    <x-slot name="appendSlot">
                                        <div class="input-group-text bg-dark">
                                            <i class="fas fa-calendar"></i>
                                        </div>
                                    </x-slot>
                                </x-adminlte-input-date>
                            </div>
                            <div class="col custom-file">
                                <input type="file" class="custom-file-input" id="customFile" name="archivo" accept=".jpg,.png,.pdf">
                                <label class="custom-file-label" for="customFile">Seleccionar archivo</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="guardarRechazada" class="btn btn-success">Continuar</button>
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
        function borrarCotizacion(id){
            Swal.fire({
                icon: 'warning',
                title: 'Borrar cotización',
                text: 'Su cotización no contiene líneas, esto borrará la referencia en el registro.',
                confirmButtonText: 'Borrar',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    debugger;
                    $('#borrar-' + id).submit();
                    window.location.replace('{{ route('administracion.cotizaciones.index') }}');
                }
            });
        }

        $(document).ready(function() {// el datatable es responsivo y oculta columnas de acuerdo al ancho de la pantalla
            var tabla2 = $('#tabla2').DataTable({
                "processing": true,
                "dom": 'Bfrtip',
                "order": [0, 'desc'],
                "buttons": [{
                        extend: 'copyHtml5',
                        text: 'Copiar al portapapeles'
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Imprimir',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    {
                        extend: 'colvis',
                        text: 'Seleccionar columnas'
                    }
                ],
                "responsive": [{
                    "details": {
                        renderer: function(api, rowIdx, columns) {
                            var data = $.map(columns, function(col, i) {
                                return col.hidden ?
                                    '<tr data-dt-row="' + col.rowIndex +
                                    '" data-dt-column="' +
                                    col.columnIndex + '">' +
                                    '<td>' + col.title + ':' + '</td> ' +
                                    '<td>' + col.data + '</td>' +
                                    '</tr>' :
                                    '';
                            }).join('');

                            return data ?
                                $('<table/>').append(data) :
                                false;
                        }
                    }
                }],
                "columnDefs": [{
                        targets: 0,
                        type: 'date'
                    },
                    {
                        targets: 7,
                        width: 80,
                    },
                ],
            });

            // función para que aparezca el nombre de archivo en el input
            $(".custom-file-input").on("change", function() {
                var fileName = $(this).val().split("\\").pop();
                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            });

            // se coloca el ACTION en el form: guardarAprobada
            $('#modalAprobarCotizacion').on('show.bs.modal', function(event){
                $('#formAprobada').attr('action', 'cotizaciones/'+event.relatedTarget.id+'/aprobarCotizacion');
            });

            // se coloca el ACTION en el form: guardarRechazada
            $('#modalRechazarCotizacion').on('show.bs.modal', function(event){
                $('#formRechazada').attr('action', 'cotizaciones/'+event.relatedTarget.id+'/rechazarCotizacion');
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
