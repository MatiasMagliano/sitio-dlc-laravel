@extends('adminlte::page')

@section('css')
    <style>
        .btn-shw-hover:hover {
            color: white;
            background-color: rgb(0, 37, 160);
        }

        .btn-rm-hover:hover {
            color: white;
            background-color: rgb(160, 0, 0);
        }

        .btn-edt-hover:hover {
            color: white;
            background-color: darkgreen;
        }

    </style>
@endsection

@section('title', 'Administrar Productos')

@section('content_header')
    <div class="row">
        <div class="col-xl-8">
            <h1>Administración de productos</h1>
        </div>
        <div class="col-xl-4 d-flex justify-content-xl-end">
            <a href="{{ route('administracion.productos.create') }}" role="button" class="btn btn-md btn-success">Crear producto</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')
    <x-adminlte-card>
        <table id="tabla2" class="table table-bordered display nowrap" style="width: 100%;">
            <thead>
                <th>Droga</th>
                <th>Presentación</th>
                <th>Proveedor</th>
                <th>Lotes vigentes</th>
                <th>Acciones</th>
            </thead>
            <tbody>
                @foreach ($productos as $producto)
                    <tr>
                        <td width="200px" style="vertical-align: middle;">
                            {{--<a href="{{ route('administracion.lotes.edit', $producto->id) }}">{{ $producto->droga }}</a>--}}
                            {{ $producto->droga }}
                        </td>
                        <td style="vertical-align: middle;">
                            {{-- Aquí se hace referencia a la relación creada en el modelo --}}
                            @foreach ($producto->presentaciones as $presentacion)
                                {{ $presentacion->forma }}, {{ $presentacion->presentacion }}
                                <br>
                                @if ($presentacion->hospitalario || $presentacion->trazabilidad)
                                <strong>Producto: </strong>
                                @endif
                                @if ($presentacion->hospitalario)
                                     HOSPITALARIO
                                @endif
                                @if ($presentacion->trazabilidad)
                                    {{--MÉTODO NO IMPLEMENTADO TODAVÍA--}}
                                    <span style="color: red;">TRAZABLE </span><a href="{{ route('administracion.trazabilidad.show', $producto->id) }}" class="btn-sm bg-gray" role="button">Ver</a>
                                @endif
                            @endforeach
                        </td>
                        <td>
                            @foreach ($producto->proveedores as $proveedor)
                                <a href="{{ route('administracion.proveedores.show', $proveedor->id) }}">{{ $proveedor->razonSocial }}</a><br>
                            @endforeach
                        </td>
                        <td>
                            @foreach ($producto->lotes as $lote)
                                <strong>Lote:</strong> <a href="{{ route('administracion.lotes.show', $lote->id) }}">{{ $lote->identificador }}</a> | <strong>Vto:</strong> {{ $lote->hasta->format('d/m/Y') }} <br>
                            @endforeach
                        </td>
                        <td class="text-center" style="vertical-align: middle;" width="100px">

                            {{-- Botón modificar --}}
                            <a href="{{ route('administracion.productos.edit', $producto->id) }}" role="button"
                                class="btn btn-sm btn-default btn-edt-hover mx-1 shadow">
                                <i class="fas fa-lg fa-fw fa-cog"></i></a>

                            {{-- se crea este método, porque el borrado en Laravel se hace por POST --}}
                            <a class="btn btn-rm-hover btn-sm btn-light mx-1 shadow" onclick="event.preventDefault();
                                        document.getElementById('form-borrar-producto-{{ $producto->id }}').submit();"
                                role="button">
                                <i class="fa fa-lg fa-fw fa-trash"></i></a>
                            <form id="form-borrar-producto-{{ $producto->id }}"
                                action="{{ route('administracion.productos.destroy', $producto->id) }}" method="POST"
                                style="display: none">
                                @csrf
                                @method("DELETE")
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-adminlte-card>
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
        });
    </script>
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión</b> 2.0 (LARAVEL V.8)
    </div>
@endsection
