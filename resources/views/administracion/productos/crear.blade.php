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
        <div class="col-md-8">
            <h1>Crear producto y sus características</h1>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <a href="{{ url()->previous() }}" role="button"
                class="btn btn-md btn-secondary">Volver a productos</a>
        </div>
    </div>
@stop

@section('content')
    <form action="{{ route('administracion.productos.store') }}" method="post" class="needs-validation" autocomplete="off" novalidate>
        @csrf
        {{-- NOMBRE DE LA DROGA --}}
        <div class="card">
            <div class="card-header">
                <div class="row d-flex">
                    <div class="col-8">
                        <h6 class="heading-small text-muted mb-1">NOMBRE DEL PRODUCTO</h6>
                        <input type="checkbox" id="sin_lote" name="sin_lote" {{old('sin_lote') == 1 ? 'checked' : ''}}>
                        <label for="sin_lote">Producto sin lote</label>
                    </div>
                    <div class="col-4 text-right">
                        <button type="submit" class="btn btn-sidebar btn-success"><i class="fas fa-share-square"></i>&nbsp;<span class="hide">Guardar</span></button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="pl-lg-4">
                    <div class="form-group">
                        <label for="input-droga">Droga</label>
                        <input type="text" name="droga" id="input-droga"
                            class="form-control @error('droga') is-invalid @enderror"
                            placeholder="Nombre genérico o compuesto principal representativo"
                            value="{{ old('droga') }}" autofocus>
                            @error('droga')<div class="invalid-feedback">{{$message}}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card-group">
            {{-- DATOS DE PROVEEDOR --}}
            <div class="card mr-2">
                <div class="card-header">
                    <div class="row d-flex">
                        <div class="col-8">
                            <h6 class="heading-small text-muted mb-1">PROVEEDOR</h6>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('administracion.proveedores.create') }}" class="btn btn-sm btn-info"
                                role="button"><i class="fas fa-plus fa-sm"></i>&nbsp;<span class="hide">proveedor</span></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="pl-lg-4">
                        <div class="form-group">
                            <label for="input-nombre">Razón social</label>
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
                    </div>
                </div>
            </div>

            {{-- DATOS DE PRESENTACION --}}
            <div class="card ml-2">
                <div class="card-header">
                    <div class="row d-flex">
                        <div class="col-8">
                            <h6 class="heading-small text-muted mb-1">PRESENTACION</h6>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('administracion.presentaciones.create') }}" class="btn btn-sm btn-info"
                                role="button"><i class="fas fa-plus fa-sm"></i>&nbsp;<span class="hide">presentación</span></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="pl-lg-4">
                        <div class="form-group">
                            <label for="input-presentacion">Forma farmacéutica y presentación</label>
                            <select name="presentacion" id="input-presentacion"
                                class="selecion-presentacion form-control-alternative @error('presentacion') is-invalid @enderror">
                                <option data-placeholder="true"></option>
                                @foreach ($presentaciones as $presentacion)
                                    @if ($presentacion->id == old('presentacion'))
                                        <option value="{{ $presentacion->id }}" selected>
                                            @if ($presentacion->hospitalario)
                                                <b>H - </b>
                                            @endif
                                            {{ $presentacion->forma }}, {{ $presentacion->presentacion }}
                                            @if ($presentacion->trazabilidad)
                                                <b> - Trazable</b>
                                            @endif
                                        </option>
                                    @else
                                        <option value="{{ $presentacion->id }}">
                                            @if ($presentacion->hospitalario)
                                                <b>H - </b>
                                            @endif
                                            {{ $presentacion->forma }}, {{ $presentacion->presentacion }}
                                            @if ($presentacion->trazabilidad)
                                                <b> - Trazable</b>
                                            @endif
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('presentacion')<div class="invalid-feedback">{{$message}}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- DATOS DEL LOTE --}}
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="heading-small text-muted mb-1">DATOS DEL LOTE</h6>
            </div>
            <div class="card-body">
                <div class="pl-lg-4">
                    <div class="form-row d-flex">
                        {{-- Campo identificador de lote, precio y cantidad --}}
                        <div class="form-group col-md-4 mb-3">
                            <label for="identificador"
                                class="label">{{ __('formularios.batch_identification') }}</label>
                            <div class="input-group mb-3">
                                <input type="text" name="identificador" id="identificador"
                                    class="form-control @error('identificador') is-invalid @enderror"
                                    value="{{ old('identificador') }}">
                                <div class="input-group-append">
                                    <button type="button" id="escanear" class="btn btn-sm btn-dark">
                                        <i class="fas fa-barcode"></i>
                                        Escanear
                                    </button>
                                </div>
                                @error('identificador')<span class="invalid-feedback" role="alert">{{$message}}</span>@enderror
                            </div>
                        </div>

                        <div class="form-group col-md-4 mb-3">
                            <label for="precio_compra" class="label">{{ __('formularios.batch_price') }}</label>
                            <input type="text" name="precio_compra" id="precio_compra" class="form-control @error('precio_compra') is-invalid @enderror" value="{{ old('precio_compra') }}">
                            @error('precio_compra')<div class="invalid-feedback">{{$message}}</div>@enderror
                        </div>

                        <div class="form-group col-md-4 mb-3">
                            <label for="cantidad" class="label">{{ __('formularios.batch_quantity') }}</label>
                            <input type="text" name="cantidad" id="cantidad" class="form-control @error('cantidad') is-invalid @enderror" value="{{ old('cantidad') }}">
                            @error('cantidad')<div class="invalid-feedback">{{$message}}</div>@enderror
                        </div>
                    </div>

                    <div class="form-row d-flex">
                        {{-- Campos de fechas: elaboración, compra y vencimiento --}}
                        <div class="form-group col-md-4 mb-3">
                            @section('plugins.TempusDominusBs4', true)
                            <x-adminlte-input-date name="fecha_elaboracion" id="fecha_elaboracion"
                                label="{{ __('formularios.batch_elaboration') }}" igroup-size="md" :config="$config"
                                placeholder="{{ __('formularios.date_placeholder') }}" autocomplete="off"
                                value="{{ old('fecha_elaboracion') }}">
                                <x-slot name="appendSlot">
                                    <div class="input-group-text bg-dark">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input-date>
                            @error('fecha_elaboracion')<div class="invalid-feedback">{{$message}}</div>@enderror
                        </div>

                        <div class="form-group col-md-4 mb-3">
                            @section('plugins.TempusDominusBs4', true)
                            <x-adminlte-input-date name="fecha_compra" id="fecha_compra"
                                label="{{ __('formularios.batch_purchase') }}" igroup-size="md" :config="$config"
                                placeholder="{{ __('formularios.date_placeholder') }}" autocomplete="off"
                                value="{{ old('fecha_compra') }}">
                                <x-slot name="appendSlot">
                                    <div class="input-group-text bg-dark">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input-date>
                            @error('fecha_compra')<div class="invalid-feedback">{{$message}}</div>@enderror
                        </div>

                        <div class="form-group col-md-4 mb-3">
                            @section('plugins.TempusDominusBs4', true)
                            <x-adminlte-input-date name="fecha_vencimiento" id="fecha_vencimiento"
                                label="{{ __('formularios.batch_expiration') }}" igroup-size="md" :config="$config"
                                placeholder="{{ __('formularios.date_placeholder') }}" autocomplete="off"
                                value="{{ old('fecha_vencimiento') }}">
                                <x-slot name="appendSlot">
                                    <div class="input-group-text bg-dark">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input-date>
                            @error('fecha_vencimiento')<div class="invalid-feedback">{{$message}}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>
            <div id="cuerpo_lote"></div>
        </div>
    </form>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#sin_lote").change(function() {
                $('#cuerpo_lote').toggleClass('overlay');
            });
        });
        new SlimSelect({
            select: '.selecion-proveedor',
            placeholder: 'Seleccione un proveedor de la lista',
        })

        new SlimSelect({
            select: '.selecion-presentacion',
            placeholder: 'Seleccione una presentación de la lista',
        })
    </script>
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión</b> 2.0 (LARAVEL V.8)
    </div>
@endsection
