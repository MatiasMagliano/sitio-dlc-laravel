@extends('adminlte::page')

@section('title', 'Crear dirección de entrega')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.css" />
@endsection

@section('content_header')
    <div class="row">
        <div class="col-xl-8">
            <h1>Crear dirección de entrega</h1>
        </div>
        <div class="col-xl-4 d-flex justify-content-xl-end">
            <a href="{{ route('administracion.clientes.index') }}" role="button" class="btn btn-md btn-secondary">Volver</a>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{route('administracion.dde.store')}}" method="post" class="needs-validation" autocomplete="off" novalidate>
        @csrf
        <x-adminlte-card title="Cliente *" theme="lightblue" theme-mode="outline" body-class=" bg-gradient-lightblue">
            <div class="form-group">
                <select name="cliente_id" id="input-cliente" class="selecion-cliente form-control-alternative">
                    <option data-placeholder="true"></option>
                    @foreach ($clientes as $cliente)
                        <option value="{{ $cliente->id }}">[{{ $cliente->nombre_corto }}]
                            {{ $cliente->razon_social }}</option>
                    @endforeach
                </select>
            </div>
        </x-adminlte-card>

        <div class="card d-none" id="card-formulario">
            <div class="card-body">
                @include('administracion.dde.partials.formulario')
                <div class="text-right">
                    <button type="submit" class="btn btn-sidebar btn-success"><i
                            class="fas fa-share-square"></i>&nbsp;<span class="hide">Guardar</span></button>
                </div>
            </div>
        </div>
    </form>

    @section('plugins.Datatables', true)
    @section('plugins.DatatablesPlugins', true)
    <div class="card d-none" id="card-tabla">
        <div class="card-body">
            <table id="tabla-dde" class="table table-sm table-bordered table-responsive-md" style="width: 100%;">
                <thead>
                    <th>Lugar de entrega</th>
                    <th>Cantidad de envíos</th>
                    <th>Domicilio</th>
                    <th>Localidad</th>
                    <th>Provincia</th>
                    <th>Condiciones</th>
                    <th>Observaciones</th>
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
    var herramientas = "<a href='#' class='btn btn-link' data-toggle='tooltip' data-placement='middle' title='Editar producto'>"
        + "<i class='fas fa-pencil-alt'></i>"
        + "</a>"
        + "<a href='#' role='button' class='btn btn-link text-danger' data-toggle='tooltip' data-placement='middle' title='Editar producto'>"
        + "<i class='fas fa-trash-alt'></i>"
        + "</a>";

    var tabla_dde = $('#tabla-dde').DataTable({
        "dom": "t",
        "processing": true,
        "retrieve": true,
        "columns": [{
                "data": "lugar_entrega",
                "class": "align-middle",
                "width": 170
            },
            {
                "data": "cantidad_enviado",
                "class": "align-middle text-center",
                "width": 150
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
                "data": null,
                "defaultContent": herramientas,
                "class": "align-middle text-center",
                "width": 130
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

    const provincia = new SlimSelect({
        select: '.selector-provincia',
        placeholder: 'Seleccione una provincia',
        onChange: (info) => {
            getLocalidades(info);
        }
    });

    const localidad = new SlimSelect({
        select: '.selector-localidad',
        placeholder: 'Seleccione una localidad',
    });

    function getLocalidades(provinciaSeleccionada) {
        let datos = {
            provincia_id: provinciaSeleccionada.value,
        };

        $.ajax({
            url: "{{ route('administracion.clientes.ajax.obtenerLocalidades') }}",
            type: "GET",
            data: datos,
        }).done(function(resultado) {
            localidad.setData(resultado);
        });
    }

    const cliente = new SlimSelect({
        select: '.selecion-cliente',
        placeholder: 'Seleccione el nombre corto o largo del cliente',
        onChange: (dde) => {
            $('#card-formulario').removeClass('d-none');
            $('#card-tabla').removeClass('d-none');
            getDirecciones(dde);
        }
    });
</script>
@endsection

@section('footer')
<strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
<div class="float-right d-none d-sm-inline-block">
    <b>Versión de software 2.8</b> (PHP: v{{phpversion()}} | LARAVEL: v.{{App::VERSION()}})
</div>
@endsection
