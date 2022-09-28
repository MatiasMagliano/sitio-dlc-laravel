@extends('adminlte::page')

@section('title', 'Administrar Cotizaciones')

@section('content_header')
    <div class="row">
        <div class="col-md-8">
            <h1>Administración de productos</h1>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <a href="{{ route('administracion.productos.create') }}" role="button" class="btn btn-md btn-success">Crear
                producto</a>
            &nbsp;
            <a href="{{ route('administracion.lotes.index') }}" role="button" class="btn btn-md btn-success">Crear lotes</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
@section('plugins.TempusDominusBs4', true)
<div class="card">
    <div class="card-body">
        <table id="tabla-productos" class="table table-bordered table-responsive-md" width="100%">
            <thead class="bg-gray">
                <th>Droga</th>
                <th>Presentación</th>
                <th>HOSP/TRAZ</th>
                <th>Lotes</th>
                <th>Proveedores</th>
                <th>STOCK <br><span class="small">exist. | cotiz. | disp.</span></th>
                <th></th>
            </thead>
            <tbody>
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
        $('#tabla-productos').DataTable({
            "processing": true,
            "serverSide": true,
            "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'B>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            "lengthMenu": [
                [10, 25, 50, -1],
                ['10', '25', '50', 'Todos']
            ],
            "ajax": {
                url: "{{ route('administracion.productos.ajax') }}",
                method: "GET"
            },
            "buttons": [{
                    extend: 'copyHtml5',
                    text: 'Copiar al portapapeles',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excelHtml5',
                    filename: 'productos',
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
            ],

            "columnDefs": [{
                    className: "align-middle",
                    targets: [0]
                },
                {
                    className: "align-middle",
                    targets: [1]
                },
                {
                    className: "align-middle",
                    targets: [2]
                },
                {
                    className: "align-middle",
                    targets: [3]
                },
                {
                    className: "align-middle",
                    targets: [4],
                    width: 100
                },
                {
                    className: "align-middle",
                    targets: [5],
                },
                {
                    className: "align-middle",
                    targets: [6],
                },
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
