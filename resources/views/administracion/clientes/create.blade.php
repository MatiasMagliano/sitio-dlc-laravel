@extends('adminlte::page')

@section('title', 'Administrar Clientes')

@section('css')
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
            <h1>Administración de clientes</h1>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <a href="{{ url()->previous() }}" role="button"
                class="btn btn-md btn-secondary">Volver</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')
    <form action="route('administracion.clientes.store')" method="post" class="needs-validation" autocomplete="off">
        @csrf

        <div class="card">
            <div class="card-header">
                <div class="row d-flex">
                    <div class="col-8">
                        <h6 class="heading-small text-muted mb-1">NUEVO CLIENTE</h6>
                    </div>
                    <div class="col-4 text-right">
                        <button type="submit" class="btn btn-sidebar btn-success"><i class="fas fa-share-square"></i>&nbsp;<span class="hide">Guardar</span></button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="pl-lg-4">
                    <h6 class="heading-small text-muted mb-1 mt-2">Datos básicos</h6>
                    <hr>
                    <div class="row d-flex">
                        <div class="form-group col-3">
                            <label for="input-nombre_corto">Nombre corto</label>
                            <input type="text" name="nombre_corto" id="input-nombre_corto"
                                class="form-control @error('nombre_corto') is-invalid @enderror"
                                value="{{ old('nombre_corto') }}" autofocus>
                                @error('nombre_corto')<div class="invalid-feedback">{{$message}}</div>@enderror
                        </div>
                        <div class="form-group col">
                            <label for="input-razon_social">Razón social</label>
                            <input type="text" name="razon_social" id="input-razon_social"
                                class="form-control @error('razon_social') is-invalid @enderror"
                                placeholder="Nombre completo del cliente o nombre de fantasía"
                                value="{{ old('razon_social') }}">
                                @error('razon_social')<div class="invalid-feedback">{{$message}}</div>@enderror
                        </div>
                    </div>
                    <div class="row d-flex">
                        <div class="form-group col-12">
                            <label for="input-direccion">Dirección de envío</label>
                            <input type="text" name="direccion" id="input-direccion"
                                class="form-control @error('direccion') is-invalid @enderror"
                                placeholder="Nombre completo del cliente o nombre de fantasía"
                                value="{{ old('direccion') }}">
                                @error('direccion')<div class="invalid-feedback">{{$message}}</div>@enderror
                        </div>
                    </div>

                    <h6 class="heading-small text-muted mb-1 mt-5">Datos impositivos</h6>
                    <hr>
                    <div class="row d-flex">
                        <div class="form-group col-3">
                            <label for="input-tipo_afip"><span class="hide">Tipo de</span> inscripción</label>
                            <select name="tipo_afip" id="input-tipo_afip"
                                class="form-control @error('tipo_afip') is-invalid @enderror">
                                <option>Seleccione una opción</option>
                                <option value="cuil" {{ old('tipo_afip') == 'cuil' ? "selected" : "" }} >CUIL</option>
                                <option value="cuit" {{ old('tipo_afip') == 'cuit' ? "selected" : "" }} >CUIT</option>
                            </select>
                            @error('tipo_afip')<div class="invalid-feedback">{{$message}}</div>@enderror
                        </div>
                        <div class="form-group col">
                            <label for="input-afip">Número</label>
                            <input type="text" name="afip" id="input-afip"
                                class="form-control @error('afip') is-invalid @enderror"
                                value="{{ old('afip') }}">
                                @error('afip')<div class="invalid-feedback">{{$message}}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('js')
    @include('partials.alerts')
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión</b> 2.0 (LARAVEL V.8)
    </div>
@endsection
