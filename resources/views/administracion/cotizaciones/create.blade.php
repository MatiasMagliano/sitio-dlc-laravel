@extends('adminlte::page')

@section('title', 'Administrar Productos')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.css" />
    <style>
        @media (max-width: 600px) {
            .hide {
                display: none;
            }
        }
    </style>
@endsection

@section('content_header')
    <div class="row">
        <div class="col-md-10">
            <h1>Crear cotización</h1>
        </div>
        <div class="col-md-2 d-flex justify-content-xl-end">
            <a href="{{ route('administracion.cotizaciones.index') }}" role="button"
                class="btn btn-md btn-secondary">Volver a cotizaciones</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')
    <x-adminlte-card>
        <form action="{{ route('administracion.cotizaciones.store') }}" method="post" class="needs-validation m-3" autocomplete="off" novalidate>
            @csrf

            <input type="hidden" name="user_id" value="{{ Auth::id() }}">

            <h6 class="heading-small text-muted mb-4">Datos básicos de la cotización</h6>
            <hr>
            <div class="pl-lg-4">
                <div class="form-group">
                    <label for="input-identificador">Identificador *</label>
                    <input type="text" name="identificador" id="input-identificador"
                        class="form-control @error('identificador') is-invalid @enderror"
                        placeholder="Identificador de licitación provisto por el cliente"
                        value="{{ old('identificador') }}" autofocus>
                    @error('identificador')<div class="invalid-feedback">{{$message}}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="input-cliente">Cliente *</label>
                    <select name="cliente_id" id="input-cliente"
                        class="selecion-cliente form-control-alternative @error('cliente_id') is-invalid @enderror">
                        <option data-placeholder="true"></option>
                        @foreach ($clientes as $cliente)
                            @if ($cliente->id == old('cliente_id'))
                                <option value="{{ $cliente->id }}" selected>[{{ $cliente->nombre_corto }}]
                                    {{ $cliente->razon_social }}</option>
                            @else
                                <option value="{{ $cliente->id }}">[{{ $cliente->nombre_corto }}]
                                    {{ $cliente->razon_social }}</option>
                            @endif
                        @endforeach
                    </select>
                    @error('cliente_id')<div class="invalid-feedback">{{$message}}</div>@enderror
                </div>

                {{-- SE CARGA CON UN AJAX --}}
                <div class="form-group">
                    <label for="input-dde">Punto de entrega</label>
                    <select name="dde_id" id="input-dde"
                        class="selecion-dde form-control-alternative @error('dde_id') is-invalid @enderror">
                        <option data-placeholder="true"></option>
                    </select>
                    @error('dde_id')<div class="invalid-feedback">{{$message}}</div>@enderror
                </div>

                <button type="submit" class="btn btn-success mt-4">Continuar</button>
            </div>
        </form>
    </x-adminlte-card>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.js"></script>
    <script>
        let old_cliente = {{@old('cliente_id', $cliente->id)}};
        var dde = new SlimSelect({
            select: '.selecion-dde',
            placeholder: 'Seleccione un punto de entrega',
        });

        var cliente = new SlimSelect({
            select: '.selecion-cliente',
            placeholder: 'Seleccione el nombre corto o largo del cliente',
            @if(@old('dde_id'))
                beforeOnChange: getDirecciones(old_cliente)
            @else
                onChange: (info) => {
                    getDirecciones(info.value);
                }
            @endif
        });

        function getDirecciones(cliente){
            let datos = {
                cliente_id: cliente,
            };

            $.ajax({
                url: "{{route('administracion.clientes.ajax.obtenerDde')}}",
                type: "GET",
                data: datos,
            }).done(function(resultado) {
                dde.setData(resultado);
            });
        }
    </script>
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión de software 2.8</b> (PHP: v{{phpversion()}} | LARAVEL: v.{{App::VERSION()}})
    </div>
@endsection
