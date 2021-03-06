@extends('adminlte::page')

@section('title', 'Administrar Clientes')

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

    </style>
@endsection

@section('content_header')
    <div class="row">
        <div class="col-md-8">
            <h1>Administración de clientes</h1>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <a href="{{ route('administracion.clientes.create') }}" role="button" class="btn btn-md btn-success">Crear cliente</a>
            &nbsp;
            <a href="{{ url()->previous() }}" role="button" class="btn btn-md btn-secondary">Volver</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
    <div class="card">
        <div class="card-header texto-header">
            <h5>Instrucciones para generar reportes de esta sección</h5>
            <p>Para exportar los datos de la siguiente tabla es necesario seleccionar primero las columnas visibles en el
                set de herramientas disponible y luego genere la extensión que necesite.</p>
        </div>
        <div class="card-body">
            <table id="tabla_clientes" class="table table-bordered table-responsive-md" width="100%">
                <thead>
                    <th>Razón Social</th>
                    <th>Inscripción AFIP</th>
                    <th>Contacto</th>
                    <th>Dirección de envío</th>
                    <th>Última compra</th>
                    <th>Acciones</th>
                </thead>
                <tbody>
                    @foreach ($clientes as $cliente)
                        <tr>
                            <td>
                                <u>Nombre corto</u> {{ $cliente->nombre_corto }}
                                <br>
                                {{ $cliente->razon_social }}</td>
                            <td>
                                <span style="text-transform: uppercase;">{{ $cliente->tipo_afip }}</span>:
                                {{ $cliente->afip }}
                            </td>
                            <td>
                                <u>Sr/a: {{ $cliente->contacto }}</u> <br>
                                Tel: {{ $cliente->telefono }} <br>
                                E-mail: {{ $cliente->email }}
                            </td>
                            <td>
                                <a role="button" id="{{$cliente->id}}" class="btn btn-sm btn-outline-dark"
                                    data-toggle="modal" data-target="#modalVerPuntosEntrega">
                                    Puntos de entrega</a>
                            </td>
                            <td>{{ optional($cliente->ultima_compra)->format('d/m/Y') }}</td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL VER PUNTOS DE VENTA --}}
    <div class="modal fade" id="modalVerPuntosEntrega" tabindex="-1"
        aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Puntos de entrega</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-responsive-md" style="width: 100%;">
                        <thead>
                            <th>Lugar de entrega</th>
                            <th>Domicilio</th>
                            <th>Provincia</th>
                            <th>Localidad</th>
                            <th>Condiciones</th>
                            <th>Observaciones</th>
                            <th></th>
                        </thead>
                        <tbody id="cuerpoTablaPtsEntrega">

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    @include('partials.alerts')
    <script type="text/javascript" src="{{ asset('js/datatables-spanish.js') }}" defer></script>
    <script>
        $(document).ready(function() {
            // el datatable es responsivo y oculta columnas de acuerdo al ancho de la pantalla
            var tabla_clientes = $('#tabla_clientes').DataTable({
                "processing": true,
                "dom": 'Bfrtip',
                "order": [1, 'asc'],
                "buttons": [{
                        extend: 'copyHtml5',
                        text: 'Copiar al portapapeles',
                        exportOptions: {
                            columns: ':visible'
                        }
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
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: ':visible'
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
            });

            $('#modalVerPuntosEntrega').on('show.bs.modal', function(event){
                $('#cuerpoTablaPtsEntrega').empty();
                let datos = {
                    cliente_id: event.relatedTarget.id,
                };

                $.ajax({
                    url: "{{route('administracion.clientes.ajax.obtenerPuntosEntrega')}}",
                    type: "GET",
                    data: datos,
                }).done(function(resultado) {
                    $.each(resultado, function(index){
                        $('#cuerpoTablaPtsEntrega').append("<tr><td>"
                            +resultado[index].lugar_entrega+"</td><td>"
                            +resultado[index].domicilio+"</td><td>"
                            +resultado[index].provincia+"</td><td>"
                            +resultado[index].localidad+"</td><td>"
                            +resultado[index].condiciones+"</td><td>"
                            +resultado[index].observaciones+"</td><td>"
                            +"<i class='fas fa-pencil-alt'></i></td>"
                            +"<tr>"
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
