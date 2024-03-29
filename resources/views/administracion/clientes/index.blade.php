@extends('adminlte::page')

@section('title', 'Administrar Clientes')

@section('css')
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
    <div class="card-body">
        <table id="tabla_clientes" class="table table-bordered" width="100%">
            <thead class="bg-gray">
                <th>Nombre corto</th>
                <th>Razón social</th>
                <th>Cond. AFIP</th>
                <th>Contacto</th>
                <th>Punto de entrega principal</th>
                <th>Última compra</th>
                <th></th>
            </thead>
            <tfoot style="display: table-header-group;">
                <tr class=" bg-gradient-light">
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
            <tbody>
                @foreach ($clientes as $cliente)
                    <tr>
                        <td class="align-middle text-center">
                            <span class="text-uppercase">{{ $cliente->nombre_corto }}</span>
                        </td>
                        <td class="align-middle">
                            {{ $cliente->razon_social }}
                        </td>
                        <td class="align-middle">
                            <span style="text-transform: uppercase;">{{ $cliente->tipo_afip }}: </span>
                            {{ $cliente->afip }}
                        </td>
                        <td class="align-middle">
                            {{ $cliente->contacto }}
                            <br>
                            Tel: {{ $cliente->telefono }}
                            <br>
                            E-mail: {{ $cliente->email }}
                        </td>
                        <td class="align-middle">
                            {{-- {{$cliente->id}} --}}
                            {{ $cliente->dde[0]->lugar_entrega }} <br>
                            {{ $cliente->dde[0]->domicilio }} <br>
                            {{ $cliente->dde[0]->localidad->nombre }}, {{ $cliente->dde[0]->provincia->nombre }}
                        </td>
                        <td class="align-middle text-center">
                            @if ($cliente->ultima_compra == null)
                                NUNCA
                            @else
                                {{ $cliente->ultima_compra->format('d/m/Y') }}
                            @endif
                        </td>
                        <td class="align-middle text-center">
                            <div class="btn-group" role="group" aria-label="Acciones de cliente">
                                <a href="{{ route('administracion.clientes.edit', ['cliente' => $cliente]) }}"
                                    class="btn btn-link" data-toggle="tooltip" data-placement="middle"
                                    title="Editar cliente">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <form action="{{ route('administracion.clientes.destroy', $cliente) }}"
                                    id="frm-borrar-{{ $cliente->id }}" method="post" class="d-inline">

                                    @csrf
                                    @method('delete')
                                    <a role="button" class="btn btn-link text-danger" data-toggle="tooltip"
                                        data-placement="middle" title="Borrar cliente"
                                        onclick="
                                                event.preventDefault();

                                                let advertencia = 'Borrar un cliente implica archivarlo. El mismo no se presentará en ningún listado, excepto en el histórico de cotizaciones. Sin embargo todas las cotizaciones en estado pendientes, serán descartadas inmediatamente';
                                                Swal.fire({
                                                    icon: 'warning',
                                                    title: '¿Está seguro de eliminar este cliente?',
                                                    html: '<p style=\'color: red; font-wieght:800; font-size:1.3em;\'>¡ATENCION!</p><br>' + advertencia,
                                                    confirmButtonText: 'Borrar',
                                                    showCancelButton: true,
                                                    cancelButtonText: 'Cancelar',
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        $('#frm-borrar-{{ $cliente->id }}').submit()
                                                    }
                                                });
                                            ">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </form>
                            </div>
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
        // se agrega el campo de búsqueda por columna
        $('#tabla_clientes tfoot th').slice(0, 4).each(function() {
            $(this).html('<input type="text" class="form-control" placeholder="Buscar" />');
        });

        // el datatable es responsivo y oculta columnas de acuerdo al ancho de la pantalla
        var tabla_clientes = $('#tabla_clientes').DataTable({
            "processing": true,
            "dom": 'ltip',
            "order": [0, 'asc'],
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
            initComplete: function() {
                // Apply the search
                this.api()
                    .columns([0, 1, 2, 3])
                    .every(function() {
                        var that = this;

                        $('input', this.footer()).on('keyup change clear', function() {
                            if (that.search() !== this.value) {
                                that.search(this.value).draw();
                            }
                        });
                    });
            },
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
