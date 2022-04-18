@extends('adminlte::page')

@section('title', 'Administrar Clientes')

@section('css')
    <style>
        .texto-header{
            padding: 0 20px;
            height: 60px;
            overflow-y: auto;
            /*font-size: 14px;*/
            font-weight: 500;
            color: #000000;
        }

        .texto-header::-webkit-scrollbar{
            width: 5px;
            background-color: #282828;
        }

        .texto-header::-webkit-scrollbar-thumb{
            background-color: #3bd136;
        }
    </style>
@endsection

@section('content_header')
    <div class="row">
        <div class="col-md-8">
            <h1>Administración de clientes</h1>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <a href="{{ route('administracion.clientes.create') }}" role="button"
                class="btn btn-md btn-success">Crear cliente</a>
            &nbsp;
            <a href="{{ url()->previous() }}" role="button"
                class="btn btn-md btn-secondary">Volver</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')
    <div class="card">
        <div class="card-header texto-header">
            <h5>Instrucciones para generar reportes</h5>
            <p>Para exportar los datos de la siguiente tabla, seleccione primero las columnas visibles en el set de herramientas disponible y luego genere la extensión que necesite.</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum fermentum mi mauris, id eleifend nunc tempor non. Mauris porttitor malesuada ullamcorper. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Praesent at hendrerit ante. Nunc fringilla consectetur pellentesque. Nunc consectetur pharetra facilisis. Proin ligula nisl, viverra vel dictum vitae, fermentum ac augue. Integer ornare nulla eros, mollis ornare tortor aliquet non. Duis suscipit urna aliquam, rhoncus lorem eget, tempus ante. Phasellus sed lacus id neque vestibulum tincidunt. Nam felis felis, consectetur sed nisi eget, porta lobortis augue. Maecenas suscipit erat ante, id dapibus diam sollicitudin consectetur. Donec id tempus neque. Curabitur porttitor laoreet lorem vitae suscipit.</p>
        </div>
        <div class="card-body">
            <table id="tabla_clientes" class="table table-bordered table-responsive-md" width="100%">
                <thead>
                    <th>ID</th>
                    <th>Nombre corto</th>
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
                            <td>{{$cliente->id}}</td>
                            <td>{{$cliente->nombre_corto}}</td>
                            <td>{{$cliente->razon_social}}</td>
                            <td>
                                <span style="text-transform: uppercase;">{{$cliente->tipo_afip}}</span>: {{$cliente->afip}}
                            </td>
                            <td>
                                <u>Sr/a: {{$cliente->contacto}}</u> <br>
                                Tel: {{$cliente->telefono}} <br>
                                E-mail: {{$cliente->email}}
                            </td>
                            <td>{{$cliente->direccion}}</td>
                            <td>{{$cliente->ultima_compra}}</td>
                            <td></td>
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
                "dom": 'Bfrtip',
                "order": [1, 'asc'],
                "buttons": [
                    {extend: 'copyHtml5', text: 'Copiar al portapapeles', exportOptions: {columns: ':visible'}},
                    {extend: 'excelHtml5', exportOptions: {columns: ':visible'}},
                    {extend: 'print', text: 'Imprimir', exportOptions: {columns: [1, 2, 3, 4, 5, 6]}},
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
