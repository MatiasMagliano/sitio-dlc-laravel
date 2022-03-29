@extends('adminlte::page')

@section('title', 'Administrar Productos')

@section('content_header')
    <div class="row">
        <div class="col-md-8">
            <h1>Administración de productos</h1>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <a href="{{ route('administracion.productos.create') }}" role="button"
                class="btn btn-sm btn-success">Crear producto</a>
            &nbsp;
            <a href="{{ route('administracion.lotes.index') }}" role="button"
                class="btn btn-sm btn-success">Crear lotes</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')
    <x-adminlte-card>
        <div class="m-2">
            <table id="tabla2" class="table table-bordered table-responsive-md" width="100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Droga</th>
                        <th>Presentaciones</th>
                        <th>Lotes vigentes</th>
                        <th>Proveedores</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productos as $producto)
                        @foreach ($producto->presentaciones as $presentacion)
                            <tr>
                                <td>{{$producto->id}}</td>
                                <td style="vertical-align: middle;">
                                    <a href="{{route('administracion.productos.edit', $producto)}}" class="btn-link justify-content-md-end">
                                        {{$producto->droga}}
                                    </a>
                                </td>
                                <td style="vertical-align: middle;">
                                    {{$presentacion->forma}}, {{$presentacion->presentacion}}
                                </td>
                                <td>
                                    @foreach ($presentacion->lotesPorPresentacion($producto->id) as $lote)
                                        <div class="row">
                                            <div class="col">L: {{$lote->identificador}}</div>
                                            <div class="col">Vto: {{$lote->fecha_vencimiento->format('d/m/Y')}}</div>
                                        </div>
                                    @endforeach
                                </td>
                                <td style="vertical-align: middle;">
                                    @foreach ($presentacion->ProveedoresPorPresentacion($producto->id) as $proveedor)
                                        {{$proveedor->razonSocial}} <br>
                                    @endforeach
                                </td>
                                <td class="text-center" style="vertical-align: middle;" width="100px">
                                    {{-- PROCEDIMIENTO DE BORRADO --}}
                                    <form action="{{ route('administracion.productos.destroy', $producto) }}" id="frm-borrar-{{$producto->id}}"
                                        method="post" class="d-inline">

                                        @csrf
                                        @method('delete')
                                        <button type="button" class="btn btn-link" data-toggle="tooltip" data-placement="middle" title="Borrar producto"
                                            onclick="
                                                event.preventDefault();
                                                let advertencia = '<p>Esto implica el borrado de todas las relaciones del producto. Si considera que es un error, deberá restaurar manualmente las relaciones</p>';

                                                Swal.fire({
                                                    icon: 'warning',
                                                    title: '¿Está seguro de eliminar el producto?',
                                                    html: '<p style=color: red; font-wieght:800; font-size:1.3em;>¡ATENCION!</p>' + advertencia,
                                                    confirmButtonText: 'Borrar',
                                                    showCancelButton: true,
                                                    cancelButtonText: 'Cancelar',
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        $('#frm-borrar-{{$producto->id}}').submit()
                                                    }
                                                });
                                            ">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
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
        $('#loading').html('Loading table')
        $(document).ready(function() {
            // el datatable es responsivo y oculta columnas de acuerdo al ancho de la pantalla
            var tabla2 = $('#tabla2').DataTable({
                "processing": true,
                "dom": 'Bfrtip',
                "order": [1, 'asc'],
                "buttons": [
                    {extend: 'copyHtml5', text: 'Copiar al portapapeles'},
                    {extend: 'excelHtml5', exportOptions: {columns: ':visible'}},
                    {extend: 'print', text: 'Imprimir', exportOptions: {columns: [0, 1, 2, 3]}},
                    {extend: 'pdfHtml5', exportOptions: {columns: [0, 1, 2, 3]}},
                    {extend: 'colvis', text: 'Seleccionar columnas'}
                ],
                "responsive": [
                    {
                        "details": {
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
                ],
                "columnDefs": [
                   {targets: 0, visible: false}
                ]
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
