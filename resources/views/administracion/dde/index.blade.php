@extends('adminlte::page')

@section('title', 'Listado direcciones de entrega')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.css" />
@endsection

@section('content_header')
    <div class="row">
        <div class="col-xl-8">
            <h1>Listado de direcciones de entrega</h1>
        </div>
        <div class="col-xl-4 d-flex justify-content-xl-end">
            <a href="{{ route('home') }}" role="button" class="btn btn-md btn-secondary">Volver</a>
        </div>
    </div>
@endsection

@section('content')
    <x-adminlte-card title="Seleccione un cliente *" theme="lightblue" theme-mode="outline" body-class=" bg-gradient-lightblue">
        <div class="form-group">
            <select name="cliente_id" id="input-cliente" class="seleccion-cliente form-control-alternative">
                <option data-placeholder="true"></option>
                @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->id }}">[{{ $cliente->nombre_corto }}]
                        {{ $cliente->razon_social }}</option>
                @endforeach
            </select>
        </div>
    </x-adminlte-card>

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
<div class="card">
    <div class="card-body">
        <table id="tabla-dde" class="table table-sm table-bordered table-responsive-md" style="width: 100%;">
            <thead>
                <th>Lugar de entrega</th>
                <th>Domicilio</th>
                <th>Localidad</th>
                <th>Provincia</th>
                <th>Condiciones</th>
                <th>Observaciones</th>
                <th>BORRADO</th>
                <th></th>
            </thead>
            <tbody id="wbodychange"></tbody>
        </table>
    </div>
</div>
@endsection

@section('js')
    @include('partials.alerts')
    <script type="text/javascript" src="{{ asset('js/datatables-spanish.js') }}" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.js"></script>

    <script>

        $(document).ready(function() {
            var tabla_dde = $('#tabla-dde').DataTable({
                "dom": "t",
                "processing": true,
                "columns": [
                    {
                        "data": "lugar_entrega",
                        "class": "align-middle",
                        "width": 170
                    },
                    {
                        "data": "domicilio",
                        "class": "align-middle"
                    },
                    {
                        "data": "localidad",
                        "class": "align-middle"
                    },
                    {
                        "data": "provincia",
                        "class": "align-middle"
                    },
                    {
                        "data": "condiciones",
                        "class": "align-middle"
                    },
                    {
                        "data": "observaciones",
                        "class": "align-middle"
                    },
                    {
                        "data": "borrado",
                        "class": "align-middle text-center",
                        "width": 100
                    },
                    {
                        "data": "acciones",
                        "class": "align-middle text-center",
                        "width": 100
                    }
                ],
            });

            function getDirecciones(dde) {
                let datos = {
                    cliente_id: dde.value,
                };

                $.ajax({
                    url: "{{ route('administracion.dde.ajax.obtenerDde') }}",
                    type: "GET",
                    data: datos,
                }).done(function(resultado) {
                    tabla_dde.clear().draw();
                    tabla_dde.rows.add(resultado).draw();
                });
            }

            const cliente = new SlimSelect({
                select: '.seleccion-cliente',
                placeholder: 'Seleccione el nombre corto o largo del cliente',
                onChange: (dde) => {
                    getDirecciones(dde);
                }
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
