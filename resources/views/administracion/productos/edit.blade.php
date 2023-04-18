@extends('adminlte::page')

@section('title', 'Editar Producto')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.css" />
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
        <div class="col-md-8">
            <h1>Editar producto</h1>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <a href="{{ route('administracion.productos.index') }}" role="button" class="btn btn-md btn-secondary">
                Volver
            </a>
        </div>
    </div>
@endsection

@section('content')
    @section('plugins.Datatables', true)
    @section('plugins.DatatablesPlugins', true)
    <form action="#" method="post" class="needs-validation" autocomplete="off" novalidate>
        @csrf

        @include('administracion.productos.partials.form-edit')
    </form>

    {{-- MODAL AGREGAR PROVEEDOR - FORMULARIO JAVASCRIPT --}}
    <div class="modal fade" id="modalAgregarroveedor" tabindex="-1" aria-labelledby="" aria-hidden="true">
        @section('plugins.inputmask', true)
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="javascript:void(0)" method="post" id="formAgregaProveedor">
                    @csrf
                    <div class="modal-header bg-gradient-blue">
                        <h5 class="modal-title">Agregar proveedor a producto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-9">
                                <label for="input-nombre">Proveedor *</label>
                                <select name="proveedor" id="input-nombre"
                                    class="selecion-proveedor form-control-alternative @error('proveedor') is-invalid @enderror">
                                    <option data-placeholder="true"></option>
                                    @foreach ($proveedores as $proveedor)
                                        @if ($proveedor->id == old('proveedor'))
                                            <option value="{{ $proveedor->id }}" selected>{{ $proveedor->razon_social }} -
                                                {{ $proveedor->cuit }}</option>
                                        @else
                                            <option value="{{ $proveedor->id }}">{{ $proveedor->razon_social }} -
                                                {{ $proveedor->cuit }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('proveedor')<div class="invalid-feedback">{{$message}}</div>@enderror
                            </div>

                            <div class="form-group col-3">
                                <label for="input-codigo_proveedor">Codigo de Proveedor *</label>
                                <input type="text" name="codigo_proveedor" id="input-codigo_proveedor"
                                    class="form-control form-control-sm @error('codigo_proveedor') is-invalid @enderror"
                                    value="{{ old('codigo_proveedor') }}" autofocus>
                                    @error('codigo_proveedor')<div class="invalid-feedback">{{$message}}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" id="guardarProveedor" class="btn btn-success">Agregar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @include('partials.alerts')
    <script type="text/javascript" src="{{ asset('js/datatables-spanish.js') }}" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.js"></script>
    <script>
        var tablaProveedores;

        new SlimSelect({
            select: '.selecion-presentacion',
            placeholder: 'Seleccione una presentación de la lista',
        });
        var proveedor = new SlimSelect({
            select: '.selecion-proveedor',
            placeholder: 'Seleccione un proveedor de la lista',
        });

        //obtiene los proveedores y los metete en el dt de proveedores
        function getProveedores(idProducto, idPresentacion) {
            var datos = {
                producto: idProducto,
                presentacion: idPresentacion
            };

            $.ajax({
                url: "{{ route('administracion.productos.ajaxProveedores') }}",
                type: "GET",
                data: datos,
            }).done(function(resultado) {
                tablaProveedores.clear();
                tablaProveedores.rows.add(resultado).draw();
            });
        };

        $('#formAgregaProveedor').on('submit', function( event ) {
            event.preventDefault();

            let datos_prov = new FormData(this);
            datos_prov.append("producto", {{$producto->id}});
            datos_prov.append("presentacion", {{$presentacion->id}});

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('administracion.productos.ajaxNuevoProveedor')}}",
                type: "POST",
                data: datos_prov,
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(response)
                {
                    Swal.fire({
                        title: 'Agregar proveedor',
                        icon: 'success',
                        text: response.success,
                        showConfirmButton: false,
                        timer: 2500
                    });

                    $('#modalAgregarroveedor').modal('toggle')

                    getProveedores({{$producto->id}}, {{$presentacion->id}})
                },
                error: function(response) {
                    var errors = response.responseJSON;
                    errores = '';
                    $.each( errors.errors, function( key, value ) {
                        errores += value;
                    });
                    Swal.fire({
                        icon: 'error',
                        text: errores,
                        showConfirmButton: true,
                    });
                }
            });
        });

        $(document).ready(function() {
            $('#input-cuit').inputmask("9{2}-9{8}-9{1}");

            tablaProveedores = $('#tablaProveedores').DataTable({
                "processing": true,
                "scrollY": '20vh',
                "scrollCollapse": true,
                "paging": false,
                "info": false,
                "ordering": false,
                "searching": false,
                "select": false,
                "columns": [
                    {
                        targets: [0],
                        data: 'razon_social',
                        width: '33%',
                    },
                    {
                        targets: [0],
                        data: 'cuit',
                        width: '33%',
                    },
                    {
                        targets: [1],
                        data: 'contacto',
                        width: '33%'
                    }
                ],
            });

            getProveedores({{$producto->id}}, {{$presentacion->id}});

            tablaProveedores.row.add({
                razon_social: '*',
                cuit: '*',
                contacto: '*',
            }).draw();
        });

    </script>
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión de software 2.8</b> (PHP: v{{phpversion()}} | LARAVEL: v.{{App::VERSION()}})
    </div>
@endsection
