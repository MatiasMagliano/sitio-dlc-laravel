@extends('adminlte::page')

@section('title', 'Administrar Productos')

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
                        <th>Cliente</th>
                        <th>Usuario</th>
                        <th>Productos cotizados</th>
                        <th>Total unidades</th>
                        <th>Importe total</th>
                        <th>ESTADO</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cotizaciones as $cotizacion)
                        <tr>
                            <td>{{ $cotizacion->created_at->format('d/m/Y') }}<br>{{ $cotizacion->identificador }}</td>
                            <td>
                                {{ $cotizacion->cliente->razon_social }}<br>
                                {{ $cotizacion->cliente->tipo_afip }}: {{ $cotizacion->cliente->afip }}
                            </td>
                            <td style="vertical-align: middle;">{{ $cotizacion->user->name }}</td>
                            <td style="vertical-align: middle;">{{$cotizacion->productos->count()}}</td>
                            <td style="vertical-align: middle;">{{$cotizacion->productos->sum('cantidad')}}</td>
                            <td style="vertical-align: middle;">
                                @if ($cotizacion->finalizada)
                                    ${{$cotizacion->productos->sum('monto_total')}}
                                @else
                                    -
                                @endif
                            </td>
                            <td style="vertical-align: middle;">
                                @if (!$cotizacion->finalizada)
                                    <span class="text-danger">Pendiente: </span> {{ $cotizacion->estado }}
                                @else
                                    <span class="text-success">Finalizada: </span> {{ $cotizacion->estado }}
                                @endif
                            </td>
                            <td>
                                @if (!$cotizacion->finalizada)
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
                                @else
                                    <a href="{{ route('administracion.cotizaciones.show', ['cotizacione' => $cotizacion]) }}"
                                        class="btn btn-link" data-toggle="tooltip" data-placement="bottom"
                                        title="Ver cotización">
                                        <i class="fal fa-search"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-adminlte-card>
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
                "order": [1, 'asc'],
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
                        targets: 7,
                        width: 70,
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
