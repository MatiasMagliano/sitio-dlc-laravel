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
            <a href="{{ route('administracion.clientes.create') }}" role="button" class="btn btn-md btn-success">Crear
                cliente</a>
            &nbsp;
            <a href="{{ route('home') }}" role="button" class="btn btn-md btn-secondary">Volver</a>
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
            <table id="tabla_clientes" class="table table-bordered" width="100%">
                <thead class="bg-gray">
                    <th>Nombre corto</th>
                    <th>Razón social</th>
                    <th>Cond. AFIP</th>
                    <th>Contacto</th>
                    <th>Punto de entrega principal</th>
                    <th>Última compra</th>
                </thead>
                <tbody>
                    @foreach ($clientes as $cliente)
                        <tr>
                            <td class="align-middle text-center">
                                <span class="text-uppercase">{{$cliente->nombre_corto}}</span>
                            </td>
                            <td class="align-middle">
                                {{$cliente->razon_social}}
                            </td>
                            <td class="align-middle">
                                <span style="text-transform: uppercase;">{{ $cliente->tipo_afip }}: </span>
                                {{ $cliente->afip }}
                            </td>
                            <td class="align-middle">
                                <u>Sr/a: {{ $cliente->contacto }}</u>
                                <br>
                                Tel: {{ $cliente->telefono }}
                                <br>
                                E-mail: {{ $cliente->email }}
                            </td>
                            <td class="align-middle">
                                {{-- {{$cliente->id}} --}}
                                {{$cliente->dde[0]->lugar_entrega}} <br>
                                {{$cliente->dde[0]->domicilio}} <br>
                                {{$cliente->dde[0]->localidad->nombre}}, {{$cliente->dde[0]->provincia->nombre}}
                            </td>
                            <td class="align-middle text-center">
                                @if ($cliente->ultima_compra == null)
                                    NUNCA
                                @else
                                    {{ $cliente->ultima_compra->format('d/m/Y') }}
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
            // el datatable es responsivo y oculta columnas de acuerdo al ancho de la pantalla
            var tabla_clientes = $('#tabla_clientes').DataTable({
                "processing": true,
                "dom": 'QBfrtip',
                "order": [0, 'asc'],
                "buttons": [{
                        extend: 'copyHtml5',
                        text: 'Copiar al portapapeles',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        filename: 'clientes',
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
                        filename: 'clientes',
                        exportOptions: {
                            columns: '0, 1, 2, 4'
                        },
                        customize: function(doc) {
                            var ahora = new Date();
                            var fecha = ahora.getDate() + '/' + (ahora.getMonth() + 1) + '/' +
                                ahora.getFullYear();
                            // Set page margins [left,top,right,bottom] or [horizontal,vertical]
                            doc.pageMargins = [10,60,10,20];
                            doc.content.splice(0, 1);
                            doc.defaultStyle.fontSize = 6;
                            doc.styles.tableHeader.fontSize = 7;
                            doc['header'] = (function() {
                                return {
                                    columns: [{
                                        alignment: 'center',
                                        fontSize: 11,
                                        text: 'Listado de clientes al ' + fecha
                                            .toString()
                                    }],
                                    margin: 20
                                }
                            });
                            doc['footer'] = (function(page, pages) {
                                return {
                                    columns: [,
                                        {
                                            alignment: 'right',
                                            text: ['página ', {
                                                text: page.toString()
                                            }, ' de ', {
                                                text: pages.toString()
                                            }]
                                        }
                                    ],
                                    margin: 20
                                }
                            });
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
        });
    </script>
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión</b> 2.0 (LARAVEL V.8)
    </div>
@endsection
