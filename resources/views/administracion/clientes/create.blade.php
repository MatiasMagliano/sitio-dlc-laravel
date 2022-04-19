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
    <form action="{{route('administracion.clientes.store')}}" method="post" class="needs-validation" autocomplete="off" novalidate>
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

                    <h6 class="heading-small text-muted mb-1 mt-5">Datos de envío</h6>
                    <hr>
                    <div class="row d-flex">
                        <div class="form-group col">
                            <label for="provincia">Provincia</label>
                            <select id="provincia" name="provincia" class="form-control" data-live-search="true"></select>
                        </div>
                        <div class="form-group col">
                            <label for="localidad">Localidad</label>
                            <select id="localidad" name="localidad" class="form-control" data-live-search="true"></select>
                        </div>
                    </div>

                    <h6 class="heading-small text-muted mb-1 mt-5">Datos Impositivos</h6>
                    <hr>
                    <div class="row d-flex">
                        <div class="form-group col-3">
                            <label for="input-tipo_afip"><span class="hide">Tipo de</span> Inscripción</label>
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
                            <input type="number" name="afip" id="input-afip"
                                class="form-control @error('afip') is-invalid @enderror"
                                value="{{ old('afip') }}">
                            <small id="input-afip" class="form-text text-muted">Sin guiones ni otros caracteres.</small>
                            @error('afip')<div class="invalid-feedback">{{$message}}</div>@enderror
                        </div>
                    </div>

                    <h6 class="heading-small text-muted mb-1 mt-5">Datos de Contacto</h6>
                    <hr>
                    <div class="row d-flex">
                        <div class="form-group col-12">
                            <label for="input-contacto">Persona de contacto</label>
                            <input type="text" name="contacto" id="input-contacto"
                                class="form-control @error('contacto') is-invalid @enderror"
                                value="{{ old('contacto') }}">
                            @error('contacto')<div class="invalid-feedback">{{$message}}</div>@enderror
                        </div>
                    </div>
                    <div class="row d-flex">
                        <div class="form-group col">
                            <label for="input-telefono">Teléfono</label>
                            <input type="number" name="telefono" id="input-telefono"
                                class="form-control @error('telefono') is-invalid @enderror"
                                value="{{ old('telefono') }}">
                            <small id="input-afip" class="form-text text-muted">Sin guiones, paréntesis u otros caracteres especiales.</small>
                            @error('telefono')<div class="invalid-feedback">{{$message}}</div>@enderror
                        </div>
                        <div class="form-group col">
                            <label for="input-email">E-mail</label>
                            <input type="email" name="email" id="input-email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}">
                            @error('email')<div class="invalid-feedback">{{$message}}</div>@enderror
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
