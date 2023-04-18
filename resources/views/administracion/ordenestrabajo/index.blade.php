@extends('adminlte::page')

@section('title', 'Administrar Productos')

@section('css')
    <style>
        .texto-header {
            padding: 0 20px;
            height: 60px;
            overflow-y: auto;
            /*font-size: 14px;*/
            font-weight: 500;
            color: #000000;
        }

        .texto-header::-webkit-scrollbar {
            width: 5px;
            background-color: #282828;
        }

        .texto-header::-webkit-scrollbar-thumb {
            background-color: #ffc107;
        }

        .pre-scrollable{
            max-height: 230px;
        }

    </style>
@endsection

@section('content_header')
    <div class="row">
        <div class="col-xl-8">
            <h1>Administración de órdenes de trabajo</h1>
        </div>
        <div class="col-xl-4 d-flex justify-content-xl-end">
            <a href="{{ route('home') }}" role="button" class="btn btn-md btn-secondary">Volver</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
    <div class="card">
        <div class="card-body">
            <div class="texto-header">
                <h5>Generación de Órdenes de trabajo</h5>
                <p>Aquí se listan todas las Cotizaciones aprobadas y con Orden de Provisión aceptada. El sistema automáticamente genera la Orden de Trabajo, le asigna Lotes disponibles y finalmente la transfiere a producción.</p>
                <p>En caso de no disponer de lotes, se genera una grilla en la siguiente sección con una vista general de los lotes asignados y de aquellos que se deberán comprar en un futuro cercano.</p>
            </div>
        </div>
    </div>
    @can('es-administracion')
    <x-adminlte-card title="Órdenes por generar" collapsible>
        <div class="processing height-control">
            <table id="ordenesPotenciales" class="table table-bordered table-responsive-md" width="100%">
                <thead class="bg-gray">
                    <tr>
                        <th>Fecha</th>
                        <th>Identificador/Usuario</th>
                        <th>Cliente</th>
                        <th>Productos cotizados</th>
                        <th>Total unidades</th>
                        <th>Importe total</th>
                        <th>ESTADO</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ordenes_potenciales as $orden)
                        <tr>
                            <td style="vertical-align: middle;">
                                {{ $orden->created_at->format('d/m/Y') }}
                            </td>
                            <td style="vertical-align: middle;">
                                <strong>{{ $orden->identificador }}</strong>
                                <br>
                                {{ $orden->user->name }}
                            </td>
                            <td style="vertical-align: middle;">
                                {{ $orden->cliente->razon_social }}<br>
                                {{ $orden->cliente->tipo_afip }}: {{ $orden->cliente->afip }}
                            </td>
                            <td style="vertical-align: middle; text-align:center;">{{$orden->productos->count()}}</td>
                            <td style="vertical-align: middle; text-align:center;">{{$orden->productos->sum('cantidad')}}</td>
                            <td style="vertical-align: middle; text-align:center;">
                                $ {{number_format($orden->monto_total, 2, ',', '.')}}
                            </td>
                            {{-- SE RESUME TODO A UN SOLO SWITCH, a diferencia del index de cotizaciones --}}
                                @switch($orden->estado_id)
                                    @case(4)
                                        {{-- ESTADOS DINAMICOS --}}
                                        <td style="vertical-align: middle;">
                                            <span class="text-fuchsia">Modificando OT</span>
                                        </td>

                                        {{-- ACCIONES DINAMICAS --}}
                                        <td style="vertical-align: middle; text-align:center;">
                                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal"
                                                data-target="#modalModificarOrden" id="{{$orden->id}}">Generar OT</button>
                                        </td>
                                        @break
                                    @default
                                        <p>-</p>
                                @endswitch
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-adminlte-card>
    @endcan

    <x-adminlte-card title="Órdenes en producción" collapsible>
    <div class="processing">
        <table id="ordenesDeTrabajo" class="table table-bordered table-responsive-md" width="100%">
            <thead class="bg-gray">
                <tr>
                    <th>Fecha</th>
                    <th>Identificador/Usuario</th>
                    <th>Cliente</th>
                    <th>Productos cotizados</th>
                    <th>Total unidades</th>
                    <th>ESTADO</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ordenes as $orden)
                    <tr>
                        <td style="vertical-align: middle;">
                            {{ $orden->created_at->format('d/m/Y') }}
                        </td>
                        <td style="vertical-align: middle;">
                            <strong>{{ $orden->cotizacion->identificador }}</strong>
                            <br>
                            {{ $orden->user->name }}
                        </td>
                        <td style="vertical-align: middle;">
                            {{ $orden->cotizacion->cliente->razon_social }}<br>
                            {{ $orden->cotizacion->cliente->tipo_afip }}: {{ $orden->cotizacion->cliente->afip }}
                        </td>
                        <td style="vertical-align: middle; text-align:center;">{{$orden->productos->count()}}</td>
                        <td style="vertical-align: middle; text-align:center;">{{$orden->productos->sum('cantidad')}}</td>
                        {{-- SE RESUME TODO A UN SOLO SWITCH, a diferencia del index de cotizaciones --}}
                        @switch($orden->estado_id)
                            @case(6)
                                {{-- ESTADOS DINAMICOS --}}
                                <td style="vertical-align: middle;">
                                    <span class="text-success">{{$orden->estado->estado}} desde el: {{$orden->en_produccion->format('d/m/Y')}}</span>
                                </td>

                                {{-- ACCIONES DINAMICAS --}}
                                <td style="vertical-align: middle; text-align:center;">
                                    <a href="{{ route('administracion.ordentrabajo.descargapdf', ['ordentrabajo' => $orden]) }}"
                                        class="btn btn-sm btn-info">
                                        Imprimir OT
                                    </a>
                                </td>
                                @break

                            @case(7)
                                {{-- ESTADOS DINAMICOS --}}
                                <td style="vertical-align: middle;">
                                    <span class="text-success">{{$orden->estado->estado}} desde el: {{$orden->en_produccion->format('d/m/Y')}}</span>
                                </td>

                                {{-- ACCIONES DINAMICAS --}}
                                <td style="vertical-align: middle; text-align:center;">
                                    <a href="{{ route('administracion.ordentrabajo.show', ['ordentrabajo' => $orden]) }}"
                                        class="btn btn-sm btn-danger">
                                        Ver
                                    </a>
                                </td>
                                @break
                            @default
                                <td><p>-</p></td>
                                <td><p>-</p></td>
                        @endswitch
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </x-adminlte-card>

    {{-- MODAL MODIFICACIÓN ITEMS DEFINITIVOS --}}
    <div class="modal fade" id="modalModificarOrden" tabindex="-1" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-gradient-danger">
                    <h5 class="modal-title">Generar Orden de Trabajo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('administracion.ordentrabajo.store')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Para generar la <strong>Orden de Trabajo</strong>, destilde solo aquellas líneas que no fueron aprobadas en la <strong>Orden de Provisión</strong>, luego indique el plazo de entrega y por último presione Continuar.</p>
                        <hr>

                        <div>
                            <a class="btn-link" onclick="selectAll()" style="cursor: pointer;">Seleccionar</a>
                            <a class="btn-link" onclick="UnSelectAll()" style="cursor: pointer;">| Deseleccionar</a>
                        </div>
                        <div id="lineasCotizadas" class="form-group pre-scrollable"></div>
                        <div class="form-group">
                            <label for="plazoDeEntrega">Plazo de entrega</label>
                            <textarea name="plazo_entrega" class="form-control" id="plazoDeEntrega" rows="2"></textarea>
                            <small class="form-text text-muted">(Opcional)</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="guardarGenOD" class="btn btn-success">Continuar</button>
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
        function selectAll(){
            var items=document.getElementsByName('lineasOrdenTrabajo[]');
            for(var i=0; i<items.length; i++){
                if(items[i].type=='checkbox')
                    items[i].checked=true;
            }
        }

        function UnSelectAll(){
            var items=document.getElementsByName('lineasOrdenTrabajo[]');
            for(var i=0; i<items.length; i++){
                if(items[i].type=='checkbox')
                    items[i].checked=false;
            }
        }

        $(document).ready(function() {
            $('#ordenesPotenciales').DataTable({
                "dom": 'rtip',
                "pageLength": 3,
                "order": [0, 'desc'],
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
                "columnDefs": [
                    {
                        targets: 0,
                        type: 'date'
                    },
                    {
                        targets: 2,
                        width: 250,
                    },
                    {
                        targets: 3,
                        width: 70
                    },
                    {
                        targets: 4,
                        width: 70
                    },
                    {
                        targets: 7,
                        width: 80,
                    },
                ],
            });

            $('#ordenesDeTrabajo').DataTable({
                "dom": 'rtip',
                "pageLength": 3,
                "order": [0, 'desc'],
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
                "columnDefs": [
                    {
                        targets: 0,
                        type: 'date'
                    },
                    {
                        targets: 2,
                        width: 250,
                    },
                    {
                        targets: 3,
                        width: 70
                    },
                    {
                        targets: 4,
                        width: 70
                    },
                    {
                        targets: 6,
                        width: 80,
                    },
                ]
            });

            $('#modalModificarOrden').on('show.bs.modal', function(event){
                $('#lineasCotizadas').empty();

                let datos = {
                    cotizacion_id: event.relatedTarget.id,
                };

                $.ajax({
                    url: "{{route('administracion.cotizadas.ajax.obtener')}}",
                    type: "GET",
                    data: datos,
                })
                .done(function(resultado) {
                    let linea = 1;
                    $.each(resultado, function(index){
                        $('#lineasCotizadas').append(
                            "<div class='form-check'>"
                            +"<input class='form-check-input' name='lineasOrdenTrabajo[]' type='checkbox' value="+ resultado[index].cotizado_id +" id='defaultCheck1' checked>"
                            +"<label class='form-check-label' for='defaultCheck1'>Línea "+ linea +", "+resultado[index].droga +" - "+ resultado[index].forma +" "+ resultado[index].presentacion +"</label>"
                            +"</div>"
                        );
                        linea = linea + 1;
                    });
                });
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
