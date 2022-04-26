<div class="pl-lg-4">
    <h6 class="heading-small text-muted mb-1 mt-2">Datos básicos</h6>
    <hr>
    <div class="row d-flex">
        <div class="form-group col-2">
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

        <div class="form-group col-2">
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
            <small id="input-afip" class="form-text text-muted">Sin guiones u otros caracteres.</small>
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

    <h6 class="heading-small text-muted mb-1 mt-5">Datos de envío</h6>
    <p>Para agregar más puntos de envío, vaya a la sección <a href="{{route('administracion.clientes.agregarPuntoEntrega')}}">Cliente/Agregar puntos de entrega</a>.</p>
    <hr>
    <div class="row d-flex">
        <div class="form-group col">
            <label for="input-lugar_entrega">Lugar de entrega</label>
            <input type="lugar_entrega" name="lugar_entrega" id="input-lugar_entrega"
                class="form-control form-control-sm @error('lugar_entrega') is-invalid @enderror"
                value="{{ old('lugar_entrega') }}">
            @error('lugar_entrega')<div class="invalid-feedback">{{$message}}</div>@enderror
        </div>
    </div>

    <div class="row d-flex">
        <div class="form-group col-md-7">
            <label for="input-domicilio">Dirección</label>
            <input type="domicilio" name="domicilio" id="input-domicilio"
                class="form-control form-control-sm @error('domicilio') is-invalid @enderror"
                value="{{ old('domicilio') }}">
            @error('domicilio')<div class="invalid-feedback">{{$message}}</div>@enderror
        </div>

        <div class="form-group col">
            <label for="input-provincia">Provincia</label>
            <select name="provincia_id" id="input-provincia"
                class="selector-provincia @error('provincia_id') is-invalid @enderror">
                <option data-placeholder="true"></option>
                @foreach ($provincias as $provincia)
                    @if ($provincia->id == old('provincia_id'))
                        <option value="{{ $provincia->id }}" selected>{{ $provincia->nombre }}</option>
                    @else
                        <option value="{{ $provincia->id }}">{{ $provincia->nombre }}</option>
                    @endif
                @endforeach
            </select>
            @error('provincia_id')<div class="invalid-feedback">{{$message}}</div>@enderror
        </div>
        <div class="form-group col">
            <label for="input-localidad">Localidad</label>
            <select id="input-localidad" name="localidad_id"
                class="selector-localidad @error('localidad_id') is-invalid @enderror">
                <option data-placeholder="true"></option>
            </select>
            @error('localidad_id')<div class="invalid-feedback">{{$message}}</div>@enderror
        </div>
    </div>
    <div class="row d-flex">
        <div class="form-group col">
            <label for="input-condiciones">Condiciones</label>
            <textarea class="form-control @error('condiciones') is-invalid @enderror" id="input-condiciones" rows="2"></textarea>
            <small id="input-condiciones" class="form-text text-muted">Datos relevantes como: encargado recepción, Nº puerta, piso, edificio, etc.</small>
            @error('condiciones')<div class="invalid-feedback">{{$message}}</div>@enderror
        </div>
        <div class="form-group col">
            <label for="input-observaciones">Observaciones</label>
            <textarea class="form-control @error('observaciones') is-invalid @enderror" id="input-observaciones" rows="2"></textarea>
            @error('observaciones')<div class="invalid-feedback">{{$message}}</div>@enderror
        </div>
    </div>
</div>
