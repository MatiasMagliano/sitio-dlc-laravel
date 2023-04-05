@extends('adminlte::page')

@section('title', 'Administrar Productos')

@section('css')
    <style>
        .texto-header {
            padding: 20px;
            height: 90px;
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
            background-color: #3bd136;
        }

        @media (max-width: 600px) {
            .hide {
                display: none;
            }
        }
    </style>
@endsection

@section('content_header')
    <div class="row">
        <div class="col-xl-8">
            <h1>Administración de productos</h1>
        </div>
        <div class="col-xl-4 d-flex justify-content-md-end">
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
    <div class="card-header bg-gray-light">
        <div class="texto-header">
            <h5>Productos, descripción y stock</h5>
            <p>
                Los términos de búsqueda se realizan en los campos debajo de cada columna habilitada.
            </p>
            <p>
                Desde aquí podrá modificar el nombre de la droga y su presentación. Para modificar los lotes de producto, ingrese en la <a href="route('administracion.lotes.index')" class="btn-link">siguiente</a> sección.
            </p>
        </div>
    </div>
    <div class="card-body">
        <table id="tabla-productos" class="table table-bordered" width="100%">
            <thead class="bg-gray">
                <th>Droga</th>
                <th>Presentación</th>
                {{-- <th>HOSP/TRAZ</th> --}}
                <th>Lotes Nº</th>
                <th>STOCK - CASA CENTRAL<br><span class="small text-nowrap">existencia | cotizización | disponible</span></th>
                <th></th>
            </thead>
            <tfoot style="display: table-header-group;">
                <tr class=" bg-gradient-light">
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
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
        moment.locale('es');

        $('#tabla-productos tfoot th').slice(0, 2).each(function() {
            $(this).html('<input type="text" class="form-control" placeholder="Buscar" />');
        });

        $('#tabla-productos').DataTable({
            dom: "rltip",
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('administracion.productos.ajax') }}",
                method: "GET"
            },
            order: [0, 'asc'],
            columnDefs: [
                {
                    targets: [0],
                    name: "droga",
                    className: "align-middle",
                },
                {
                    targets: [1],
                    name: "presentacion",
                    className: "align-middle",
                },
                {
                    targets: [2],
                    name: "lotes",
                    className: "align-middle small",
                },
                {
                    targets: [3],
                    name: "stock",
                    className: "align-middle",
                    width: 100,
                    orderable: false,
                },
                {
                    targets: [4],
                    name: "acciones",
                    className: "align-middle",
                    orderable: false,
                },
            ],
            "initComplete": function() {
                this.api()
                    .columns([0, 1])
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
    <b>Versión</b> 2.0 (LARAVEL V.8)
</div>
@endsection
