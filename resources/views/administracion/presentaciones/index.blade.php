@extends('adminlte::page')

@section('css')
    <style>
        tabla1.dataTable thead th {
            border-bottom: none;
        }

        tabla1.dataTable tfoot th {
            border-top: none;
            border-bottom: 1px solid  #111;
        }

        #datosLoteActivo {
            height: 50vh;
        }

        #datosLoteInactivo {
            display: none;
        }
    </style>
@endsection

@section('title', 'Administrar Presentaciones')

@section('content_header')
    <div class="row">
        <div class="col-md-8">
            <h1>Administración de presentaciones</h1>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <a href="{{ route('administracion.clientes.create') }}" role="button" class="btn btn-md btn-success">Crear
                Presentación</a>
            &nbsp;
            <a href="{{ url()->previous() }}" role="button" class="btn btn-md btn-secondary">Volver</a>
        </div>
    </div>
@endsection

@section('content')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
    <x-adminlte-card>
        <table id="tabla2" class="table table-bordered table-responsive-md" style="width: 100%;">
            <thead class="bg-gray">
                <th>Forma</th>
                <th>Presentación</th>
                <th>Alta</th>
                <th>Productos relacionados</th>
            </thead>
            <tbody>
                @foreach ($presentaciones as $presentacion)
                    <tr id="{{$presentacion->id}}">
                        <td>
                            @if($presentacion->hospitalario === 1)
                                <strong>HOSPITALARIO - </strong>
                                {{ $presentacion->forma }}
                            @else
                                {{ $presentacion->forma }}
                            @endif
                            @if($presentacion->trazabilidad === 1)
                                <strong>(Trazable)</strong>
                            @endif
                        </td>
                        <td>{{ $presentacion->presentacion }}</td>
                        <td>{{ $presentacion->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a role="button" id="{{$presentacion->id}}|{{$presentacion->forma}} {{$presentacion->presentacion}}" class="btn btn-link"
                                data-toggle="modal" data-target="#modalVerProductos">
                                <i class="fas fa-search "></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-adminlte-card>

    {{-- MODAL VER PUNTOS DE VENTA --}}
    <div class="modal fade" id="modalVerProductos" tabindex="-1"
        aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Productos relacionados a <span id="nombrePresentacion"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul id="listaProductos"></ul>
                </div>
                &nbsp;
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('js/datatables-spanish.js') }}" defer></script>
    <script>
        $(document).ready(function() {
            // el datatable es responsivo y oculta columnas de acuerdo al ancho de la pantalla
            $('#tabla2').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'copyHtml5',
                        text: 'Copiar al portapapeles',
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
                responsive: {
                    details: {
                        renderer: function(api, rowIdx, columns) {
                            var data = $.map(columns, function(col, i) {
                                return col.hidden ?
                                    '<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' +
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
                }
            });

            $('#modalVerProductos').on('show.bs.modal', function(event){
                let temporal = event.relatedTarget.id;
                let aux = temporal.split('|');
                $('#listaProductos').empty();

                //Se coloca el título del modal
                $('#nombrePresentacion').empty();
                $('#nombrePresentacion').append(aux[1]);

                let datos = {
                    presentacion_id: aux[0],
                };

                $.ajax({
                    url: "{{route('administracion.presentaciones.ajax.obtenerProductos')}}",
                    type: "GET",
                    data: datos,
                }).done(function(resultado) {
                    $.each(resultado, function(index){
                        $('#listaProductos').append(
                            "<li><a href=productos/"+resultado[index].id+"/show/"+event.relatedTarget.id+">"+resultado[index].droga+"</a></li>"
                        );
                    });
                });
            })
        });
    </script>
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión</b> 2.0 (LARAVEL V.8)
    </div>
@endsection
